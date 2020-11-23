<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    
    public function login(Request $request) {

        $creds = $request->only(['email', 'password']);

        if(!$token = auth()->attempt($creds)) {
            return response()->json(['message' => 'Invalid credentials'], 400);
        }

        $user = User::where('email', auth()->user()->email)->first();
        $user->last_login = now('UTC');
        $user->save();

        return response()->json(['token' => $token, 'user' => auth()->user()]);

    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required',
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required',
            'role' => 'required',
        ]);

        if ($validator->fails()) {    
            return response()->json($validator->messages(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);
        
        $user->save();

        return response()->json(['message' => 'Account created successfully']);
    }

    public function getUsers(Request $request) {
        $users = User::with('orders')->get();
        return response()->json(['data' => $users]);
    }

}
