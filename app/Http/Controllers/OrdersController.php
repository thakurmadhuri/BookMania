<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Orders;
use App\Models\OrderBooks;
use App\Models\UserAddresses;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class OrdersController extends Controller
{

    public function allOrders()
    {
        $orders = Orders::orderBy("created_at", "desc")->paginate(20);

        return view("all-orders", compact("orders"));
    }

    public function placeOrder(Request $request)
    {
        $user = auth()->user();

        $cart = Cart::with([
            'cartdetails' => function ($query) {
                $query->join('books', 'cart_details.books_id', '=', 'books.id');
                // ->$query->selectRaw('SUM(books.price * cart_details.qty) as total_price');
            },
        ])->where("user_id", $user->id)->first();

        $address = UserAddresses::where("user_id", $user->id)->first();

        $latestOrder = Orders::orderBy('created_at', 'DESC')->first();
        if ($latestOrder) {
            $id = '#ORD' . str_pad($latestOrder->id + 1, 8, "0", STR_PAD_LEFT);
        } else {
            $id = '#ORD' . str_pad(1, 8, "0", STR_PAD_LEFT);
        }

        $order = Orders::create([
            'order_id' => $id,
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

        $total = 0;
        foreach ($cart->cartdetails as $cartdetail) {
            $sub_total = $cartdetail->qty * $cartdetail->price;
            // $sub_total = floatval(str_replace(',', '', $sub_total));
            $total = $total + $sub_total;
            // dd($sub_total);

            OrderBooks::create([
                'order_id' => $order->id,
                'books_id' => $cartdetail->books_id,
                'qty' => $cartdetail->qty,
                'total_book_price' => $sub_total,
            ]);
        }

        $order->total_price = $total;
        $order->save();

        $cart->delete();

        Session::forget('cart');

        if ($order) {
            return response()->json(200);
        }

    }

    public function completeOrder(Request $request)
    {
        $user = auth()->user();
        $order = Orders::with([
            'books' => function ($query) {
                $query->join('books', 'order_books.books_id', '=', 'books.id');
            }
        ])->where("user_id", $user->id)->latest()->first();

        return view("complete-order", compact("order"));
    }

    public function myOrders()
    {
        $user = auth()->user();
        $orders = Orders::with([
            'books' => function ($query) {
                $query->join('books', 'order_books.books_id', '=', 'books.id');
            }
        ])->where("user_id", $user->id)->orderBy('created_at', 'desc')->get();

        return view("my-orders", compact("orders"));
    }

    public function viewOrder($id)
    {
        $order = Orders::with(
            'books'
        )->where("id", $id)->first();
        return view("view-order", compact("order"));
    }
}