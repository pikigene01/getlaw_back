<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\LawfirmsReviews;
use App\Models\Bookings;
use App\Models\Notes;
use App\Models\Notifications;
use App\Models\Money_stats;
use App\Models\Blog;
use App\Models\LiveChat;
use App\Models\Tokens;
use App\Models\AvailabilityTimes;
use Paynow\Payments\Paynow;
use Twilio\Rest\Client;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class LawfirmController extends Controller
{
    public function index(){
        return json_encode(array('gene'=>1));
    }
    public function all(Request $request){
        // $role = $request->role;
        $role = "1";
        $count_reviews_for_each_post = [];
        $post_reviews_perc = [];
        $new_count = "";
        $user = User::inRandomOrder()->where('role','=',$role)->Limit('9')
        // ->join('reviews.id','users.id')
        ->get();
        foreach($user as $row){
            $num = LawfirmsReviews::where('post_id','=',$row->id)->count();
            $numLawyers = User::where('belongs','=',$row->id)->count();

             $five = LawfirmsReviews::where('post_id',$row->id)->where('rated_index','5')->count();
        // $five_perc = $reviewscount / $five * 100;
        $four = LawfirmsReviews::where('post_id',$row->id)->where('rated_index','4')->count();
        // $four_perc = $reviewscount / $four * 100;
        $three = LawfirmsReviews::where('post_id',$row->id)->where('rated_index','3')->count();
        // $three_perc = $reviewscount / $three * 100;
        $two = LawfirmsReviews::where('post_id',$row->id)->where('rated_index','2')->count();
        // $two_perc = $reviewscount / $two * 100;
        $one = LawfirmsReviews::where('post_id',$row->id)->where('rated_index','1')->count();
        // $one_perc = $reviewscount / $one * 100;
             $new_post_review_count = array_push($post_reviews_perc, array(["five_stars"=>"$five","four_stars"=>"$four","three_stars"=>"$three","two_stars"=>"$two","one_stars"=>"$one"]));
            $new_count = array_push($count_reviews_for_each_post, array(["count" => "$num","lawyers_count" =>$numLawyers]));
        }
        // dd($post_reviews_perc);
    //    dd($count_reviews_for_each_post);
        return response()->json([
            'status' => 200,
            'posts' => $user,
            'number_reviews' =>  array($count_reviews_for_each_post),
            'reviews'=> array($post_reviews_perc),
            // 'description' => $user->description,
            // 'phone' => $user->phone,
            // 'lawyers' => $user->name,
            // 'rcreated_ateviews' => $user->reviews,
            // '' => $user->created_at,
            // 'message' => 'data collected successfully',
        ]);
    }
    public function lawyers_get(Request $request){
        $role = $request->role;

        $count_reviews_for_each_post = [];
        $post_reviews_perc = [];
        $new_count = "";
        $user = User::inRandomOrder()->where('role','=',$role)->Limit('15')
        // ->join('reviews.id','users.id')
        ->get();
        // dd($post_reviews_perc);
    //    dd($count_reviews_for_each_post);
        return response()->json([
            'status' => 200,
            'posts' => $user,

        ]);
    }
    public function lawyers_get_info(Request $request){

       $user_id = $request->user_id;
        $user = User::
        where('id',$user_id)
        // ->join('reviews.id','users.id')
        ->first();
        // dd($post_reviews_perc);
    //    dd($count_reviews_for_each_post);
        return response()->json([
            'status' => 200,
            'posts' => $user,

        ]);
    }
    public function lawyers_get_dash(Request $request){
        $role = '2';
       $belongs_id = "";
       $user_id = $request->user_id;
       $check_role = User::where('id',$user_id)->get();
       $user = [];
       $new_array = "";
       foreach($check_role as $row){
        if($row->role == $role){
        $user = User::inRandomOrder()
        ->where('belongs',$row->belongs)->orWhere('id',$row->belongs)
        ->get();

        }else{
            $user = User::inRandomOrder()
            ->where('id',$user_id)->orWhere('belongs',$user_id)
            ->get();


        }
       }
        // $user = User::inRandomOrder()->where('role','=',$role)
        // ->where('belongs',$user_id)
        // ->Limit('15')
        // // ->join('reviews.id','users.id')
        // ->get();
        // dd($post_reviews_perc);
    //    dd($count_reviews_for_each_post);
        return response()->json([
            'status' => 200,
            'posts' => $user,

        ]);
    }
    public function view(Request $request){
        $id = $request->post_id;

        $lawfirm = User::where('role','1')
        ->where('id',$id)->get();

        return response()->json([
            'status' => 200,
            'posts' => $lawfirm,
        ]);
    }
    public function lawyers(Request $request){
        $id = $request->post_id;

        $lawyers = User::where('belongs',$id)->get();

        return response()->json([
            'status' => 200,
            'posts' => $lawyers,
        ]);
    }
    public function lawyers_all(Request $request){
        $role = $request->role;

        $lawyers = User::where('role',$role)->get();

        return response()->json([
            'status' => 200,
            'posts' => $lawyers,
        ]);
    }
    public function dash_board(Request $request){
        $lawfirm_id = $request->lawyer_id;

        $bookings = Bookings::where('lawyer_id',$lawfirm_id)->count();
        $reviews = LawfirmsReviews::where('post_id',$lawfirm_id)->count();

        return response()->json([
            'status' => 200,
            'bookings' => $bookings,
            'reviews' => $reviews,
            'reports' => '0',
        ]);
    }
    public function user_profile(Request $request){
        $lawyer_id = $request->user_id;

        $users = User::where('id',$lawyer_id)->get();

        return response()->json([
            'status' => 200,
            'user_profile' => $users,
            'message' => "data collected",

        ]);
    }

    public function get_schedule(Request $request){
        $lawyer_id = $request->lawyer_id;
        //when confirmed == 0 it means that lesson is still in pending status so sent it to lawyer schedules
        $bookings = Bookings::where('lawyer_id',$lawyer_id)->where('confirmed','0')->get();
        if($bookings->count() > 0){
      $bookings = $bookings;
        }else{
            $bookings = [];
        }

        return response()->json([
            'status' => 200,
            'message' => 'Data collected successfully',
            'bookings' => $bookings
        ]);

    }
    public function save_schedule(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'lawyer_id' => 'required',
                'token' => 'required|min:8',
                'data_week' => 'required',
                'data_event' => 'required',

            ]
        );
          $token = $request->token;


        if ($validator->fails()) {
            return response()->json(['status' => 401,'message' => 'Please insert valid token']);
        }else{
        $token_all = Tokens::where('token',$token)->where('lawyer_id',$request->lawyer_id)->where('valid','1')->get();
          if($token_all->count() > 0){
            $bookings_exist = Bookings::where('token',$request->token)
            ->where('lawyer_id',$request->lawyer_id)->first();
            if(!$bookings_exist){
          $bookings = new Bookings();

          $bookings->lawyer_id = $request->lawyer_id;
          $bookings->token = $request->token;
          $bookings->data_event = $request->data_event;
          $bookings->data_date = $request->data_week;
          $bookings->type = $request->type;
          $bookings->save();

          return response()->json([
            'status' => 200,
            'message' => 'You successfully scheduled',
        ]);
            }else{
                return response()->json([
                    'status' => 401,
                    'message' => 'Bookings Failed',
                ]);
            }

        }else{
            return response()->json([
                'status' => 401,
                'message' => 'Invalid Token',
            ]);
        }
    }

}

