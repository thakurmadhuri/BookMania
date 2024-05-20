<?php

namespace App\Models;

use App\Models\Category;
use App\Models\CartDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory, SoftDeletes,CascadeSoftDeletes;

    protected $cascadeDeletes = ['cartDetail'];
    protected $fillable = [
        'name',
        'description',
        'price',
        'author',
        'image',
        'category_id'
    ];

    public function category():BelongsTo
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }

    public function cartDetail():HasMany
    {
        return $this->hasMany(CartDetail::class,'book_id','id');
    }
}