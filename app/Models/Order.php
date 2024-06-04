<?php

namespace App\Models;

use App\Models\User;
use App\Models\OrderBook;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
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
    
    protected $cascadeDeletes = ['books'];

    protected $fillable = [
        'order_id',
        'total_qty',
        'total_price',
        'payment_method',
        'user_id',
        'first_name',
        'last_name',
        'address',
        'pincode',
        'mobile',
        'city',
        'state',
        'country',
    ];

    public function books():HasMany
    {
        return $this->hasMany(OrderBook::class,'order_id','id');
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}