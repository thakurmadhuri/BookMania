<?php

namespace App\Models;

use App\Models\Books;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categories extends Model
{
    use HasFactory, SoftDeletes;
    // use CascadeSoftDeletes;

    protected $cascadeDeletes = ['books'];
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'description',
    ];

    public function books():HasMany
    {
        return $this->hasMany(Books::class);
    }
}
