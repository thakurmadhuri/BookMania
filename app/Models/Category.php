<?php

namespace App\Models;

use App\Models\Book;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, RevisionableTrait;

    protected $cascadeDeletes = ['books'];

    protected $revisionEnabled = true;
    protected $revisionCleanup = true;
    protected $historyLimit = 500; 
    protected $revisionForceDeleteEnabled = true;

    protected $dontKeepRevisionOf = [
        'updated_at',
        'created_at'
    ];

    protected $fillable = [
        'name',
        'description',
    ];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'category_id', 'id');
    }

    public function uniqueId()
    {
        return 'name';
    }
}
