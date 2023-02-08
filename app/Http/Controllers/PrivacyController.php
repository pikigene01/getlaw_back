<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PrivacyPolicy;


class PrivacyController extends Controller
{
    public function save_privacy(Request $request){
        $data = PrivacyPolicy::get();

        if($data->count() > 0){
         $update = PrivacyPolicy::where('id','1')->update(array('html_data'=>$request->html_data));
        }else{
            $save_policy = new PrivacyPolicy();
            $save_policy->html_data = $request->html_data;
            $save_policy->save();
        }
        return response()->json([
            'status' => 200,
            'message' => 'Data Saved ',
        ]);
    }

    public function get_html_data(Request $request){
        $data = PrivacyPolicy::first();
        return response()->json([
            'status' => 200,
            'html_data'=> $data->html_data,
            'message' => 'Logged IN Successfully By Connectcurb ',
        ]);
    }
}
