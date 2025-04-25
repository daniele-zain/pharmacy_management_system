<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    function Register(Request $request){
        $data = $request->all();
        $validator= Validator::make($data,[
            'name' => 'required|string',
            'email' => 'required|string|unique:Users,email',
            'password' => 'required|string|confirmed',
            'phone'=>'string|required|unique:Users,phone',
            'type' => 'required'
        ]);
        if($validator->fails()){
            return response()->json('Something went wrong! try again',400);
        }

       $user = Auth::user();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'type' => $data['type'],
            'password' => bcrypt($data['password']),
            //updated in the table
            'phone'=>$data['phone']
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response()->json($response,201);
    }

    public function login(Request $request){
        $fields = $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string'
        ]);

        //check email
        $user = user::where('phone' , $fields['phone'])->first();

        //check password
        if(!$user || !Hash::check($fields['password'] , $user->password)){
            return response([
                'message' => 'login failed'
            ] , 401);

        }
        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response , 201);
    }

    public function logout(Request $request){

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged Out'
        ],200);
    }

}
