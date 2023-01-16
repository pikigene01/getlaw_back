<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Tokens;


class TokenController extends Controller
{
    public function updateToken(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'token' => 'required|min:8',
                'valid' => 'required',

            ]
        );
          $token = $request->token;


        if ($validator->fails()) {
            return response()->json(['status' => 401,'message' => 'cofirmation wiil be done on client side']);
        }else{
            $token_update = Tokens::where('token',$token)->update(array('valid'=>$request->valid));
            return response()->json(['status' => 200,'message' => 'room confirmed']);

        }
    }
}
