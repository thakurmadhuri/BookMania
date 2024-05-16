<?php

namespace App\Models;

use App\Models\CartDetails;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $cascadeDeletes = ['cartDetails'];

    protected $fillable = [
        'total_qty',
        'total_price',
        'user_id',
    ];

    public function cartDetails():HasMany
    {
        return $this->hasMany(CartDetail::class,'cart_id','id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}
