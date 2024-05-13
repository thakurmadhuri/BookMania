<?php

namespace App\Models;

use App\Models\Books;
use App\Models\Orders;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderBooks extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'books_id',
        'qty',
        'total_book_price',
    ];

    public function order():BelongsTo
    {
        return $this->belongsTo(Orders::class,'order_id','id');
    }

    public function book():BelongsTo
    {
        return $this->belongsTo(Books::class,'books_id','id');
    }
}
