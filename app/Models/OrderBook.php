<?php

namespace App\Models;

use App\Models\Book;
use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderBook extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'book_id',
        'qty',
        'total_book_price',
    ];

    public function order():BelongsTo
    {
        return $this->belongsTo(Order::class,'order_id','id');
    }

    public function book():BelongsTo
    {
        return $this->belongsTo(Book::class,'book_id','id');
    }
}
