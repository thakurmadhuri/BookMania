<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderBooks extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'book_id',
        'qty',
        'total_book_price',
    ];
}
