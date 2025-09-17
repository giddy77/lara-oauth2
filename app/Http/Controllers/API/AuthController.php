<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use  Auth;

class AuthController extends Controller
{
    /**
     * Registration
     */

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            // 'confirm_password' => 'required|same:password'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $response = [];
        $response['token'] = $user->createToken("MyApp")->accessToken;
        $response['user'] = $user->name;
        $response['email'] = $user->email;

        return response()->json([
            "status" => 1,
            "message" => 'Registration Successful!',
            "data" => $response
        ]);
    }

    /**
     * Login
     */

    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, "password" => $request->password])) {
            $user = Auth::user();

        $response = [];
        $response['token'] = $user->createToken("MyApp")->accessToken;
        $response['user'] = $user->name;
        $response['email'] = $user->email;

        return response()->json([
            "status" => 1,
            "message" => 'Login Successful!',
            "data" => $response
        ]);

        }

        return response()->json([
            "status" => 0,
            "message" => "Failed to Login",
            "data" => null
        ]);
    }
}
