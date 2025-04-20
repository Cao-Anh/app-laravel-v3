<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'price',
        'quantity',
        'description'
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
    // public function orders()
    // {
    //     return $this->belongsToMany(Order::class, 'order_details')
    //         ->withPivot('quantity', 'notes')
    //         ->withTimestamps();
    // }

    public function scopeCommonSearch($query,$search,$minPrice,$maxPrice,$startDate,$endDate)
    {
        return $query->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        })
        ->when($minPrice, function ($query, $minPrice) {
            $query->where('price', '>=', $minPrice);
        })
        ->when($maxPrice, function ($query, $maxPrice) {
            $query->where('price', '<=', $maxPrice);
        })
        ->when($startDate, function ($query, $startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        })
        ->when($endDate, function ($query, $endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        });
    }
}
