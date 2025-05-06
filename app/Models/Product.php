<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\ProductTypeEnum;

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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
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
