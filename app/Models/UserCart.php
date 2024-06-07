<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserCart extends Model
{
    use HasFactory, SoftDeletes, RevisionableTrait;

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
        'book_id',
        'qty',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book():BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function countPrice(){
        return $this->book->price * $this->qty;
    }

    public  function totalQty(){
        $user=Auth::user();
        if ($user) {
            return $this->where('user_id', $user->id)->count();
        }
        return 0;
    }

    public function totalPrice()
    {
        $user = Auth::user();

        if ($user) {
            $cartItems = $this->where('user_id', $user->id)->get();
            $total = 0;

            foreach ($cartItems as $item) {
                $total += $item->countPrice();
            }

            return $total;
        }

        return 0;
    }
}
