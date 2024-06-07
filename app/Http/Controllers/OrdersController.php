<?php

namespace App\Http\Controllers;

use App\Models\UserCart;
use Stripe\Stripe;
use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use Stripe\StripeClient;
use App\Models\OrderBook;
use Stripe\PaymentIntent;
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
            $cart = UserCart::where("user_id", $user->id)->get();
            $userCart = new UserCart();

            $address = UserAddress::where("user_id", $user->id)->first();
            if (!$address) {
                return response()->json(['message' => "address not found"], 404);
            }

            $latestOrder = Order::orderBy('created_at', 'DESC')->first();
            if ($latestOrder) {
                $id = '#ORD' . str_pad($latestOrder->id + 1, 8, "0", STR_PAD_LEFT);
            } else {
                $id = '#ORD' . str_pad(1, 8, "0", STR_PAD_LEFT);
            }

            $order = Order::create([
                'order_id' => $id,
                'total_qty' => $userCart->totalQty(),
                'total_price' => $userCart->totalPrice(),
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

            foreach ($cart as $item) {
                OrderBook::create([
                    'order_id' => $order->id,
                    'book_id' => $item->book_id,
                    'qty' => $item->qty,
                    'total_book_price' => $item->countPrice(),
                ]);
            }

            UserCart::where("user_id", $user->id)->delete();

            activity('Order Activity')->event('Created')
                ->withProperties([
                    'order_id' => $id,
                    'total_qty' => $userCart->totalQty(),
                    'total_price' => $userCart->totalPrice(),
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
                ])
                ->performedOn($order)
                ->causedBy(auth()->user())
                ->log('New Order Created');

            DB::commit();
            return response()->json(['message' => "Order placed successfully..!"], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Order placement failed: " . $e->getMessage());
            return response()->json(["message" => "Error while placing order", "error" => $e->getMessage()], 500);
        }
    }

    public function stripeOrder(Request $request)
    {
        $auth = auth()->user();

        DB::beginTransaction();
        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $user = User::where('email', $auth->email)->firstOrFail();

            $user->createOrGetStripeCustomer();
            // $paymentMethod = $user->addPaymentMethod($request->paymentMethodId);
            // $user->updateDefaultPaymentMethod($paymentMethod->id);
            $amount = (int) ($request->amount);
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'usd',
                'payment_method' => $request->paymentMethodId,
                'confirmation_method' => 'automatic',
                'confirm' => true,
                'return_url' => route('place-order'),
            ]);
            $c = $paymentIntent->confirm(
                [
                    'payment_method' => $request->paymentMethodId,
                    'return_url' => route('place-order'),
                ]
            );

            $this->placeOrder($request);

            DB::commit();
            return response()->json(['message' => "Order placed successfully..!"], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Order placement failed: " . $e->getMessage());
            return response()->json(["message" => "Error while placing order", 'error' => $e->getMessage()], 500);
        }
    }

    public function getLastOrder()
    {
        $user = auth()->user();
        $order = Order::where("user_id", $user->id)->latest()->first();

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