<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Customer;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'number',
        'total_price',
        'shipping_price',
        'status',
        'notes',
    ];

    protected $casts = [
        'status' => 'boolean',
        'type' => OrderStatusEnum::class,
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

}
