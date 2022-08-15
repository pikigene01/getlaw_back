<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;

use App\Models\User;
use App\Models\Tokens;
use Illuminate\Support\Facades\Validator;



class registerController extends Controller
{
    public function register(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|min:3',
                // 'email' => 'required|min:6',
                'phone' => 'required',
                'description'=> 'required|min:12',
                'role'=> 'required',
                'password' => 'required|min:6',
                'confirm_password' => 'required|same:password',
                'token' => 'required',
                'picture_law' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]
        );


        if ($validator->fails()) {
            return response()->json(['status' => 401,'message' => 'Please fill all fields']);
        }else{
            if($request->file('picture_law')){
             $image = $request->file('picture_law');
             $var = date_create();
             $date = date_format($var, 'Ymd');
             $imageName = $date.'_'.$image->getClientOriginalName();
             $image->move(public_path().'/uploads/', $imageName);
             $url = URL::to("/").'/uploads/'.$imageName;
            }
            $token  = $request->token;

            $token_validate = Tokens::where('token',$token)->where('valid','1')->get();
             if($token_validate->count() > 0){


        $input = array(
            'name' => $request->name,
            'surname'=>$request->surname,
            'email'=> $request->email,
            'phone'=> $request->phone,
            'description'=> $request->description,
            'picture'=> $url,
            'role'=> $request->role,
            'price'=>$request->price,
            'location'=>$request->location,
            'isVerified'=>'1',
            'belongs'=>$request->belongs,
            'password' => bcrypt($request->password),
        );

        $user = User::where('email', $request->email)->first();

       if($user){
        return response()->json(['status' => 401,'message' =>'user already exist..']);
       }

        $user = User::create($input);
        $token = $user->createToken($user->email.'_Token')->plainTextToken;
        if($request->role === '1'){
            return response()->json([
                'status' => 200,
                'username' => $user->name,
                'user_id' => $user->id,
                'token' => $token,
                'message' => 'Lawfirm Successfully',
            ]);
        }else{
            return response()->json([
                'status' => 401,
                'message' => 'Lawyer Registered Successfully',
            ]);
        }


    }else{
        return response()->json(['status' => 401,'message' =>'Invalid Token Please use the correct token.']);

    }
        };

    }

    public function delete_user(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'user_id' => 'required',
                'belongs' => 'required'

            ]
        );


        if ($validator->fails()) {
            return response()->json(['status' => 401,'message' => 'Please fill all fields']);
        }else{

        $user_id = $request->user_id;
        $isAuthenticated = $request->isAuthenticated;
if($isAuthenticated){
        $user = User::where('id',$user_id)->where('belongs',$request->belongs)->delete();
        if($user){
        return response()->json([
            'status' => 200,
            'message' => 'User Deleted Successfully',
        ]);
    }else{
        return response()->json([
            'status' => 400,
            'message' => 'Not Authorised',
        ]);
    }
    }else{
        return response()->json([
            'status' => 400,
            'message' => 'Please you are not authorized to delete user',
        ]);
    }
    }
    }
    public function update_user(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'user_id' => 'required',

            ]
        );


        if ($validator->fails()) {
            return response()->json(['status' => 401,'message' => 'Please fill all fields']);
        }else{
        $user_id = $request->user_id;

        $user = User::where('id',$user_id)->update(array('name'=>$request->name,
        'phone'=>$request->phone,'location'=>$request->location,'description'=>$request->description
    ,'surname'=>$request->surname));

        return response()->json([
            'status' => 200,
            'message' => 'User updated Successfully',
        ]);
    }
    }


}
