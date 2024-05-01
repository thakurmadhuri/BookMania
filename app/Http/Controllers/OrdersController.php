<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Orders;
use App\Models\OrderBooks;
use App\Models\UserDetails;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrdersController extends Controller
{
    public function placeOrder(Request $request)
    {
        $user = auth()->user();

        $cart = Cart::with([
            'cartdetails' => function ($query) {
                $query->join('books', 'cart_details.book_id', '=', 'books.id');
            }
        ])->where("user_id", $user->id)->first();

        $address = UserDetails::where("user_id", $user->id)->first();

        $order = Orders::create([
            'order_id' => 'ORD-' . Str::uuid(),
            'total_qty' => $cart->total_qty,
            'total_price' => $cart->total_price,
            'payment_method' => 'CASH',
            'user_id' => $user->id,
            'first_name' => $address->first_name,
            'last_name' => $address->last_name,
            'address' => $address->address,
            'pincode' => $address->pincode,
            'mobile' => $address->mobile,
            'city' => $address->city,
            'state' => $address->state,
            'country' => $address->country,
        ]);

        foreach ($cart->cartdetails as $cartdetail) {
            OrderBooks::create([
                'order_id' => $order->id,
                'book_id' => $cartdetail->book_id,
                'qty' => $cartdetail->qty,
                'total_book_price'=> $cartdetail->total_book_price,
            ]);
        }

        $cart->delete();
        
        if ($order) {
            return response()->json(200);
        }

    }

    public function completeOrder(Request $request){
        $user = auth()->user();
        $order = Orders::with([
            'books' => function ($query) {
                $query->join('books', 'order_books.book_id', '=', 'books.id');
            }
        ])->where("user_id", $user->id)->latest()->first();

        return view("complete-order", compact("order"));
    }
}
