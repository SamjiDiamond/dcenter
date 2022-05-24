<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Plan;
use App\Models\SMSLog;
use App\Models\SmsPayment;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Checkout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Paystack;
use Stripe\Stripe;
use App\Mail\transactionMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class BillingController extends Controller
{
    public function index()
    {
        $plans = Plan::all();

        $sub=DB::table('subscriptions')->where('company_id', '=', auth()->user()->company_id)->exists();

        if($sub) {
            $sub = DB::table('subscriptions')->where('company_id', '=', auth()->user()->company_id)->first();
            $sub = $sub->plan_id;
        }else{
            $sub='ntn';
        }
        return view('billing', compact('plans', 'sub'));
    }

    public function show(Plan $plan, Request $request)
    {
        if ($request->user()->subscribedToPlan($plan->paystack_plan, 'main')) {
            return redirect()->route('home')->with('message', 'You have already subscribed the plan');
        }
        return view('show', compact('plan'));
    }

    public function invoice(Plan $plan, Request $request)
    {
        $reference = substr(md5(time()), 0, 10);
        $sub=DB::table('subscriptions')->where('company_id', '=', auth()->user()->company_id);

        if($sub->exists() && $sub->pluck('plan_status') == "Active") {
            
            return redirect()->route('plans')->with('message', 'You have already subscribed to a plan.');
        }

      /*  if($sub) {
            $sub = DB::table('subscriptions')->where('company_id', '=', auth()->user()->company_id)->first();

//        if($company->subscribedToPlan($plan, 'user_sub')) {
            if ($plan->id == $sub->plan_id) {
                return redirect()->route('plans')->with('message', 'You have already subscribed the plan');
            }
        }

        */

        $company = Company::where('id', '=', Auth::user()->company_id)->first();
        $others = User::where([['company_id', '=', Auth::user()->company_id],])->get();

        session(['subplan' => $plan->paystack_plan]);

        
        return view('invoice_usersub', compact('plan', 'company', 'others', 'reference'));
    }

    /**
     * Redirect the User to Paystack Payment Page
     * @return Url
     */
    public function redirectToGateway(Request $request)
    {
    
        $reference = substr(md5(time()), 0, 10);

        $request->all();
    
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.korapay.com/merchant/api/v1/charges/initialize',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "reference": "' . $reference . '",
            "amount": "'. $request->amount . '",
            "currency": "NGN",
            "redirect_url": "http://localhost:8000/verify/'.$reference .'",
                "customer": {
                    "name": "'. $request->first_name .' ' .$request->last_name . '",
                    "email": "' .$request->email .'"
                }
            }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . env('KORAPAY_SECRET'),
              ),
        ));


        $response = curl_exec($curl);

        curl_close($curl);
        $rep=json_decode($response, true);

      
        
        if($rep && $rep['status']== true) {
            $payout_link = $rep['data']['checkout_url'];
                //save details to virtual_account table
                $checkout = new Checkout();
                $checkout->company_id = auth()->user()->company_id;
                $checkout->reference_id = $rep['data']['reference'];
                $checkout->plan_id = $request->plan;
                $checkout->order_id = $request->orderID;
                $checkout->amount = $request->amount;
                $checkout->quantity = $request->quantity;
                $checkout->currency = $request->currency;
                $checkout->status =  $rep['status'];
                
                    if($checkout->save()){

                        $subscription = new Subscription();
                        $subscription->company_id = auth()->user()->company_id;
                        $subscription->checkout_id = $checkout->id;
                        $subscription->plan_id =  $checkout->plan_id;
                        
                        $subscription->save();

                        $user = auth()->user();

                        Mail::to($user)->send(new transactionMail($user, $checkout));
        
                        return redirect($payout_link);

                    }else{
                        return redirect()->back()->with(['message' => 'Subscription was unsuccessful! ']);
                    }
                     

        }else{
            return redirect()->back()->with('message',  'Payment not Successful ! An Error occurred!  ');
        }

       
        
