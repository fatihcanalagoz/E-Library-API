<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
 

class AuthController extends Controller
{
    
    public function register(Request $request){
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

       $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password'])
       ]);
        $token = $user->createToken('AccessToken')->plainTextToken;

        return response()->json([
            'data' => [
                'user' => $user,
                'token' => $token
            ]
            ],201);
    }

    public function login(Request $request){
        $fields = $request->validate([
            'email' => 'required|string',
            'password' =>'required|string'
        ]);

        $user = User::where('email',$fields['email'])->first();
        if(!$user || !Hash::check($fields['password'],$user->password )){

             return response()->json([
                'message' => 'This credential does not match our records'
            ]);
        }
        $token = $user->createToken('Auth-Token')->plainTextToken;
        return response()->json([
            'user' => $user,
            'token' => $token
        ]);

    }

    public function logout(){
        auth()->user()->token()->delete();
        return  [
            'message' => 'You logged out.'
        ];
    }
}
