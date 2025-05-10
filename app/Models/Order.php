<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'customer_id', 'number', 'total_price', 'status', 'shipping_price', 'notes'

    ];

    protected $casts = [
        // 'status' => 'boolean',
        'status' => OrderStatusEnum::class,
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // public function products(): BelongsToMany
    // {
    //     return $this->belongsToMany(Product::class, 'order_items')
    //         ->using(OrderItem::class)
    //         ->withPivot(['quantity', 'unit_price'])
    //         ->withTimestamps();
    // }
}