//        try {
        //return dd($)
        //Paystack::getAuthorizationUrl()->redirectNow();
//        } catch (\Exception $e) {
//            return Redirect::back()->withMessage(['msg' => 'The paystack token has expired. Please refresh the page and try again.', 'type' => 'error']);
//        }
    }

     /**
     * Verify Payment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function VerifyPayment($ref){
        if(Auth::check()){
            $curl = curl_init();
       
            curl_setopt_array($curl, array(
              CURLOPT_URL => "https://api.korapay.com/merchant/api/v1/charges/$ref",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
              CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . env('KORAPAY_SECRET'),
                 ),
            ));
            
            $response = curl_exec($curl);
     
             curl_close($curl);
             $rep=json_decode($response, true);
     
             if($rep['status']== true) {
                 $checkout = Checkout::where('reference_id', $rep['data']['reference']);
                 $checkout->update([
                     'status' => $rep['data']['status']
                 ]);


                 if($checkout->pluck('status')->first() == "success" ){
                     $subscription = Subscription::where('checkout_id', $checkout->pluck('id')->first());
                     $plan_id = $subscription->pluck('plan_id')->first();

                   

                    $plan_end_date = '';

                    if($plan_id == 1  ){
                        $plan_end_date =  Carbon::now()->addDay();
                    }
                    else{
                        $plan_end_date =  Carbon::now()->addDays(30);
                        
                    }

                     $subscription->update([
                         'plan_start_date' => Carbon::now(),
                         'plan_end_date' => $plan_end_date ,
                         'plan_status' => 'active'
                     ]);
                 

                 if($subscription->pluck('plan_status')->first() == "Active"){
                    return redirect('/dashboard')->with('message', 'Subscription Activated Successfully!');
                 }else{

                    return redirect('/dashboard')->with('message', 'Subscription Activation in Progress!');

                 }

                }
                return redirect('/dashboard')->with('message', 'Subscription Activation Unsuccessful');

                 
             }
            
        }
       // dd($reference);
     


    }



    /**
     * Obtain Paystack payment information
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleGatewayCallback()
    {
        //$paymentDetails = Paystack::getPaymentData();

//        dd($paymentDetails);
        // Now you have the payment details,
        // you can store the authorization_code in your db to allow for recurrent subscriptions
        // you can then redirect or do whatever you want

        if ($paymentDetails['data']['status'] == "success") {
            $comp = Company::find(Auth::user()->company_id);

            // Accepts an card authorization authtoken for the customer
//            $comp->newSubscription('main', session('subplan'))->create($paymentDetails['data']['authorization']['authorization_code']);
            $comp->newSubscription('main', session('subplan'))
                ->trialDays(10)
                ->create($paymentDetails['data']['authorization']['authorization_code']);
            return redirect()->route('plans')->with('success', 'Your subscription is successful');
        }

        return redirect()->route('plans')->with('danger', 'Something is wrong with the subscription.');
    }

    public function create(Request $request)
    {
        $input = $request->all();

        $plan = Plan::find($input['plan']);
        $company = Company::find($input['company']);

        Paystack::getAuthorizationUrl()->redirectNow();

        return true;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/".$input['reference'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer ".env("PAYSTACK_SECRET_KEY"),
                "Cache-Control: no-cache",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }

        return;

//        if($company->subscribedToPlan($plan->stripe_plan, 'user_sub')) {
//            return redirect()->route('home')->with('success', 'You have already subscribed to this plan');
//        }

        $sub=Subscription::where('company_id', '=', auth()->user()->company_id)->exists();

        if($sub) {
            $sub = Subscription::where('company_id', '=', auth()->user()->company_id)->first();

            if ($plan->stripe_plan == $sub->stripe_plan) {
                return redirect()->route('plans')->with('success', 'You have already subscribed the plan');
            }
        }

//        Stripe::setApiKey(env('STRIPE_SECRET'));
//
//        $company->createAsStripeCustomer();

        if ($plan->slug == "daily") {
            $company->newSubscription("user_sub", $plan->stripe_plan)
                ->trialDays(1)
                ->create('pm_card_visa');
        }else{
            $company->newSubscription("user_sub", $plan->stripe_plan)
                ->create('pm_card_visa');
        }


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/subscription",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>"{\"customer\": \".$company->email.\", \"plan\": \".$plan->stripe_plan.\"}",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;

        $respons=json_decode($response, true);

        $sub_code=$respons['data']['subscription_code'];
        $email_token=$respons['data']['email_token'];

        $subi=Subscription::where('company_id', '=', auth()->user()->company_id)->orderBy('id', 'desc')->first();
        $subi->update(['paystack_reference'=>$input['reference'], 'subscription_code'=>$sub_code, 'email_token'=>$email_token]);


//        $plan = Plan::find($input['plan'])->name;

//       $user = User::find(Auth::id());
        /*
                $user->newSubscription($plan->name, $plan->stripe_id)
                    ->trialDays(10)
                    ->create('pm_card_visa');*/



       /* $user=User::where([['company_id', '=', Auth::user()->company_id], ['paystack','!=',''] ])->first();
        if($user){

        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/subscription",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>"{\"customer\": \".$user->paystack_id.\", \"plan\": \".$plan->stripe_plan.\"}",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;


        $users=User::where([['company_id', '=', Auth::user()->company_id], ])->get();
        foreach ($users as $user) {
            $user->newSubscription("user_sub", $input['plan'])
                ->create('pm_card_visa');
        }*/

        /*$request->user()
            ->newSubscription("user_sub", $input['plan'])
            ->create('pm_card_visa');*/

        return redirect()->route('home')->with('success', 'Your plan has been subscribed successfully');
    }

    public function create2(Request $request, Plan $plan)
    {
        if($request->user()->subscribedToPlan($plan->stripe_plan, 'main')) {
            return redirect()->route('home')->with('success', 'You have already subscribed the plan');
        }
        $plan = Plan::findOrFail($request->get('plan'));

        $request->user()
            ->newSubscription('main', $plan->stripe_plan)
            ->create($request->stripeToken);

        return redirect()->route('home')->with('success', 'Your plan subscribed successfully');
    }

    public function showsub(){
        if(auth()->user()->company_id==1) {
            $data = Subscription::get();
        }else{
            $data = Subscription::where('company_id', '=', auth()->user()->company_id)
                ->get();
        }

        return view('user_subscriptions', ['datas' =>$data, 'i'=>1]);
    }

    public function cancelsub(Request $request)
    {
//        $user = $request->user();

        $input = $request->all();

        $sub=Subscription::find($input['id']);
        $com=Subscription::find($sub->id);

            $com->subscription('user_sub')->cancel();

        $curl = curl_init();


        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/subscription/disable",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>"{\"code\": \".$sub->subscription_code.\", \"token\": \".$sub->email_token.\"}",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;

        $respons=json_decode($response, true);

        $sub_status=$respons['status'];

        if($sub_status){
            return redirect('subscriptions')->with(['success' => 'Subscription cancelled successfully.']);

        }else{
            return redirect('subscriptions')->with(['success' => 'Subscription ended with error.']);
        }

    }

    public function enablesub(Request $request)
    {
//        $user = $request->user();

        $input = $request->all();

        $sub=Subscription::find($input['id']);
        $com=Subscription::find($sub->id);

        if (!$com->subscription('user_sub')->onGracePeriod()) {
            return redirect('subscriptions')->with(['success' => 'Subscription can not be enabled, kindly make a new subscription.']);
        }

            $com->subscription('user_sub')->cancel();

        $curl = curl_init();


        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/subscription/disable",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>"{\"code\": \".$sub->subscription_code.\", \"token\": \".$sub->email_token.\"}",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;

        $respons=json_decode($response, true);

        $sub_status=$respons['status'];

        if($sub_status){
            return redirect('subscriptions')->with(['success' => 'Subscription cancelled successfully.']);

        }else{
            return redirect('subscriptions')->with(['success' => 'Subscription ended with error.']);
        }

    }

    public function invoices( Request $request)
    {
        $input = $request->all();

        $company=Company::find($input['company']);
        $invoices = $company->invoices();
// Includes pending invoices in the results...
        $invoices = $company->invoicesIncludingPending();

        if(auth()->user()->company_id==1) {
            $companys = Company::get();
        }else{
            $companys = Company::where('id', '=', auth()->user()->company_id)->get();
        }

        return view('invoices', ['invoices' =>$invoices, 'companys'=>$companys, 'i'=>1]);

    }

    public function updateSubscription(Request $request)
    {
        $user = $request->user();

        // get the plan
        $plan = $request->input('plan');

        // if a user is cancelled
        if ($user->subscribed('main') and $user->subscription('main')->onGracePeriod()) {

            if ($user->onPlan($plan)) {
                // resume the plan
                $user->subscription('main')->resume();
            } else {
                // resume and switch plan
                $user->subscription('main')->resume()->swap($plan);
            }

            // if not cancelled, and switch
        } else {
            // change the plan
            $user->subscription('main')->swap($plan);
        }

        return redirect('account')->with(['success' => 'Subscription updated.']);
    }

    public function sms_payment(Request $request)
    {

        $input = $request->all();
        $rules = array(
            'unit_price' => 'required',
            'unit_purchased' => 'required',
            'total_amount' => 'required',
            'reference' => 'required',
        );

        $messages = array(
            'min' => 'Hmm, that looks short.',
            'max' => 'Oops, that too long.',
            'alpha_num'  => 'Use alphabet or alphabet with numbers to secure your password.');

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->passes()) {
            try {
                $input['company_id']=auth()->user()->company_id;
                $input['user_id']=auth()->id();

                SmsPayment::create($input);

                $comp=Company::find(auth()->user()->company_id);
                $comp->sms_balance += $input['unit_purchased'];
                $comp->save();

                return redirect()->route('sms.payment')->with('success', $input['unit_purchased'] . ' SMS unit purchase successful');
            }catch(\Exception $e){
                DB::rollback();
                return redirect()->route('sms.payment')->with('error','SMS unit purchase ended with error');
            }
        }else{
            DB::rollback();
            return redirect()->route('sms.payment')->with('error','Error purchasing units, check your input and try again');
        }
    }

    public function sms_payments(){
        if(auth()->user()->company_id==1) {
            $data =SmsPayment::join("company", "company.id", "=", "sms_payments.company_id")
                ->join("users", "users.id", "=", "sms_payments.user_id")
                ->select('sms_payments.*', 'company.name as company', 'users.first_name', 'users.last_name' )
                ->get();
        }else{
            $data =SmsPayment::join("company", "company.id", "=", "sms_payments.company_id")
                ->join("users", "users.id", "=", "sms_payments.user_id")
                ->select('sms_payments.*', 'company.name as company', 'users.first_name', 'users.last_name' )
                ->where('company.id','=',auth()->user()->company_id)
                ->get();
        }

        return view('sms_payments', ['datas' =>$data, 'i'=>1]);
    }

    public function sms_transactions(){
        if(auth()->user()->company_id==1) {
            $data = SMSLog::join("company", "company.id", "=", "smslog.company_id")
                ->join("users", "users.id", "=", "smslog.user_id")
                ->select('smslog.*', 'company.name as company', 'users.first_name', 'users.last_name' )
                ->get();
        }else{
            $data = SMSLog::join("company", "company.id", "=", "smslog.company_id")
                ->join("users", "users.id", "=", "smslog.user_id")
                ->select('smslog.*', 'company.name as company', 'users.first_name', 'users.last_name')
                ->whrere('company.id', '=', auth()->user()->company_id)
                ->get();
        }

        return view('sms_transactions', ['datas' =>$data, 'i'=>1]);
    }
}
