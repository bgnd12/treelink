<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdrawal extends Model
{
    use HasFactory;

    public const STATUSES = [
        'pending' => 'Menunggu diproses',
        'processing' => 'Sedang diproses',
        'paid' => 'Sudah dikirim',
        'rejected' => 'Ditolak',
    ];

    protected $fillable = [
        'user_id',
        'bank_account_id',
        'amount',
        'status',
        'note',
        'processed_at',
        'proof_path',
    ];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2', 'processed_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst((string) $this->status);
    }

    public function getTransferReferenceAttribute(): string
    {
        $date = $this->processed_at ?? $this->created_at;

        return 'SIM-TRF-'.($date?->format('Ymd') ?? now()->format('Ymd')).'-'.str_pad((string) $this->id, 6, '0', STR_PAD_LEFT);
    }

    public function getProofUrlAttribute(): ?string
    {
        return $this->proof_path ? asset('storage/'.$this->proof_path) : null;
    }
}
