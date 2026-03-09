<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Criteria extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'weight_order'];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_criterias')
            ->withPivot('value')
            ->withTimestamps();
    }
}
