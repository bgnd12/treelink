<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Order extends Model
{
    use HasFactory;

    public const STATUSES = [
        'awaiting_payment' => 'Menunggu Pembayaran',
        'awaiting_confirmation' => 'Menunggu Konfirmasi',
        'processing' => 'Diproses',
        'shipped' => 'Dikirim',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];

    /**
     * Statuses that count as an active (not finished) order for a buyer.
     */
    public const ACTIVE_STATUSES = ['awaiting_payment', 'awaiting_confirmation', 'processing', 'shipped'];

    /**
     * Statuses that mean the order has been finalised.
     */
    public const FINAL_STATUSES = ['completed', 'cancelled'];

    /**
     * Legacy status keys that may still exist on older rows, mapped to the
     * current status so the UI never shows an unknown value.
     */
    public const LEGACY_STATUS_MAP = [
        'pending' => 'awaiting_payment',
    ];

    public const PAYMENT_STATUSES = [
        'unpaid' => 'Belum Dibayar',
        'paid' => 'Sudah Dibayar',
    ];

    public const PAYMENT_METHODS = [
        'transfer' => 'Transfer Bank (Manual)',
        'ewallet' => 'E-Wallet (Manual)',
        'cod' => 'Bayar di Tempat (COD)',
        'treepay_qris' => 'QRIS (TreePay)',
        'treepay_va' => 'Virtual Account (TreePay)',
        'treepay_ewallet' => 'E-Wallet (TreePay)',
        'treepay_cc' => 'Kartu Kredit/Debit (TreePay)',
    ];

    protected $fillable = [
        'seller_id',
        'buyer_id',
        'buyer_name',
        'buyer_contact',
        'note',
        'total',
        'status',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_province',
        'shipping_postal_code',
        'payment_method',
        'payment_gateway',
        'payment_reference',
        'payment_external_id',
        'payment_url',
        'payment_qr_url',
        'payment_va_number',
        'payment_merchant_id',
        'payment_status',
        'payment_status_raw',
        'payment_proof',
        'payment_metadata',
        'tracking_number',
        'paid_at',
    ];

    protected $appends = [
        'payment_proof_url',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
            'paid_at' => 'datetime',
            'payment_metadata' => 'array',
        ];
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        $status = self::LEGACY_STATUS_MAP[$this->status] ?? $this->status;

        return self::STATUSES[$status] ?? ucfirst((string) $status);
    }

    /**
     * Current status with legacy keys normalised to the current status set.
     */
    public function normalizedStatus(): string
    {
        return self::LEGACY_STATUS_MAP[$this->status] ?? $this->status;
    }

    public function getStatusCodeAttribute(): string
    {
        return $this->normalizedStatus();
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return self::PAYMENT_STATUSES[$this->payment_status] ?? ucfirst((string) $this->payment_status);
    }

    public function getPaymentMethodLabelAttribute(): ?string
    {
        if (! $this->payment_method) {
            return null;
        }

        return self::PAYMENT_METHODS[$this->payment_method] ?? ucfirst((string) $this->payment_method);
    }

    public function getTotalLabelAttribute(): string
    {
        return 'Rp '.number_format((float) $this->total, 0, ',', '.');
    }

    public function getCodeAttribute(): string
    {
        return 'TL-'.str_pad((string) $this->id, 6, '0', STR_PAD_LEFT);
    }

    public function getPaymentProofUrlAttribute(): ?string
    {
        if (! $this->payment_proof) {
            return null;
        }

        return url('storage/'.$this->payment_proof);
    }

    public function getShippingSummaryAttribute(): string
    {
        $parts = array_filter([
            $this->shipping_address,
            $this->shipping_city,
            $this->shipping_province,
            $this->shipping_postal_code,
        ], fn ($value) => filled($value));

        return implode(', ', $parts);
    }

    public function getIsPaidAttribute(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function proofStorageDisk(): string
    {
        return config('filesystems.default');
    }

    public function deletePaymentProof(): void
    {
        if ($this->payment_proof) {
            Storage::disk($this->proofStorageDisk())->delete($this->payment_proof);
        }
    }
}
