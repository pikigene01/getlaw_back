<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Currencyrates;
use App\Models\Notifications;


class loginController extends Controller
{


    public function __construct()
    {

    }

    public function loginweb(Request $request){
return view('auth.login');
    }

    public function login(Request $request){

        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|max:191',
                'password' => 'required',

            ]
        );


        if ($validator->fails()) {
            return response()->json(['status'=>400,'message' =>' validation error']);
        }else{
            $user = User::where('email', $request->email)->first();

            if(! $user || !Hash::check($request->password, $user->password)){
               return response()->json([
                   'status' => 401,
                   'message' => 'Invalid Credentials',

               ]);
            }else{
       $token = $user->createToken($user->email.'_Token')->plainTextToken;
                return response()->json([
                    'status' => 200,
                    'username' => $user->name,
                    'user_id' => $user->id,
                    'token' => $token,
                    'message' => 'Logged IN Successfully By GetLaw',
                ]);

            }
        }

    }
   public function funds(Request $request){
       $user_id = $request->user_id;
       if(empty($user_id)){
      $funds = '0';
       }else{
        $user = User::where('id', $request->user_id)->first();
        $funds = $user->funds;
       }

       if($request->currency === 'usd'){
           $money = $funds;
       }else if($request->currency === 'rtgs'){
      $rate_count = Currencyrates::where('country', $request->currency)->get();
       foreach($rate_count as $row){
        $money = $funds * $row->rate;

       }
       }

    return response()->json([
        'status' => 200,
        'funds' => $money,
        'message' => 'Your funds have been collected successfully',
    ]);
   }
   public function rates(Request $request){
    $user_token = $request->user_token;
    $currency = $request->currency;
    $rate = $request->rate;
    $validator = Validator::make(
        $request->all(),
        [
            'user_token' => 'required|max:191',
            'currency' => 'required',
            'rate' => 'required',

        ]
    );


    if ($validator->fails()) {
        return response()->json(['status'=>400,'message' =>' validation error']);
    }else{
        $rate_count = Currencyrates::where('country', $currency)->get();
        if($rate_count->count() > 0){
        $update = Currencyrates::where('country', $currency)
        ->update(array('country'=>$currency,'rate'=>$rate));
        return response()->json([
            'status' => 200,
            'message' => 'currency updated successfully',
        ]);
        }else{
            $rates = array(
                "country" => $currency,
                "rate" => $rate
               );
                $rate = Currencyrates::create($rates);
                // $rate->country = $currency;
                // $rate->rate = $rate;
                // $rate->save();

             return response()->json([
                 'status' => 200,
                 'message' => 'Ok new',
             ]);
        }

}
}
   public function get_rates(Request $request){
    $currency = $request->currency;

    $rates = Currencyrates::where('country',$currency)->get();

 return response()->json([
     'status' => 200,
     'rates' => $rates,
     'message' => 'Ok',
 ]);
}

    public function refresh(Request $request){

    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json([
            'status'=>200,
            'message' => 'Successfully logged out',
            ]);

     }
}


