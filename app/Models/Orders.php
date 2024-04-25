<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
