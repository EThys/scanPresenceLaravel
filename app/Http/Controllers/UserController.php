<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users=User::all();
    }

    public function login(Request $request)
    {
        $validatedData=Validator::make($request->all(),
        [
            'email'=>'required',
            'password'=>'required'

        ]);
        if($validatedData->fails()){
            return response()->json([
              'status'=>400,
              'errors'=>$validatedData->errors()
            ],400);
        }

        $user = User::where('email', $request->email)->first();

        if(!$user){
            return response()->json([
              'message' => 'unknown user' 
            ],400);
        }
        if(!Hash::check($request->password, $user->password)){
            return response()->json([
              'message' => 'Incorrect password'
            ],400);
          }
          

        return response()->json([
            'status' => 200,
            'message' => 'Connexion réussie',
            'token'=>$user->createToken("API TOKEN")->plainTextToken,
            'user'=>$user
        ],200);
    }

    public function register(Request $request){
        $validatedData=Validator::make($request->all(),
        [
            "userName"=>'required',
            "password"=>'required',
            "email"=>'required|unique:TUsers',
            "IsAdmin"=>'required'
        ]);

        if($validatedData->fails()){
            return response()->json([
                'status'=>400,
                'message'=>'Inscription echouée',
                'errors'=>$validatedData->errors()
            ],400);
        }

        $user=User::create([
            'userName'=>$request->userName,
            'password'=>bcrypt($request->password),
            'email'=>$request->email,
            'IsAdmin'=>$request->IsAdmin,

        ]);
        return response()->json([
            'status'=>200,
            'message'=>'Inscription reussie',
            'token'=>$user->createToken("API TOKEN")->plainTextToken
        ],200);
    }

    public function logout() {
    
        auth()->user()->tokens()->delete();
      
        return response()->json([
          'status' => 200,
          'message' => 'Deconnecté'
        ],200);
    }
}
