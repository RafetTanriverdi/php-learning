<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'stock',
        'description',
        'category_id',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (! $search) {
            return $query;
        }

        return $query->where(
            'name',
            'like',
            '%'.$search.'%'
        );
    }

    public function scopeMinPrice(Builder $query, $minPrice): Builder
    {
        if ($minPrice === null) {
            return $query;
        }

        return $query->where('price', '>=', $minPrice);
    }

    public function scopeMaxPrice(Builder $query, $maxPrice): Builder
    {
        if ($maxPrice === null) {
            return $query;
        }

        return $query->where('price', '<=', $maxPrice);
    }

    public function scopeInStock(Builder $query, bool $inStock): Builder
    {
        if (! $inStock) {
            return $query;
        }

        return $query->where('stock', '>', 0);
    }
}
