<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserAddress extends Model
{
    use HasFactory, RevisionableTrait;

    protected $revisionEnabled = true;
    protected $revisionCleanup = true;
    protected $historyLimit = 500; 
    protected $revisionForceDeleteEnabled = true;

    protected $dontKeepRevisionOf = [
        'updated_at',
        'created_at'
    ];

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'address',
        'pincode',
        'default_address',
        'mobile',
        'city',
        'state',
        'country',
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
