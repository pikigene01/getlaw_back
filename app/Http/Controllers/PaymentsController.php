<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tokens;


class PaymentsController extends Controller
{
    public function getBrainTreeToken(Request $request){
        $clientToken = $this->gateway()->clientToken()->generate();


        return response()->json([
            'status'=>200,
            'nonce'=> $clientToken,
            'message'=> 'user not authenticated',
       ]);


       }


       public function makePayment(Request $request){

        $data = $request->validate([
            'nonce' => 'required',
        ]);
        $price = $request->room_price;


        $nonceFromTheClient = $request->nonce;
        try {
            $response = $this->gateway()->transaction()->sale([
                'amount' => $price,
                'paymentMethodNonce' => $nonceFromTheClient,
                'options' => [
                    'submitForSettlement' => True
                ]
            ]);
            $token = "qwergttyuiopasdfghjklzxcvbnm12345467890!@#$%^&*()";

            $token = str_shuffle($token);
            $token = substr($token, 4, 13);
            $room_id = $request->room_id;
            $token_save = new Tokens();
            $token_save->token = $token;
            $token_save->creator_id = $room_id;
            $token_save->valid = '1';
            $token_save->save();
            return response()->json([
                'status'=>200,
                'message'=> 'Token svaed',
                'reference' => $response->transaction->id,
                'status' => $response->transaction->status,
                'amount' => $response->transaction->amount,
                'currency' => $response->transaction->currencyIsoCode,
                'payment_method' => $response->transaction->paymentInstrumentType,
                'status_url' => $response->transaction->type,
                'token'=>$token,


           ]);   } catch (\Throwable $th) {
            $th->getMessage();
            return response()->json([
                'status'=>400,
                'message'=> $th->getMessage(),
                'token'=>$th->getMessage(),
           ]);
        }
       }
}
