<?php

namespace App\Models;

use App\Models\Category;
use App\Models\UserCart;
use App\Models\OrderBook;
use App\Models\CartDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, RevisionableTrait;

    protected $revisionEnabled = true;
    protected $revisionCleanup = true;
    protected $historyLimit = 500; 
    protected $revisionForceDeleteEnabled = true;

    protected $dontKeepRevisionOf = [
        'updated_at',
        'created_at'
    ];

    protected $cascadeDeletes = ['userCart', 'orderBook'];
    protected $fillable = [
        'name',
        'description',
        'price',
        'author',
        'image',
        'category_id'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function userCart(): HasMany
    {
        return $this->hasMany(UserCart::class, 'book_id', 'id');
    }

    public function orderBook(): HasMany
    {
        return $this->hasMany(OrderBook::class, 'book_id', 'id');
    }

    public function uniqueIds(): array
    {
        return ['name', 'category_id'];
    }
}