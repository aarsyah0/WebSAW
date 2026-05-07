<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Product;

class Transaction extends Model
{
    protected $fillable = ['user_id', 'code', 'address', 'phone', 'pickup_at', 'stock_restored_at', 'status', 'total'];

    protected $casts = [
        'total' => 'decimal:2',
        'pickup_at' => 'datetime',
        'stock_restored_at' => 'datetime',
    ];

    public static function cancelOverduePendingPickup(): void
    {
        static::query()
            ->where('status', 'pending')
            ->whereNotNull('pickup_at')
            ->where('pickup_at', '<', now())
            ->select('id')
            ->orderBy('id')
            ->chunkById(100, function ($rows) {
                $ids = $rows->pluck('id')->all();
                $transactions = static::query()->with('details')->whereIn('id', $ids)->get();
                foreach ($transactions as $t) {
                    $t->setStatus('cancelled');
                }
            });
    }

    public function setStatus(string $newStatus): void
    {
        $oldStatus = (string) $this->status;
        if ($oldStatus === $newStatus) {
            return;
        }

        DB::transaction(function () use ($newStatus, $oldStatus) {
            $this->refresh();

            // Cancelled: restore stock once
            if ($newStatus === 'cancelled') {
                $this->restoreStockIfNeeded();
                $this->forceFill(['status' => 'cancelled'])->save();
                return;
            }

            // If reverting from cancelled -> pending/paid, reserve stock again
            if ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
                $this->reserveStockIfWasRestored();
            }

            $this->forceFill(['status' => $newStatus])->save();
        });
    }

    protected function restoreStockIfNeeded(): void
    {
        if ($this->stock_restored_at !== null) {
            return;
        }

        $details = $this->relationLoaded('details') ? $this->details : $this->details()->get();
        foreach ($details as $d) {
            Product::whereKey($d->product_id)->increment('stock', (int) $d->quantity);
        }

        $this->forceFill(['stock_restored_at' => now()])->save();
    }

    protected function reserveStockIfWasRestored(): void
    {
        if ($this->stock_restored_at === null) {
            return;
        }

        $details = $this->relationLoaded('details') ? $this->details : $this->details()->get();
        foreach ($details as $d) {
            Product::whereKey($d->product_id)->decrement('stock', (int) $d->quantity);
        }

        $this->forceFill(['stock_restored_at' => null])->save();
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
