<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TripayService
{
    /**
     * Peta kode metode lokal ke kode channel Tripay.
     */
    public const CHANNEL_MAP = [
        'tripay_qris' => 'QRIS2',
        'tripay_briva' => 'BRIVA',
        'tripay_bniva' => 'BNIVA',
        'tripay_bcava' => 'BCAVA',
        'tripay_mandiriva' => 'MANDIRIVA',
        'tripay_bsiva' => 'BSIVA',
        'tripay_permata' => 'PERMATAVA',
        'tripay_ovo' => 'OVO',
        'tripay_dana' => 'DANA',
        'tripay_shopeepay' => 'SHOPEEPAY',
        'tripay_alfamart' => 'ALFAMART',
        'tripay_indomaret' => 'INDOMARET',
    ];

    protected string $merchantCode;

    protected string $apiKey;

    protected string $privateKey;

    protected string $baseUrl;

    protected int $timeout;

    public function __construct()
    {
        $this->merchantCode = (string) config('tripay.merchant_code', '');
        $this->apiKey = (string) config('tripay.api_key', '');
        $this->privateKey = trim((string) config('tripay.private_key', ''));
        $this->baseUrl = rtrim((string) config('tripay.base_url', 'https://tripay.co.id/api-sandbox'), '/');
        $this->timeout = (int) config('tripay.timeout', 30);
    }

    protected function client(): PendingRequest
    {
        return Http::withToken($this->apiKey)
            ->acceptJson()
            ->asForm()
            ->timeout($this->timeout);
    }

    /**
     * Signature untuk create transaction: HMAC-SHA256(merchantCode . merchantRef . amount).
     */
    public function createSignature(string $merchantRef, $amount): string
    {
        return hash_hmac('sha256', $this->merchantCode.$merchantRef.$amount, $this->privateKey);
    }

    /**
     * Verifikasi callback Tripay: HMAC-SHA256(rawBody) dibandingkan X-Callback-Signature.
     */
    public function verifyCallbackSignature(string $rawBody, string $signature): bool
    {
        if ($signature === '' || $this->privateKey === '') {
            return false;
        }

        $expected = hash_hmac('sha256', $rawBody, $this->privateKey);

        return hash_equals($expected, $signature);
    }

    public function amountInRupiah(mixed $amount): ?int
    {
        if (is_int($amount)) {
            return $amount >= 0 ? $amount : null;
        }

        if (is_string($amount) && preg_match('/^\d+$/', $amount) === 1) {
            return (int) $amount;
        }

        if (is_float($amount) && floor($amount) === $amount && $amount >= 0) {
            return (int) $amount;
        }

        return null;
    }

    /**
     * Buat transaksi baru di Tripay.
     */
    public function createTransaction(array $payload): array
    {
        $response = $this->client()->post($this->baseUrl.'/transaction/create', $payload);

        if (! $response->successful() || ! $response->json('success')) {
            Log::error('Tripay create transaction failed', [
                'status' => $response->status(),
                'response' => $response->json(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => $response->json('message') ?? 'Gagal membuat transaksi Tripay.',
                'data' => $response->json(),
            ];
        }

        return [
            'success' => true,
            'data' => $response->json('data'),
        ];
    }

    /**
     * Ambil detail transaksi berdasarkan reference Tripay.
     */
    public function getTransaction(string $reference): array
    {
        $response = $this->client()->get($this->baseUrl.'/transaction/detail', [
            'reference' => $reference,
        ]);

        if (! $response->successful() || ! $response->json('success')) {
            Log::error('Tripay get transaction failed', [
                'status' => $response->status(),
                'reference' => $reference,
                'response' => $response->json(),
            ]);

            return [
                'success' => false,
                'message' => $response->json('message') ?? 'Gagal mengambil detail transaksi Tripay.',
                'data' => $response->json(),
            ];
        }

        return [
            'success' => true,
            'data' => $response->json('data'),
        ];
    }

    /**
     * Daftar channel pembayaran yang aktif untuk merchant.
     */
    public function getPaymentChannels(): array
    {
        $response = $this->client()->get($this->baseUrl.'/merchant/payment-channel');

        if (! $response->successful()) {
            return [];
        }

        return $response->json('data') ?? [];
    }

    /**
     * Konversi kode metode lokal (tripay_briva) menjadi kode channel Tripay (BRIVA).
     */
    public function mapMethodToChannel(string $paymentMethod): ?string
    {
        if (isset(self::CHANNEL_MAP[$paymentMethod])) {
            return self::CHANNEL_MAP[$paymentMethod];
        }

        if (! str_starts_with($paymentMethod, 'tripay_')) {
            return null;
        }

        return strtoupper(substr($paymentMethod, strlen('tripay_')));
    }
}
