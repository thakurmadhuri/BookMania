<?php

namespace App\Models;

use App\Models\Cart;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\Relations\BelongsTo;
 use Illuminate\Database\Eloquent\SoftDeletes;
 
class CartDetails extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "carts_id",
        "books_id",
        "qty",
        "total_book_price",
    ];

    public function cart():BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }
}
