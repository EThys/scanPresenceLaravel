<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PasswordChangeController extends Controller
{
    public function changePassword(Request $request){
        $validatedData=Validator::make($request->all(),[
            "oldPassword"=> "required",
            "newPassword"=> "required",
            "confirmPassword"=> "required|same:newPassword",
        ]);


        if ($validatedData->fails()) {
            $errors = $validatedData->errors();
            $errorMessage = '';
        
            switch (true) {
                case $errors->has('confirmPassword'):
                    $errorMessage = $errors->first('confirmPassword');
                    break;
                case $errors->has('oldPassword'):
                    $errorMessage = $errors->first('oldPassword');
                    break;
                case $errors->has('newPassword'):
                    $errorMessage = $errors->first('newPassword');
                    break;
                default:
                    $errorMessage = 'Veuillez remplir tous les champs obligatoires.';
                    break;
            }
        
            return response()->json([
                'status' => 400,
                'message' => $errorMessage,
            ], 400);
        }


        $user = Auth::user();
        
        if (Hash::check($request->oldPassword, $user->password)) {

            $user->password = Hash::make($request->newPassword);
            $user->save();
            auth()->user()->tokens()->delete();

                
            return response()->json([
                'status'=>200,
                'message' => 'Mot de passe changé avec succès !'
            ], 200); 
        }else{
            return response()->json([
                'status'=>400,
                'message' => 'Le mot de passe actuel est incorrect'
            ], 400);
        }
    }
}
