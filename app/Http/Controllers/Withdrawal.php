<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\MoneyWithdrawal;
use App\Models\Money;
use App\Models\User;


class Withdrawal extends Controller
{
    public function index(Request $request){

        return json_encode(array('status'=>200));
    }

    public function withdraw_money(Request $request){
        return json_encode(array('status'=>200,'message'=>'withdraw money get'));

    }
    public function withdraw_money_save(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'user_id' => 'required',
                'money_to_withdraw' => 'required',

            ]
        );


        if ($validator->fails()) {
            return response()->json(['status' => 404,'errors'=>$validator->getMessageBag(),'message' => 'validation error']);
        }else{

         $MoneyWithdrawal = new MoneyWithdrawal();
         $MoneyWithdrawal->money_to_withdraw = $request->money_to_withdraw;
         $MoneyWithdrawal->user_id = $request->user_id;
         $MoneyWithdrawal->status = 'pending';//status can be changes later to collected if user money is send
         $MoneyWithdrawal->save();
         $money_model = Money::where('user_id', $request->user_id)->get();
         if($money_model->count() > 0){
            $money_model = Money::where('user_id', $request->user_id)->update(array('funds'=>'00'));
            $update_user = User::where('id',$request->user_id)
            ->update(array('funds'=>'00'));
         }

        return json_encode(array('status'=>200,'message'=>'withdraw money save'));
        }
    }
}
