<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'beds', 'baths', 'area', 'city', 'code', 'street', 'street_nr', 'price'
    ];

    public function owner(): BelongsTo {
        return $this->belongsTo(
            \App\Models\User::class,
            'by_user_id'
        );
    }

    public function scopeMostRecent($query): Builder {
        return $query->orderByDesc('created_at');       // sort the listing based on the created date time
    }

    public function scopeFilter(Builder $query, array $filters): Builder {
        return $query->when(
            $filters['priceFrom'] ?? false,             // filter priceFrom
            fn ($query, $value) => $query->where('price', '>=', $value)
        )->when(
            $filters['priceTo'] ?? false,               // filter priceTo
            fn ($query, $value) => $query->where('price', '<=', $value)
        )->when(
            $filters['beds'] ?? false,                  // filter beds
            fn ($query, $value) => $query->where('beds', (int)$value < 6 ? '=' : '>=', $value)      // if $value > 6, operator will be >=
        )->when(
            $filters['baths'] ?? false,                 // filter baths
            fn ($query, $value) => $query->where('baths', (int)$value < 6 ? '=' : '>=', $value)      // if $value > 6, operator will be >=
        )->when(
            $filters['areaFrom'] ?? false,              // filter areaFrom
            fn ($query, $value) => $query->where('area', '>=', $value)
        )->when(
            $filters['areaTo'] ?? false,                // filter areaTo
            fn ($query, $value) => $query->where('area', '<=', $value)
        );
    }
}
