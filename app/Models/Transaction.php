<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $fillable = ['user_id', 'code', 'address', 'phone', 'pickup_at', 'status', 'total'];

    protected $casts = [
        'total' => 'decimal:2',
        'pickup_at' => 'datetime',
    ];

    public static function cancelOverduePendingPickup(): void
    {
        static::query()
            ->where('status', 'pending')
            ->whereNotNull('pickup_at')
            ->where('pickup_at', '<', now())
            ->update(['status' => 'cancelled']);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id');
    }
}
