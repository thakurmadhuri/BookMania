<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartDetails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index(){
        $user=Auth::user();
        $cart = Cart::where("user_id",$user->id)->get();

        return view("cart",compact("cart",'user'));
    }

    public function store(Request $request){
        $user=Auth::user();
        $data= $request->all();

        $c=Cart::where('user_id',$user->id)->first();
        if($c !== null){
            $de=CartDetails::where('cart_id',$c->id)->where('book_id',$data['book_id'])->first();
            dd($de);

        }
        else{
            $cart = Cart::create([
                'user_id'=>$user->id,
                'total_qty'=>$data['quantity'],
                'total_price'=>$data['total'],
            ]);
    
            $details = CartDetails::create([
                "cart_id"=>$cart->id,
                "book_id"=>$data['book_id'],
                "qty"=>$data['quantity'],
                "total_book_price"=>$data['total'],
            ]);
        }

        return response()->json(200);
    }
}
