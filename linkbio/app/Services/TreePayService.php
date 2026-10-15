<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TreePayService
{
    protected string $merchantId;
    protected string $apiKey;
    protected string $privateKey;
    protected ?string $secretKey;
    protected string $baseUrl;
    protected int $timeout;
    protected string $mode;

    public function __construct()
    {
        $this->merchantId = config('treepay.merchant_id', '');
        $this->apiKey = config('treepay.api_key', '');
        $this->privateKey = config('treepay.private_key', '');
        $this->secretKey = config('treepay.secret_key');
        $this->baseUrl = rtrim(config('treepay.base_url', 'https://sandbox.treepay.co.id/api/v1'), '/');
        $this->timeout = (int) config('treepay.timeout', 30);
        $this->mode = config('treepay.mode', 'sandbox');
    }

    protected function client(): PendingRequest
    {
        return Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Merchant-Id' => $this->merchantId,
            'Api-Key' => $this->apiKey,
        ])->timeout($this->timeout);
    }

    public function generateSignature(array $data): string
    {
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return hash_hmac('sha256', $json, $this->privateKey);
    }

    public function createTransaction(array $payload): array
    {
        $url = $this->baseUrl.'/transaction/create';

        $response = $this->client()->post($url, $payload);

        if (! $response->successful()) {
            Log::error('TreePay create transaction failed', [
                'status' => $response->status(),
                'response' => $response->json(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => $response->json('message') ?? 'Failed to create transaction',
                'data' => $response->json(),
            ];
        }

        $data = $response->json();

        return [
            'success' => true,
            'data' => $data,
        ];
    }

    public function getTransaction(string $reference): array
    {
        $url = $this->baseUrl.'/transaction/'.$reference;

        $response = $this->client()->get($url);

        if (! $response->successful()) {
            Log::error('TreePay get transaction failed', [
                'status' => $response->status(),
                'reference' => $reference,
                'response' => $response->json(),
            ]);

            return [
                'success' => false,
                'message' => $response->json('message') ?? 'Failed to get transaction',
                'data' => $response->json(),
            ];
        }

        return [
            'success' => true,
            'data' => $response->json(),
        ];
    }

    public function verifyCallback(array $data): bool
    {
        $signature = $data['signature'] ?? '';
        $dataWithoutSignature = $data;
        unset($dataWithoutSignature['signature']);
        $expectedSignature = $this->generateSignature($dataWithoutSignature);
        return hash_equals($expectedSignature, $signature);
    }
}
