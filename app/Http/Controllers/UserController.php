<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $user->load('addresses');
        return $user;
    }

    public function edit()
    {
        $user = $this->profile();
        return view("profile", compact("user"));
    }

    public function delete(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = User::find($id);
            if (!$user) {
                return redirect("users")->with("error", "User not found..!");
            }
            $user->delete();
            DB::commit();
            return redirect("users")->with("success", "Deleted successfully..!");
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

