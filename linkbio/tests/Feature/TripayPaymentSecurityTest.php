<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Support\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class TripayPaymentSecurityTest extends TestCase
{
    use RefreshDatabase;

    private string $privateKey = 'test-private-key';

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'tripay.private_key' => $this->privateKey,
            'tripay.merchant_code' => 'TTEST',
        ]);
    }

    public function test_paid_callback_requires_matching_reference_and_amount(): void
    {
        $order = $this->tripayOrder();
        $payload = $this->payload($order, ['total_amount' => 99999]);

        $response = $this->signedCallback($payload);

        $response->assertStatus(422)->assertJson(['success' => false]);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'unpaid',
        ]);
    }

    public function test_paid_callback_is_idempotent_when_repeated(): void
    {
        $order = $this->tripayOrder();
        $payload = $this->payload($order);

        $this->signedCallback($payload)->assertOk()->assertJson(['success' => true]);
        $first = Order::findOrFail($order->id);
        $this->signedCallback($payload)->assertOk()->assertJson(['success' => true]);
        $second = Order::findOrFail($order->id);

        $this->assertSame('paid', $second->payment_status);
        $this->assertSame('processing', $second->status);
        $this->assertSame($first->paid_at?->timestamp, $second->paid_at?->timestamp);
    }

    public function test_failed_callback_does_not_mark_order_paid(): void
    {
        $order = $this->tripayOrder();
        $payload = $this->payload($order, ['status' => 'FAILED']);

        $this->signedCallback($payload)->assertOk()->assertJson(['success' => true]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'unpaid',
            'status' => 'cancelled',
        ]);
    }

    public function test_invalid_signature_is_rejected(): void
    {
        $order = $this->tripayOrder();
        $payload = json_encode($this->payload($order), JSON_THROW_ON_ERROR);

        $response = $this->call('POST', route('tripay.callback'), [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_CALLBACK_EVENT' => 'payment_status',
            'HTTP_X_CALLBACK_SIGNATURE' => 'invalid',
        ], $payload);

        $response->assertStatus(400)->assertJson(['success' => false]);
    }

    public function test_seller_payment_mutation_endpoint_is_not_available(): void
    {
        $seller = User::factory()->create();
        $order = $this->tripayOrder($seller);

        $this->actingAs($seller)
            ->patch(route('dashboard.orders.index').'/'.$order->id.'/payment', ['payment_status' => 'paid'])
            ->assertNotFound();

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'payment_status' => 'unpaid']);
    }

    public function test_success_page_does_not_accept_arbitrary_order_ids(): void
    {
        $order = $this->tripayOrder();

        $this->get(route('public.checkout.success', ['orders' => $order->id]))
            ->assertNotFound();
    }

    public function test_online_checkout_with_multiple_sellers_is_rejected(): void
    {
        $this->withoutMiddleware();

        $sellerOne = User::factory()->create();
        $sellerTwo = User::factory()->create();
        $groups = new Collection([
            ['seller' => $sellerOne, 'items' => collect()],
            ['seller' => $sellerTwo, 'items' => collect()],
        ]);

        $this->mock(Cart::class, function ($mock) use ($groups) {
            $mock->shouldReceive('groups')->andReturn($groups);
        });

        $response = $this->post(route('checkout.store'), [
            'shipping_name' => 'Buyer',
            'shipping_phone' => '08123456789',
            'shipping_address' => 'Address',
            'shipping_city' => 'Jakarta',
            'payment_method' => 'tripay_qris',
        ]);

        $response->assertSessionHasErrors('payment_method');
        $this->assertDatabaseCount('orders', 0);
    }

    private function tripayOrder(?User $seller = null): Order
    {
        $seller ??= User::factory()->create();

        return Order::create([
            'seller_id' => $seller->id,
            'buyer_name' => 'Buyer',
            'buyer_contact' => '08123456789',
            'total' => 100000,
            'status' => 'awaiting_payment',
            'payment_method' => 'tripay_qris',
            'payment_gateway' => 'tripay',
            'payment_reference' => 'TL-'.$seller->id.'-TEST',
            'payment_external_id' => 'TTEST-REFERENCE',
            'payment_status' => 'unpaid',
        ]);
    }

    private function payload(Order $order, array $overrides = []): array
    {
        return array_merge([
            'reference' => $order->payment_external_id,
            'merchant_ref' => $order->payment_reference,
            'total_amount' => 100000,
            'status' => 'PAID',
            'is_closed_payment' => 1,
            'paid_at' => now()->timestamp,
        ], $overrides);
    }

    private function signedCallback(array $payload)
    {
        $body = json_encode($payload, JSON_THROW_ON_ERROR);
        $signature = hash_hmac('sha256', $body, $this->privateKey);

        return $this->call('POST', route('tripay.callback'), [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_CALLBACK_EVENT' => 'payment_status',
            'HTTP_X_CALLBACK_SIGNATURE' => $signature,
        ], $body);
    }
}
