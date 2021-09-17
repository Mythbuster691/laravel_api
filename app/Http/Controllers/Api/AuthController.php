<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function registerWithEmail(Request $request){
        $data = [
            "email" => $request->email,
            "name" => $request->email,
            "password" => $request->password,
            "email_verified_at" => Carbon::now(),
        ];

        $validator = Validator::make($data, [
            "email" => 'required|email|unique:users',
            "password"=>'required|min:6'
        ]);

        if($validator->fails()){
            return $validator->errors();
        }

        $user = User::create([
            'name' => $request->input('email'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        $token = $user->createToken('Laravel8PassportAuth')->accessToken;
        return response()->json(['token' => $token, 'user_id' => $user->id], 200);
    }

    public function registerWithPhone(Request $request){
        $data = [
            "phone" => $request->phone,
            "name" => $request->phone,
            "password" => $request->password,
            "email_verified_at" => Carbon::now(),
        ];

        $validator = Validator::make($data, [
            "phone" => 'required|digits:10|unique:users',
            "password"=>'required|min:6'
        ]);

        if($validator->fails()){
            return $validator->errors();
        }

        $user = User::create([
            'name' => $request->input('phone'),
            'phone' => $request->input('phone'),
            'password' => bcrypt($request->input('password')),
        ]);

        $token = $user->createToken('Laravel8PassportAuth')->accessToken;
        return response()->json(['token' => $token, 'user_id' => $user->id], 200);
    }

    public function loginWithEmail(Request $request){
        $data = [
            "email" => $request->email,
            "password" => $request->password
        ];
        $validator = Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if($validator->fails()){
            return $validator->errors();
        }

        if (Auth::attempt($data)) {
            $token = Auth::user()->createToken('Laravel8PassportAuth')->accessToken;
            return response()->json(['token' => $token, 'user_id' => Auth::user()->id], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }

    }

    public function loginWithPhone(Request $request){
        $data = [
            "phone" => $request->phone,
            "password" => $request->password
        ];
        $validator = Validator::make($data, [
            'phone' => 'required|digits:10',
            'password' => 'required|min:6'
        ]);

        if($validator->fails()){
            return $validator->errors();
        }

        if (Auth::attempt($data)) {
            $token = Auth::user()->createToken('Laravel8PassportAuth')->accessToken;
            return response()->json(['token' => $token, 'user_id' => Auth::user()->id], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }

    }

    public function profile(Request $request, $id){
        $user = User::findOrFail($id);
        $validator = Validator::make($request->all(),[
            "name" => 'required|min:2',
            "email" => 'required|email|unique:users,id,'.$id,
            "phone" => 'required|digits:10|unique:users,id,'.$id,
        ]);

        if($validator->fails()){
            return $validator->errors();
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        if($user->update()){
            return response()->json(['user_id' => $user->id, 'message' => 'User Profile Updated Successfully !!'], 200);
        }else{
            return response()->json(['message' => 'Some Error Occured!!'], 401);
        }

    }
}
