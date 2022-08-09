<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Notifications;

use App\Models\User;


class changePasswordController extends Controller
{
    public function changePassword(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
            'current_password' => 'required',
            'new_password' => 'required|string|min:6',
            'confirm_new_password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>400,'message' =>' validation error']);
        }else{
        if(strcmp($request->current_password, $request->new_password) == 0){
            //Current password and new password are same
            return response()->json([
                'status' => 400,
                'message' => 'New Password cannot be same as your current password. Please choose a different password.',
            ]);

        }
        if(strcmp($request->current_password, $request->confirm_new_password) == 0){
            //Current password and new password are same
            return response()->json([
                'status' => 400,
                'message' => 'New Password and Confirm Password do no match!!!!',
            ]);

        }
        $user = User::where('id', $request->user_id)->first();
        // foreach($user as $user){
            if(! $user || !Hash::check($request->current_password, $user->password)){
                return response()->json([
                    'status' => 401,
                    'message' => 'Invalid Credentials',

                ]);
             }else{



        //Change Password
        $user = User::where('id',$request->user_id)->update(array('password' => bcrypt($request->new_password)));

        return response()->json([
            'status' => 200,
            'message' => 'Password Changed Successfully !',
        ]);

    }
    }
}
}
