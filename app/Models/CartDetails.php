<?php

namespace App\Models;

use App\Models\Cart;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\SoftDeletes;
 
class CartDetails extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "cart_id",
        "book_id",
        "qty",
        "total_book_price",
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
