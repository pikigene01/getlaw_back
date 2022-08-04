<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RobController extends Controller
{
   public function index(Request $request){
       dd('rob');
   }

   public function show($id)
   {
       return view('user.profile', [
           'user' => User::findOrFail($id)
       ]);
   }
}
