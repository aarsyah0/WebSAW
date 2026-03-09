<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price', 'stock', 'age_range', 'category', 'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function criterias(): BelongsToMany
    {
        return $this->belongsToMany(Criteria::class, 'product_criterias')
            ->withPivot('value')
            ->withTimestamps();
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }
}
