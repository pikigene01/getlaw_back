<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

        ]
    );


    if ($validator->fails()) {
        return response()->json(['status' => 400,'message' => 'Validation Error']);
    }else{
        $blogs = new Blog();

        $blogs->title = $request->title;
        $blogs->user_id = $request->user_id;
        $blogs->description = $request->description;
        $blogs->category = $request->category;
        $blogs->image = 'hdbf';
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
