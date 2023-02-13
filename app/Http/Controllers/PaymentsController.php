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
            'message'=>  'authenticated',
       ]);

       }


       public function makePayment(Request $request){
        $price = $request->room_price;

        if($price == 0){
            $token = "qwergttyuiopasdfghjklzxcvbnm12345467890";

            $token = str_shuffle($token);
            $token = substr($token, 4, 13);
            $room_id = $request->room_id;
            $token_save = new Tokens();
            $token_save->token = $token;
            $token_save->creator_id = $room_id;
            $token_save->valid = '1';
            $token_save->price = $price;
            $token_save->save();
            return response()->json([
                'status'=>200,
                'message'=> 'Token svaed',
                'token'=>$token,
           ]);
        }else{

        $data = $request->validate([
            'visa_number' => 'required',
            'cvc' => 'required',
            'exp_month' => 'required',
            'exp_year' => 'required',
            'room_price' => 'required',
        ]);


        // $nonceFromTheClient = $request->nonce;

        try {
            // $response = $this->gateway()->transaction()->sale([
            //     'amount' => $price,
            //     'paymentMethodNonce' => $nonceFromTheClient,
            //     'options' => [
            //         'submitForSettlement' => True
            //     ]
            // ]);
            $stripe = new \Stripe\StripeClient(
             env('STRIPE_PRIVATE_KEY')
            );
            $res = $stripe->tokens->create([
            'card' => [
            'number'=> $request->visa_number,
            'exp_month'=> $request->exp_month,
            'exp_year'=> $request->exp_year,
            'cvc' => $request->cvc,
            ],
            ]);
         \Stripe\Stripe::setApiKey(
           env('STRIPE_PRIVATE_KEY')
          );

        $response =  $stripe->charges->create([
          'amount' => $price * 100,//stripe wants users to multiply given amont by 100
          'currency' => 'usd',
          'source' => 'tok_visa',
          'description' => 'Connectcurb get token'
          ]);
            $token = "qwergttyuiopasdfghjklzxcvbnm12345467890";
           if($response->status){
            $token = str_shuffle($token);
            $token = substr($token, 4, 13);
            $room_id = $request->room_id;
            $token_save = new Tokens();
            $token_save->token = $token;
            $token_save->creator_id = $room_id;
            $token_save->valid = '1';
            $token_save->price = $price;
            $token_save->save();
            return response()->json([
                'status'=>200,
                'message'=> 'Token saved sccessfully',
                // 'reference' => $response->transaction->id,
                // 'status' => $response->transaction->status,
                // 'amount' => $response->transaction->amount,
                // 'currency' => $response->transaction->currencyIsoCode,
                // 'payment_method' => $response->transaction->paymentInstrumentType,
                // 'status_url' => $response->transaction->type,
                'token'=>$token,


           ]);
           }else{
            return response()->json([
                'status'=>400,
                'message'=> 'Token failed',
                'token'=>'Please use valid card',

           ]);
           }
            } catch (\Throwable $th) {
            $th->getMessage();
            return response()->json([
                'status'=>400,
                'message'=> $th->getMessage(),
                'token'=>$th->getMessage(),
           ]);
        }
       }
    }//close room is not for free
}
