<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view("users", compact("users"));
    }

    public function profile()
    {
        $user = Auth::user();
        return $user;
    }

    public function edit()
    {
        $user = $this->profile();
        return view("profile", compact("user"));
    }

    public function delete(Request $request,$id){
        $user = User::find($id);
        if (!$user) {
            // return response()->json(['message' => 'User not found'], 404);
            return redirect("users")->with("error", "User not found..!");
        }
        $user->delete();
        return redirect("users")->with("success", "Deleted successfully..!");
    }
}

