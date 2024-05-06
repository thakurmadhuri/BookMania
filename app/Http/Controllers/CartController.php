<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartDetails;
use App\Models\UserAddresses;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cart = Cart::with([
            'cartdetails' => function ($query) {
                $query->join('books', 'cart_details.books_id', '=', 'books.id');
            }
        ])->where("user_id", $user->id)->get();

        return view("cart", compact("cart", 'user'));
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'books_id' => 'required',
            'quantity' => 'required',
            'total' => 'required',
            'author' => 'required',
            'category_id' => 'required'
        ]);

        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        $user = Auth::user();
        $data = $request->all();

        $c = Cart::where('user_id', $user->id)->first();
        if ($c !== null) {
            $de = CartDetails::where('cart_id', $c->id)->where('books_id', $data['books_id'])->first();

            if (isset($de)) {

                $c->total_qty = $c->total_qty - $de->qty + $data['quantity'];
                $c->total_price = $c->total_price - $de->total_book_price + $data['total'];
                $c->save();

                if($data['quantity']==0){
                    $de->delete();
                }
                else{
                    $de->qty = $data['quantity'];
                    $de->total_book_price = $data['total'];
                    $de->save();
                }
                
            } else {
                $de = CartDetails::create([
                    "cart_id" => $c->id,
                    "books_id" => $data['books_id'],
                    "qty" => $data['quantity'],
                    "total_book_price" => $data['total'],
                ]);

                $c->total_qty = $c->total_qty + $data['quantity'];
                $c->total_price = $c->total_price + $data['total'];
                $c->save();
            }

        } else {
            $cart = Cart::create([
                'user_id' => $user->id,
                'total_qty' => $data['quantity'],
                'total_price' => $data['total'],
            ]);

            $details = CartDetails::create([
                "cart_id" => $cart->id,
                "books_id" => $data['books_id'],
                "qty" => $data['quantity'],
                "total_book_price" => $data['total'],
            ]);

            
        }

        $cart = Session::get('cart', []);

        $productId = $data['books_id'];
        $quantity = $data['quantity'];
        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            if (isset($cart[$productId])) {
                $cart[$productId] += $quantity;
            } else {
                $cart[$productId] = $quantity;
            }
        }

        Session::put('cart', $cart);

        return response()->json(200);
    }

    public function cartCount()
    {
        $cart = Session::get('cart', []);
        $cartLength = count($cart);
        return response()->json(['count' => $cartLength]);
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
            'cartdetails' => function ($query) {
                $query->join('books', 'cart_details.books_id', '=', 'books.id');
            }
        ])->where("user_id", $user->id)->get();

        return view("checkout", compact("cart", 'user', 'states'));
    }

    public function addAddress(Request $request)
    {
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

        $address = UserAddresses::create([
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

        if ($address) {
            return response()->json(200);
        } else {
            return response()->json(500);
        }

    }
}
