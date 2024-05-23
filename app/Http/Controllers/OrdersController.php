<?php

namespace App\Http\Controllers;

use Stripe\Charge;
use Stripe\Stripe;
use App\Models\Cart;
use Stripe\Customer;
use App\Models\Order;
use App\Models\OrderBook;
use App\Models\UserAddress;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class OrdersController extends Controller
{

    public function allOrders()
    {
        $orders = Order::orderBy("created_at", "desc")->paginate(20);

        return view("all-orders", compact("orders"));
    }

    public function placeOrder(Request $request)
    {
        $user = auth()->user();

        DB::beginTransaction();
        try {
            $cart = Cart::with([
                'cartDetails' => function ($query) {
                    $query->join('books', 'cart_details.book_id', '=', 'books.id');
                },
            ])->where("user_id", $user->id)->first();

            $address = UserAddress::where("user_id", $user->id)->first();

            $latestOrder = Order::orderBy('created_at', 'DESC')->first();
            if ($latestOrder) {
                $id = '#ORD' . str_pad($latestOrder->id + 1, 8, "0", STR_PAD_LEFT);
            } else {
                $id = '#ORD' . str_pad(1, 8, "0", STR_PAD_LEFT);
            }

            $order = Order::create([
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
            foreach ($cart->cartDetails as $cartdetail) {
                $sub_total = $cartdetail->qty * $cartdetail->price;
                $total = $total + $sub_total;

                OrderBook::create([
                    'order_id' => $order->id,
                    'book_id' => $cartdetail->book_id,
                    'qty' => $cartdetail->qty,
                    'total_book_price' => $sub_total,
                ]);

                $cartSession = Session::get('cart.' . $user->id, []);
                unset($cartSession[$cartdetail->book_id]);
                Session::put('cart.' . $user->id, $cartSession);
            }

            $order->total_price = $total;
            $order->save();

            $cart->delete();

            Session::forget('cart' . $user->id);

            DB::commit();

            return response()->json(['message' => "Order placed successfully..!"], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Order placement failed: " . $e->getMessage());
            return response()->json(["message" => "Error while placing order"], 500);
        }
    }

    public function stripeOrder(Request $request)
    {
        $user = auth()->user();

        DB::beginTransaction();
        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $amount = $request->input('amount') * 100;
            $email = $request->input('email');

            $customer = Customer::create([
                'email' => $email,
                'source' => $request->input('stripeToken'),
            ]);

            $charge = Charge::create([
                'customer' => $customer->id,
                'amount' => $amount,
                'currency' => 'usd',
            ]);

            DB::commit();
            return response()->json(['message' => "Order placed successfully..!"], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Order placement failed: " . $e->getMessage());
            return response()->json(["message" => "Error while placing order"], 500);
        }
    }

    public function getLastOrder()
    {
        $user = auth()->user();
        $order = Order::with(
            'books'
        )->where("user_id", $user->id)->latest()->first();

        return $order;
    }

    public function completeOrder(Request $request)
    {
        $order = $this->getLastOrder();
        return view("complete-order", compact("order"));
    }

    public function getMyOrders()
    {
        $user = auth()->user();
        $orders = Order::with('books')->where("user_id", $user->id)->orderBy('created_at', 'desc')->get();

        return $orders;
    }

    public function myOrders()
    {
        $orders = $this->getMyOrders();

        return view("my-orders", compact("orders"));
    }

    public function viewOrder($id)
    {
        $order = Order::with(
            'books'
        )->where("id", $id)->first();
        return view("view-order", compact("order"));
    }
}