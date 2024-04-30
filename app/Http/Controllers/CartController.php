<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartDetails;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cart = Cart::with([
            'cartdetails' => function ($query) {
                $query->join('books', 'cart_details.book_id', '=', 'books.id');
            }
        ])->where("user_id", $user->id)->get();

        return view("cart", compact("cart", 'user'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();

        $c = Cart::where('user_id', $user->id)->first();
        if ($c !== null) {
            $de = CartDetails::where('cart_id', $c->id)->where('book_id', $data['book_id'])->first();

            $c->total_qty = $c->total_qty - $de->qty + $data['quantity'];
            $c->total_price = $c->total_price - $de->total_book_price + $data['total'];
            $c->save();

            $de->qty = $data['quantity'];
            $de->total_book_price = $data['total'];
            $de->save();

        } else {
            $cart = Cart::create([
                'user_id' => $user->id,
                'total_qty' => $data['quantity'],
                'total_price' => $data['total'],
            ]);

            $details = CartDetails::create([
                "cart_id" => $cart->id,
                "book_id" => $data['book_id'],
                "qty" => $data['quantity'],
                "total_book_price" => $data['total'],
            ]);
        }

        return response()->json(200);
    }

    public function checkout(Request $request){
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
                $query->join('books', 'cart_details.book_id', '=', 'books.id');
            }
        ])->where("user_id", $user->id)->get();

        return view("checkout", compact("cart", 'user','states'));
    }

    public function addAddress(Request $request){
        $user = auth()->user();

        $address=UserDetails::create([
            'user_id'=> $user->id,
            'first_name'=> $request->input('firstname'),
            'last_name'=> $request->input('lastname'),
            'mobile'=> $request->input('mobile'),
            'address'=> $request->input('address'),
            'pincode'=> $request->input('pincode'),
            'city'=> $request->input('city'),
            'state'=> $request->input('state'),
            'country'=> $request->input('country'),
            "default_address"=> false,
        ]);

        if($address){
            return response()->json(200);
        }
        else{
            return response()->json(500);
        }

    }
}
