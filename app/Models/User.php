<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'remember_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function orderDetails()
    {
        return $this->hasManyThrough(
            \App\Models\OrderDetail::class,
            \App\Models\Order::class,
            'user_id',       // Foreign key on orders table
            'order_id',      // Foreign key on order_details table
            'id',            // Local key on users table
            'id'             // Local key on orders table
        );
    }

    public function userActivity()
    {
        return $this->hasMany(UserActivity::class);
    }

    public function scopeCommonSearch ($query, $search)
    {
        return $query->when($search, function($q) use ($search) {
            $q->where(function($query) use ($search) {
                $query->where('username', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            });
        });
    }

    public function scopeWithOrderStats($query)
    {
        return $query->withSum('orders as total_spent', 'total_amount');
    }

    public function scopeWithOrderQuantityStats($query)
    {
        return $query->withSum('orderDetails as total_quantity', 'quantity');
    }
    
    public function scopeSortByName($query, $direction = 'asc')
    {
        return $query->orderBy('username', $direction);
    }
}
