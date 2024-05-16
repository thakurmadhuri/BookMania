<?php

namespace App\Observers;

use App\Models\CartDetail;

class CartDetailObserver
{
    public function creating(CartDetail $cartDetail){
        $cartDetail->total_book_price = $cartDetail->countPrice();
    }

    public function created(CartDetail $cartDetail): void
    {
        //
    }

    public function updating(CartDetail $cartDetail)
    {
        $cartDetail->total_book_price = $cartDetail->countPrice();
    }

    /**
     * Handle the CartDetail "updated" event.
     */
    public function updated(CartDetail $cartDetail): void
    {
        //
    }

    /**
     * Handle the CartDetail "deleted" event.
     */
    public function deleted(CartDetail $cartDetail): void
    {
        //
    }

    /**
     * Handle the CartDetail "restored" event.
     */
    public function restored(CartDetail $cartDetail): void
    {
        //
    }

    /**
     * Handle the CartDetail "force deleted" event.
     */
    public function forceDeleted(CartDetail $cartDetail): void
    {
        //
    }
}
