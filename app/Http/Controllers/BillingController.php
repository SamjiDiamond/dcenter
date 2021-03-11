<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Plan;
use App\Models\SMSLog;
use App\Models\SmsPayment;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Stripe\Stripe;
use Paystack;

class BillingController extends Controller
{
    public function index()
    {
        $plans = Plan::all();

        $sub=DB::table('subscriptions')->where('company_id', '=', auth()->user()->company_id)->exists();

        if($sub) {
            $sub = DB::table('subscriptions')->where('company_id', '=', auth()->user()->company_id)->first();
            $sub=$sub->stripe_plan;
        }else{
            $sub='ntn';
        }
        return view('billing', compact('plans', 'sub'));
    }

    public function show(Plan $plan, Request $request)
    {
        if($request->user()->subscribedToPlan($plan->stripe_plan, 'user_sub')) {
            return redirect()->route('home')->with('success', 'You have already subscribed the plan');
        }
        return view('show', compact('plan'));
    }

    public function invoice(Plan $plan, Request $request)
    {
        $sub=DB::table('subscriptions')->where('company_id', '=', auth()->user()->company_id)->exists();

        if($sub) {
            $sub = DB::table('subscriptions')->where('company_id', '=', auth()->user()->company_id)->first();

//        if($company->subscribedToPlan($plan, 'user_sub')) {
            if ($plan->stripe_plan == $sub->stripe_plan) {
                return redirect()->route('plans')->with('success', 'You have already subscribed the plan');
            }
        }

        $company=Company::where('id', '=', Auth::user()->company_id)->first();
        $others=User::where([['company_id', '=', Auth::user()->company_id], ])->get();

        return view('invoice_usersub', compact('plan', 'company', 'others'));
    }

    public function create(Request $request)
    {
        $input = $request->all();

        $plan = Plan::find($input['plan']);
        $company = Company::find($input['company']);


        return Paystack::getAuthorizationResponse($input['reference']);

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
            $data =Company::join("subscriptions", "company.id", "=", "subscriptions.company_id")
                ->select('subscriptions.*', 'company.name as company' )
                ->get();
        }else{
            $data =Company::join("subscriptions", "company.id", "=", "subscriptions.company_id")
                ->select('subscriptions.*', 'company.name as company' )
                ->where('company.id','=',auth()->user()->company_id)
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
            $data =SMSLog::join("company", "company.id", "=", "smslog.company_id")
                ->join("users", "users.id", "=", "smslog.user_id")
                ->select('smslog.*', 'company.name as company', 'users.first_name', 'users.last_name' )
                ->where('company.id','=', auth()->user()->company_id)
                ->get();
        }

        return view('sms_transactions', ['datas' =>$data, 'i'=>1]);
    }
}
