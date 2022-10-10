<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

        $nonceFromTheClient = $data['nonce'];
        try {
            $response = $this->gateway()->transaction()->sale([
                'amount' => '10.0',
                'paymentMethodNonce' => $nonceFromTheClient,
                'options' => [
                    'submitForSettlement' => True
                ]
            ]);

            return response()->json([
                'status'=>200,
                'message'=> 'Token svaed',
                'reference' => $response->transaction->id,
                'status' => $response->transaction->status,
                'amount' => $response->transaction->amount,
                'currency' => $response->transaction->currencyIsoCode,
                'payment_method' => $response->transaction->paymentInstrumentType,
                'status_url' => $response->transaction->type,

           ]);   } catch (\Throwable $th) {
            $th->getMessage();
            return response()->json([
                'status'=>400,
                'message'=> 'Failed to buy the token',
           ]);
        }
       }
}
