<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EarningsTest extends TestCase
{
    use RefreshDatabase;

    public function test_earnings_report_only_shows_paid_orders_for_the_authenticated_seller(): void
    {
        $seller = User::factory()->create();
        $otherSeller = User::factory()->create();

        $ownOrder = $this->createOrder($seller, 125000, 'paid');
        $this->createOrder($seller, 50000, 'unpaid');
        $otherOrder = $this->createOrder($otherSeller, 900000, 'paid');

        $this->actingAs($seller)
            ->get(route('dashboard.orders.index'))
            ->assertOk()
            ->assertSee($ownOrder->code)
            ->assertSee('Rp 125.000')
            ->assertDontSee($otherOrder->code)
            ->assertDontSee('Rp 900.000');
    }

    public function test_earnings_report_requires_authentication(): void
    {
        $this->get(route('dashboard.orders.index'))
            ->assertRedirect(route('login'));
    }

    private function createOrder(User $seller, int $total, string $paymentStatus): Order
    {
        return Order::create([
            'seller_id' => $seller->id,
            'buyer_name' => 'Buyer',
            'buyer_contact' => '08123456789',
            'total' => $total,
            'status' => 'processing',
            'payment_method' => 'tripay_qris',
            'payment_gateway' => 'tripay',
            'payment_status' => $paymentStatus,
        ]);
    }
}
