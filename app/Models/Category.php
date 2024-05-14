<?php

namespace App\Models;

use App\Models\Book;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'name',
        'description',
    ];

    public function books():HasMany
    {
        return $this->hasMany(Book::class,'category_id','id');
    }
}