public function set_availability(Request $request){

    $validator = Validator::make(
        $request->all(),
        [
            'lawyer_id' => 'required',
            'times_available' => 'required',

        ]
    );


    if ($validator->fails()) {
        return response()->json(['status' => 401,'message' => 'Please select times no\ available']);
    }else{
        $times = [];
   $times = $request->times_available;

   foreach($times as $row){
    $availability = new AvailabilityTimes();

    $bookings = new Bookings();

          $bookings->lawyer_id = $request->lawyer_id;
          $bookings->data_event = $row['schedule_time'];
          $bookings->data_date = $row['week_day'];
          $bookings->type = $request->type;

          $bookings->save();
   }


     return response()->json([
         'status' => 200,
         'message' => 'Availabity have been set successfully',
     ]);
}

}
public function reset_availability(Request $request){

    $validator = Validator::make(
        $request->all(),
        [
            'lawyer_id' => 'required',
            'type' => 'required',

        ]
    );


    if ($validator->fails()) {
        return response()->json(['status' => 401,'message' => 'Please select times available']);
    }else{
        $bookings = Bookings::where('lawyer_id',$request->lawyer_id)->where('type', $request->type)->get();
        if($bookings->count() > 0){
    $bookings = Bookings::where('lawyer_id',$request->lawyer_id)->where('type', $request->type)->delete();

    return response()->json([
         'status' => 200,
         'message' => 'Availabity have been reseted successfully',
     ]);
    }else{
        return response()->json([
            'status' => 400,
            'message' => 'No Availability found in your account',
        ]);
    }
}

}

    public function save_review(Request $request){

        $validator = Validator::make(
            $request->all(),
            [
                'review' => 'required|max:191',
                'token' => 'required',
                'rated_index' => 'required',

            ]
        );


        if ($validator->fails()) {
            return response()->json(['status'=>400,'message' => 'validation errors by Gene Law']);
        }else{
            $token = $request->token;
            $token_all = Tokens::where('token',$token)->get();
          if($token_all->count() > 0){
            $review_exist = LawfirmsReviews::where('token',$request->token)
            ->where('post_id',$request->post_id)->first();
            if(!$review_exist){
          $review = new LawfirmsReviews();

          $review->post_id = $request->post_id;
          $review->token = $request->token;
          $review->review = $request->review;
          $review->rated_index = $request->rated_index;
          $review->save();

          try{
            $account_sid = env('TWILIO_SID');
            $account_token = env('TWILIO_TOKEN');
            $number = env('TWILIO_FROM');

            $client = new Client($account_sid, $account_token);
            $client->message->create('+263782954717', [
                'from'=>$number,
                'Body'=>'Some Rated Your Lawfirm Go and check..'
            ]);

            return "Message Sent..";

          }catch(\Exception $e){
        //  return $e->getMessage();
          }
        return response()->json([
            'status' => 200,
            'message' => 'review have been saved successfully',
        ]);

    }else{
        return response()->json([
            'status' => 400,
            'message' => 'This user with this token already submitted review',
        ]);

    }

           }else{
        return response()->json([
            'status' => 400,
            'message' => 'Invalid Token Not Found',
        ]);
      }


    }

    }
    public function add_note(Request $request){

        $validator = Validator::make(
            $request->all(),
            [
                'user_id' => 'required',
                'note' => 'required|min:8',

            ]
        );


        if ($validator->fails()) {
            return response()->json(['status'=>400,'message' => 'data not saved empty note']);
        }else{

          $note = new Notes();

          $note->user_id = $request->user_id;
          $note->note = $request->note;
          $note->save();


        return response()->json([
            'status' => 200,
            'message' => 'note have been saved successfully',
        ]);

    }

    }
    public function update_note(Request $request){

        $validator = Validator::make(
            $request->all(),
            [
                'user_id' => 'required',
                'note' => 'required|min:8',
                'post_id' => 'required'

            ]
        );


        if ($validator->fails()) {
            return response()->json(['status'=>400,'message' => $validator->messages()]);
        }else{

          $note = Notes::where('id',$request->post_id)->where('user_id',$request->user_id)
          ->update(array('note'=>$request->note));


        return response()->json([
            'status' => 200,
            'message' => 'note have updated successfully',
        ]);

    }

    }
    public function add_msg(Request $request){

        $validator = Validator::make(
            $request->all(),
            [
                'sender_id' => 'required',
                'receiver_id' => 'required',
                'message' => 'required|min:4',

            ]
        );


        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()]);
        }else{

          $chat = new LiveChat();

          $chat->sender_id = $request->sender_id;
          $chat->receiver_id = $request->receiver_id;
          $chat->message = $request->message;
          $chat->save();


        return response()->json([
            'status' => 200,
            'message' => 'message have been saved successfully',
        ]);

    }

    }
    public function get_msg(Request $request){
               $user_id = $request->user_id;

          $Livechat_msgs = LiveChat::where('sender_id',$user_id)->orWhere('receiver_id',$user_id)->get();


        return response()->json([
            'status' => 200,
            'messages' => $Livechat_msgs,
        ]);


    }
    public function delete_msg(Request $request){
               $id = $request->id;

          $Livechat_msgs = LiveChat::findOrFail($id);
          $Livechat_msgs->delete();


        return response()->json([
            'status' => 200,
            'message' => 'Live chat message deleted successfully...',
        ]);


    }
    public function get_note(Request $request){
        $user_id = $request->user_id;
        $notes_count = Notes::where('user_id',$user_id)->count();

        $notes = Notes::where('user_id',$user_id)
        ->orderBy('created_at','DESC')->get();
        return response()->json([
            'status' => 200,
            'notes' => $notes,
            'notes_count'=>$notes_count
        ]);
    }
    public function delete_note(Request $request){
        $id = $request->id;
        $note = Notes::findOrFail($id);
        $note->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Note deleted successfully',
        ]);
    }
    public function check_token(Request $request){
        $token = $request->token;
        $lawyer_id = $request->lawyer_id;
        //valid token is defined as 1 and 0 for invalid
        $valid = 1;
        $token_all = Tokens::where('token',$token)->where('lawyer_id',$lawyer_id)->where('valid',$valid)->get();
    if($token_all->count() > 0){
        return response()->json([
            'status' => 200,
            'message' => 'valid token',
        ]);
    }else{
        return response()->json([
            'status' => 400,
            'message' => 'Invalid token',
        ]);
    }
    }

    public function search(Request $request){
        $search_val = $request->search;
        $user = User::where('role','1')
        ->orWhere('description','Like','%'.$search_val.'%')
        ->orWhere('phone','Like','%'.$search_val.'%')
        ->orWhere('name','Like','%'.$search_val.'%')
        ->orderBy('created_at','DESC')->get();
        return response()->json([
            'status' => 200,
            'posts' => $user,

        ]);
    }

    public function get_review(Request $request){
        $post_id = $request->post_id;
        $reviewscount = LawfirmsReviews::where('post_id',$post_id)->count();

        $reviewsposts = LawfirmsReviews::where('post_id',$post_id)
        ->orderBy('created_at','DESC')->get();

        $five = LawfirmsReviews::where('post_id',$post_id)->where('rated_index','5')->count();
        // $five_perc = $reviewscount / $five * 100;
        $four = LawfirmsReviews::where('post_id',$post_id)->where('rated_index','4')->count();
        // $four_perc = $reviewscount / $four * 100;
        $three = LawfirmsReviews::where('post_id',$post_id)->where('rated_index','3')->count();
        // $three_perc = $reviewscount / $three * 100;
        $two = LawfirmsReviews::where('post_id',$post_id)->where('rated_index','2')->count();
        // $two_perc = $reviewscount / $two * 100;
        $one = LawfirmsReviews::where('post_id',$post_id)->where('rated_index','1')->count();
        // $one_perc = $reviewscount / $one * 100;

        return response()->json([
            'status' => 200,
            'count' => $reviewscount,
            'five_perc' => $five,
            'four_perc' => $four,
            'three_perc' => $three,
            'two_perc' => $two,
            'one_perc' => $one,
            'posts' => $reviewsposts,
        ]);
    }

    public function get_token_eco(Request $request){
        $token = "qwergttyuiopasdfghjklzxcvbnm12345467890!@#$%^&*()";

        $token = str_shuffle($token);
        $token = substr($token, 4, 13);
        $ecocashname = $request->ecocashname;
        $ecocashsurname = $request->ecocashsurname;
        $ecocashnumber = $request->ecocashnumber;
        $lawfirm_id = $request->lawfirm_id;
        $price = $request->lawfirm_price;
        $paynow = new Paynow(
            '14927',
            '36f183ee-bdfe-4226-aff5-ed0d472904f0',
            'http://127.0.0.1:8000/gateways/paynow/update',
            'http://127.0.0.1:8000/return?gateway=paynow'
        );
        // $package = PriceControl::all();
        // $activePrice = $package[0];
        $payment = $paynow->createPayment('ORDER' . 'Ordernumber', 'pikigene01@gmail.com');

        $payment->add('Price', '1000');

        // $response = $paynow->sendMobile($payment, $ecocashnumber, 'ecocash');
        $response = $paynow->send($payment, '0782954717', 'ecocash');

        if ($response->success) {

        return response()->json([
           'status' => '200',
           'token' => $token,
           'message' => "Your successfully bought $price",

       ]);
    }else{
        return response()->json([
            'status' => '200',
            'token' => $token,
            'message' => "Your successfully bought $price",

        ]);
    }
    }
    public function withdraw_money(Request $request){

    }

    public function paymoney(Request $request){

        $request->validate([
            'phone_number' => 'required',
            'method' => 'required'
        ]);

        $paynow = new Paynow(
            $this->integration_id,
            $this->integration_key,
            'http://127.0.0.1:8000/gateways/paynow/update',
            'http://127.0.0.1:8000/return?gateway=paynow'
        );
        // $package = PriceControl::all();
        // $activePrice = $package[0];
        $payment = $paynow->createPayment('ORDER' . 'Ordernumber', $request->email);

        $payment->add('Price', $activePrice->subscription_price);

        $response = $paynow->sendMobile($payment, $request->input('phone_number'), $request->input('method'));
        if ($response->success) {
            //create transaction


            return response()->json([
                'success' => true,
                'message' => $response->instructions(),
                'Order' => $Order
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $response->error
            ]);
        }

    }


    public function checkPayment($id){

        $user = Auth::user();

        $paynow = new Paynow(
            $this->integration_id,
            $this->integration_key,
            'http://127.0.0.1:8000/gateways/paynow/update',
            'http://127.0.0.1:8000/return?gateway=paynow'
        );

        $transaction = Order::find($id);

        if ($transaction->poll_url == null){
            return response()->json([
                'success' => false,
                'message' => 'payment error'
            ], 217);
        }

        $status = $paynow->pollTransaction($transaction->poll_url);

        $response = $status->data();
        if ($status->paid()) {
            //check if the order was used before
            if($transaction->used){
                return response()->json([
                    'success' => false,
                    'message', 'this order was paid and used for an older subscription'
                ]);
            }else{
                //create or update a subscription
                if($user->subscription != null){
                    $today = Carbon::now();
                    if($user->subscription->expires_at > $today){
                        //its not yet expired but add more days
                        $subscription = Subscription::find($user->subscription->id);
                        $subscription->expires_at = $subscription->expires_at->addMonth(1);
                        $subscription->save();
                        $transaction->status = $response['status'];
                        $transaction->used = 1;
                        $transaction->save();
                        return response()->json([
                            'success' => true,
                            'status' => $response['status'],
                            'order'=>$transaction
                        ]);
                    }else{
                        //its expired just add a month

                        return response()->json([
                            'success' => true,
                            'status' => $response['status'],
                            'order'=>$transaction
                        ]);
                    }
                }else{
                    //create a new subscription

                    return response()->json([
                        'success' => true,
                        'status' => $response['status'],
                        'order'=>$transaction
                    ]);
                }
            }
        } else {
            $transaction->status = $response['status'];
            $transaction->save();
            return response()->json([
                'success' => false,
                'status' => $response['status'],
                'order'=>$transaction
            ]);
        }
    }


    // Save the response from paynow in a variable


    // PAYPAL INTERGRATION

    public function createTransaction()
    {
        return view('transaction');
    }

    /**
     * process transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function processTransaction(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('successTransaction'),
                "cancel_url" => route('cancelTransaction'),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => "1000.00"
                    ]
                ]
            ]
        ]);

        if (isset($response['id']) && $response['id'] != null) {

            // redirect to approve href
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return redirect()->away($links['href']);
                }
            }

            return redirect()
                ->route('createTransaction')
                ->with('error', 'Something went wrong.');

        } else {
            return redirect()
                ->route('createTransaction')
                ->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }

    /**
     * success transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function successTransaction(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            return redirect()
                ->route('createTransaction')
                ->with('success', 'Transaction complete.');
        } else {
            return redirect()
                ->route('createTransaction')
                ->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }

    /**
     * cancel transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancelTransaction(Request $request)
    {
        return redirect()
            ->route('createTransaction')
            ->with('error', $response['message'] ?? 'You have canceled the transaction.');
    }
}
