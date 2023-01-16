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
        $price = 0;
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|min:3',
                'email' => 'required|min:6',
                'phone' => 'required',
                'description'=> 'required|min:12',
                'role'=> 'required',
                'password' => 'required|min:6',
                'confirm_password' => 'required|same:password',
                'token' => 'required',
                // 'location' => 'required',
                // 'latitude' => 'required|min:4',
                // 'longitude' => 'required|min:4',
                'picture_law' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]
        );


        if ($validator->fails()) {
            return response()->json(['status' => 404,'errors'=>$validator->getMessageBag(),'message' => 'validation error']);
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


  if(empty($request->price)){
    $price = 0;
  }else{
    $price = $request->price;
  }

        $input = array(
            'name' => $request->name,
            'surname'=>$request->surname,
            'email'=> $request->email,
            'phone'=> $request->phone,
            'description'=> $request->description,
            'picture'=> $url,
            'role'=> $request->role,
            'price'=>$price,
            'location'=>$request->location,
            'isVerified'=>'1',
            'belongs'=>$request->belongs,
            'latitude'=>$request->latitude,
            'longitude'=>$request->longitude,
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
        $user_file = User::where('id',$user_id)->where('belongs',$request->belongs)->get();

         if($isAuthenticated){
        $user = User::where('id',$user_id)->where('belongs',$request->belongs)->delete();
        foreach($user_file as $user_file){
            $delete_image = true;
            if($user && $delete_image){
            return response()->json([
                'status' => 200,
                'message' => 'User Deleted Successfully and user image',
            ]);


    }else{
        return response()->json([
            'status' => 400,
            'message' => 'Not Authorised',
        ]);
    }
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
