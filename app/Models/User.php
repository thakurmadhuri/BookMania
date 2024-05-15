<?php

namespace App\Models;

use App\Models\Order;
use App\Models\UserAddress;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles, CascadeSoftDeletes;

    protected $cascadeDeletes = ['orders','carts','addresses'];


    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function orders():HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function carts():HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function addresses():HasMany
    {
        return $this->hasMany(UserAddress::class);
    }
}
