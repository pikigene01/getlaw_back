<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;


use App\Models\Blog;


class BlogsController extends Controller
{


   public function all(Request $request){
    $user_id = $request->user_id;
        $blogs = Blog::where('user_id', $user_id)->get();

        return response()->json([
             'status'=>200,
             'blogs'=> $blogs,
             'messagge'=> 'Blogs have successfully retrieved',
        ]);
   }
   public function all_cat(Request $request){
    $category = $request->category;
        $blogs = Blog::where('category', 'LIKE', '%'.$category.'%')
        // $blogs = Blog::where('category', $category)
        // ->join('users.id', 'blogs.user_id')
        ->get();

        return response()->json([
             'status'=>200,
             'blogs'=> $blogs,
             'messagge'=> 'Blogs have successfully retrieved',
        ]);
   }
   public function all_blogs(Request $request){
        $blogs = Blog::orderBy('created_at', 'ASC')->limit(6)
        // ->join('users.id', 'blogs.user_id')
        ->get();

        return response()->json([
             'status'=>200,
             'blogs'=> $blogs,
             'messagge'=> 'Blogs have successfully retrieved',
        ]);
   }
   public function add_blog(Request $request){
    $category = $request->category;

    $validator = Validator::make(
        $request->all(),
        [
            'category' => 'required',
            'title' => 'required|min:4',
            'description' => 'required',
            'user_id' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]
    );


    if ($validator->fails()) {
        return response()->json(['status' => 404,'errors'=>$validator->getMessageBag(),'message' => 'validation error']);
    }else{
        if($request->file('image')){
            $image = $request->file('image');
            $var = date_create();
            $date = date_format($var, 'Ymd');
            $imageName = $date.'_'.$image->getClientOriginalName();
            $image->move(public_path().'/uploads/', $imageName);
            $url = URL::to("/").'/uploads/'.$imageName;
           }
        $blogs = new Blog();

        $blogs->title = $request->title;
        $blogs->user_id = $request->user_id;
        $blogs->description = $request->description;
        $blogs->category = $request->category;
        $blogs->image = $url;
        $blogs->save();

        return response()->json([
             'status'=>200,
             'message'=> 'Blog have successfully retrieved'
        ]);
    }
   }
   public function edit_blog(Request $request){
    $category = $request->category;
        $blogs = Blog::where('category', $category)->get();

        return response()->json([
             'status'=>200,
             'blogs'=> $blogs,
             'message'=> 'Blogs have successfully retrieved'
        ]);
   }
   public function delete_blog(Request $request){
    $blog_id = $request->post_id;
    $isAuthenticated = $request->isAuthenticated;
    if($isAuthenticated){
        $blogs = Blog::where('id', $blog_id)->delete();

        return response()->json([
             'status'=>200,
             'message'=> 'Blogs have successfully deleted',
        ]);
    }else{
        return response()->json([
            'status'=>400,
            'message'=> 'user not authenticated',
       ]);
    }
   }


}
