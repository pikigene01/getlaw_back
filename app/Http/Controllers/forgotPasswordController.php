<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Twilio\Rest\Client;
use App\User;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    //find user
    //send otp to user
    //verfy otp
    //success
    //change password
    public function forgot_pass(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|max:191',
                'password' => 'required',

            ]
        );


        if ($validator->fails()) {
            return response()->json(['status' => 404,'errors'=>$validator->getMessageBag(),'message' => 'validation error']);

        }
    }

    public function checkUser(Request $request){
        $data = User::where('phone_number', $request->phone_number)->first();
        if (!is_null($data)) {
            //change user verification to false
            $user = tap(User::where('phone_number', $data['phone_number']))
                    ->update(['isVerified'=>false]);
            //continue here
            $token = getenv("TWILIO_AUTH_TOKEN");
            $twilio_sid = getenv("TWILIO_SID");
            $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
            $twilio = new Client($twilio_sid, $token);

           $verification = $twilio->verify->v2->services($twilio_verify_sid)
                ->verifications
                ->create($request['phone_number'], "sms");
                return response()->json([
                    'success' => true,
                    'message' => 'Verification message was send',
                ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'This Phone number is not registered with us',
        ]);
    }

    public function verify(Request $request){
        $data = $request->validate([
            'verification_code' => ['required', 'numeric'],
            'phone_number' => ['required', 'string'],
        ]);
        /* Get credentials from .env */
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");

        $twilio = new Client($twilio_sid, $token);

        $verification_check = $twilio->verify->v2->services($twilio_verify_sid)
            ->verificationChecks
            ->create($request->input('verification_code'),
                    ["to" => $request->input('phone_number')]
            );

            if($verification_check->valid){
                $user = tap(User::where('phone_number', $data['phone_number']))
                    ->update(['isVerified'=>true]);
                return response()->json([
                    'success' => true,
                    'message' => 'Phone Number Verified procceed to change password',
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Verification Error',
            ]);


    }

    public function changepassword(Request $request){
        //check if user is verified
        $user = User::where('phone_number', $request->phone_number)->first();
        if($user->isVerified == true){
        $user->password = Hash::make($request->get('new_password'));
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password changed succesfully',
        ]);
        }
        return response()->json([
            'success' => false ,
            'message' => 'Your OTP Was not verified',
        ]);


    }

    public function user(){
        $users = User::all();

        foreach($users as $user){
            $user->delete();
        }
        return response()->json([
            'success' => true ,
            'message' => 'deleted',
        ]);
    }

}
