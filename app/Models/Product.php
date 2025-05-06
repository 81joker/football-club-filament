<?php

namespace App\Models;

use App\Enums\ProductTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'brand_id',
        'name',
        'slug',
        'image',
        'description',
        'sku',
        'quantity',
        'price',
        'is_visible',
        'is_featured',
        'type',
        'published_at',
    ];
    protected $casts = [
        'status' => 'boolean',
        'type' => ProductTypeEnum::class,
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function categories():BelongsToMany
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }
    public function brand():BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
    // public function getStatusLabelAttribute()
    // {
    //     return $this->status ? 'Active' : 'Inactive';
    // }
}
