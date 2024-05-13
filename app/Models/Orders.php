<?php

namespace App\Models;

use App\Models\User;
use App\Models\OrderBooks;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Orders extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'total_qty',
        'total_price',
        'payment_method',
        'user_id',
        'first_name',
        'last_name',
        'address',
        'pincode',
        'mobile',
        'city',
        'state',
        'country',
    ];

    public function books():HasMany
    {
        return $this->hasMany(OrderBooks::class,'order_id','id');
    }


    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
