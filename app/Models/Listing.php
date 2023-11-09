<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Listing extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'beds', 'baths', 'area', 'city', 'code', 'street', 'street_nr', 'price'
    ];

    protected $sortable = [
        'price', 'created_at'       // the listings can only be sorted by 'price' and 'created_at'
    ];

    public function owner(): BelongsTo {
        return $this->belongsTo(
            \App\Models\User::class,
            'by_user_id'
        );
    }

    public function images(): HasMany {
        return $this->hasMany(ListingImage::class);
    }

    public function offers(): HasMany {
        return $this->hasMany(Offer::class, 'listing_id');
    }

    public function scopeMostRecent(Builder $query): Builder {
        return $query->orderByDesc('created_at');       // sort the listing based on the created date time
    }

    public function scopeWithoutSold(Builder $query): Builder {
        // return $query->doesntHave('offers')        // retrieve all the listings that dont have any offer
        //     ->orWhereHas(                           // or it has offers but both 'accepted_at' and 'rejected_at' column are null
        //         'offers',
        //         fn(Builder $query) => $query->whereNull('accepted_at')
        //                                     ->whereNull('rejected_at'));
        return $query->whereNull('sold_at');        // retrieve all the listings that are not sold
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
        )->when(
            $filters['deleted'] ?? false,
            fn ($query, $value) => $query->withTrashed()    // for retrieving the softdeleted listings
        )->when(
            $filters['by'] ?? false,
            fn($query, $value) => 
            !in_array($value, $this->sortable) ? 
                $query :        // if the $value is not within the $sortable array
                $query->orderBy($value, $filters['order'] ?? 'desc')       // else
        );
    }
}
