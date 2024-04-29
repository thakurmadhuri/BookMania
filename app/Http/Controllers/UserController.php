<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(){
        $users = User::all();
        return view("users",compact("users"));
    }

    public function profile(){
        $user = Auth::user();
        return view("profile",compact("user"));
    }
}
