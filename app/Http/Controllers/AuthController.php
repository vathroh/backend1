<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'c_password' => 'required|same:password'
        ]);

        if($validation->fails()){
            return response()->json($validation->errors(), 400);
        };

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return $user;
    }

    public function login(Request $request){
        if(!Auth::attempt($request->only('email', 'password'))){
            return response([
                'message' => 'Email atau password salah!'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();

        $token = $user->createToken('token')->plainTextToken;

        $cookie = cookie('jtosp1', $token, 60*24);

        return response([
            'message' => 'Anda berhasil login.'
        ])->withCookie($cookie);

    }

    public function logout(Request $request){
        $cookie = Cookie::forget('jtosp1');

        return response([
            'message' => 'Anda sudah berhasil logout.'
        ])->withCookie($cookie);
    }

    public function user(){
        return Auth::user();
    }

}
