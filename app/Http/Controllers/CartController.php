<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function myCart(Request $request)
    {
        $user = Auth::user();
        $cart = Cart::with([
            'cartDetails' => function ($query) {
                $query->join('books', 'cart_details.book_id', '=', 'books.id');
            }
        ])->where("user_id", $user->id)->get();

        return $cart;
    }

    public function index(Request $request)//my cart for web
    {
        $user = Auth::user();
        $cart = $this->myCart($request);

        return view("cart", compact("cart", 'user'));
    }

    public function removeItem(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'book_id' => 'required',
            'cart_id' => 'required',
        ]);

        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        $user = Auth::user();
        $data = $request->all();
        $de = CartDetail::where('cart_id', $data['cart_id'])->where('book_id', $data['book_id'])->first();
        if ($de !== null) {
            $de->delete();
            $count = CartDetail::where('cart_id', $data['cart_id'])->count();
            if ($count == 0) {
                Cart::where('id', $data['cart_id'])->delete();
                Session::forget('cart' . $user->id);
            }
            $cart = Session::get('cart.' . $user->id, []);
            unset($cart[$data['book_id']]);
            Session::put('cart.' . $user->id, $cart);
            return response()->json(200);
        } else {
            return response()->json('Item not found', 404);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validated = Validator::make($request->all(), [
                'book_id' => 'required',
                'quantity' => 'required',
                'total' => 'required',
            ]);

            if ($validated->fails()) {
                return redirect()->back()->withErrors($validated)->withInput();
            }

            $data = $request->all();

            $book = Book::where('id', $data['book_id'])->first();
            if (!isset($book)) {
                return response()->json(['message' => "Book not found"], 404);
            }

            $user = Auth::user();

            $productId = $data['book_id'];
            $quantity = $data['quantity'];

            $c = Cart::where('user_id', $user->id)->first();
            if ($c !== null) {
                $de = CartDetail::where('cart_id', $c->id)->where('book_id', $data['book_id'])->first();
                if (isset($de)) {

                    $c->total_price = $c->total_price - $de->total_book_price + floatval($data['total']);

                    if ($data['quantity'] == 0) {
                        $de->delete();
                    } else {
                        $de->qty = $data['quantity'];
                        $de->total_book_price = floatval($data['total']);
                        $de->save();
                    }

                } else {
                    $de = CartDetail::create([
                        "cart_id" => $c->id,
                        "book_id" => $data['book_id'],
                        "qty" => $data['quantity'],
                        "total_book_price" => $data['total'],
                    ]);

                    $c->total_price = $c->total_price + $data['total'];
                }

                $count = CartDetail::where('cart_id', $c->id)->count();
                if ($count == 0) {
                    $c->delete();
                } else {
                    $c->total_qty = $count;
                    $c->save();
                }

            } else {
                $cart = Cart::create([
                    'user_id' => $user->id,
                    'total_qty' => '1',
                    'total_price' => $data['total'],
                ]);

                $details = CartDetail::create([
                    "cart_id" => $cart->id,
                    "book_id" => $data['book_id'],
                    "qty" => $data['quantity'],
                    "total_book_price" => $data['total'],
                ]);

                $count = CartDetail::where('cart_id', $cart->id)->count();
                if ($count == 0) {
                    $cart->delete();
                } else {
                    $cart->total_qty = $count;
                    $cart->save();
                }
            }

            $cart = Session::get('cart.' . $user->id, []);

            if ($quantity <= 0) {
                unset($cart[$productId]);
            } else {
                if (isset($cart[$productId])) {
                    $cart[$productId] = $quantity;
                } else {
                    $cart[$productId] = $quantity;
                }
            }

            Session::put('cart.' . $user->id, $cart);
            DB::commit();
            return response()->json(['message' => "Item added in cart"], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to add item in cart: " . $e->getMessage());
            return response()->json(["message" => "Error while adding item in cart"], 500);
        }
    }

    public function cartCount()
    {
        $user = Auth::user();
        $c = Cart::where('user_id', $user->id)->first();
        if (isset($c)) {
            $cartLength = CartDetail::where('cart_id', $c->id)->count();
            return response()->json(['count' => $cartLength]);
        } else {
            return response()->json(['count' => 0]);
        }
    }

    public function checkout(Request $request)
    {
        $user = auth()->user();

        $states = [
            "Andhra Pradesh",
            "Arunachal Pradesh",
            "Assam",
            "Bihar",
            "Chhattisgarh",
            "Goa",
            "Gujarat",
            "Haryana",
            "Himachal Pradesh",
            "Jammu and Kashmir",
            "Jharkhand",
            "Karnataka",
            "Kerala",
            "Madhya Pradesh",
            "Maharashtra",
            "Manipur",
            "Meghalaya",
            "Mizoram",
            "Nagaland",
            "Odisha",
            "Punjab",
            "Rajasthan",
            "Sikkim",
            "Tamil Nadu",
            "Telangana",
            "Tripura",
            "Uttarakhand",
            "Uttar Pradesh",
            "West Bengal",
            "Andaman and Nicobar Islands",
            "Chandigarh",
            "Dadra and Nagar Haveli",
            "Daman and Diu",
            "Delhi",
            "Lakshadweep",
            "Puducherry"
        ];

        $cart = Cart::with([
            'cartDetails' => function ($query) {
                $query->join('books', 'cart_details.book_id', '=', 'books.id');
            }
        ])->where("user_id", $user->id)->get();

        return view("checkout", compact("cart", 'user', 'states'));
    }

    public function addAddress(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'mobile' => 'required|string|regex:/^[0-9]{10}$/',
                'address' => 'required|string',
                'pincode' => 'required|string|size:6',
                'city' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'country' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $user = auth()->user();

            $address = UserAddress::create([
                'user_id' => $user->id,
                'first_name' => $request->input('firstname'),
                'last_name' => $request->input('lastname'),
                'mobile' => $request->input('mobile'),
                'address' => $request->input('address'),
                'pincode' => $request->input('pincode'),
                'city' => $request->input('city'),
                'state' => $request->input('state'),
                'country' => $request->input('country'),
                "default_address" => false,
            ]);


            DB::commit();
            return response()->json(['message' => 'Address inserted successfully'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to add address: " . $e->getMessage());
            return response()->json(["message" => "Error while adding address"], 500);
        }

    }
}