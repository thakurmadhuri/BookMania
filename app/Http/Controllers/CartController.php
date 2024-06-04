<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\UserAddress;
use App\Models\UserCart;
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
        $cart = UserCart::where("user_id", $user->id)->get();
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
            // 'book_id' => 'required',
            'cart_id' => 'required',
        ]);

        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        $user = Auth::user();
        $data = $request->all();
        $de = UserCart::where('id', $data['cart_id'])->first();

        if ($de !== null) {
            $de->delete();
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

            $cart = UserCart::where('user_id', $user->id)->where('book_id', $data['book_id'])->first();
            if ($cart !== null) {
                if ($data['quantity'] == 0) {
                    $cart->delete();
                } else {
                    $cart->qty = $data['quantity'];
                    $cart->save();
                }
            } else {
                UserCart::create([
                    "user_id" => $user->id,
                    "book_id" => $data['book_id'],
                    "qty" => $data['quantity'],
                ]);
            }

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
        $c = UserCart::where('user_id', $user->id)->count();
        return response()->json(['count' => $c]);
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

        $cart = UserCart::where("user_id", $user->id)->get();
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