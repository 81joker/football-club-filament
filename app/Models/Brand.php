<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use HasFactory;

    protected $table = 'brands';
    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
        'status',
    ];
    protected $casts = [
        'status' => 'boolean',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // public function getStatusLabelAttribute()
    // {
    //     return $this->status ? 'Active' : 'Inactive';
    // }
    // public function getStatusClassAttribute()
    // {
    //     return $this->status ? 'badge badge-success' : 'badge badge-danger';
    // }
    // public function getStatusTextAttribute()
    // {
    //     return $this->status ? 'Active' : 'Inactive';
    // }
}
