<?php

namespace App\Models;

use App\Models\CartDetails;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'total_qty',
        'total_price',
        'user_id',
    ];

    public function cartdetails():HasMany
    {
        return $this->hasMany(CartDetails::class,'cart_id', 'id');
    }
}
