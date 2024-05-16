<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\Book;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\Relations\BelongsTo;
 use Illuminate\Database\Eloquent\SoftDeletes;
 
class CartDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "cart_id",
        "book_id",
        "qty",
        "total_book_price",
    ];

    public function cart():BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function book():BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function countPrice(){
        return $this->book->price * $this->qty;
    }
}
