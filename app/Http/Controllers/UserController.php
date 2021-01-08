<?php


namespace App\Http\Controllers;


use App\Models\Company;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use DB;
use Hash;
use Stripe\Stripe;
use Bouncer;
use Silber\Bouncer\Database\Ability;
use Silber\Bouncer\Database\Role;


class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
//        $this->middleware('subware');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(auth()->user()->company_id==1){
            $users = User::join("assigned_roles","assigned_roles.entity_id", "=","users.id")
                ->join("roles","roles.id", "=","assigned_roles.role_id")
                ->join("company", "company.id","=","users.company_id")
                ->select('users.*', 'roles.name as role', 'company.name as company')
                ->where('users.account_type','=','admin')
                ->orderBy('id','DESC')->get();
            $roles = Role::get();
        }else{
            $users = User::join("assigned_roles","assigned_roles.entity_id", "=","users.id")
                ->join("roles","roles.id", "=","assigned_roles.role_id")
                ->join("company", "company.id","=","users.company_id")
                ->select('users.*', 'roles.name as role', 'company.name as company')
                ->where('users.account_type','=','admin')
                ->where('users.company_id','=',auth()->user()->company_id)
                ->orderBy('id','DESC')->get();
            $roles = Role::where('company_id','=',auth()->user()->company_id)->get();
        }




        return view('admins', ['users' => $users, 'roles' =>$roles, 'i'=>1]);
    }

    public function orderPost(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $input = $request->all();
        $token = $input['stripeToken'];

        try {
         /*   $user->subscription($input['plane'])->create($token,[
                'email' => $user->email
            ]);*/
            $user->newSubscription('default', $input['plan'])->create($token, [
                'email' => $user->email
            ]);
            return back()->with('success','Subscription is completed.');
        } catch (Exception $e) {
            return back()->with('success',$e->getMessage());
        }

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $rules = array(
            'last_name' => 'required|min:3',
            'first_name' => 'required|min:3',
            'email' => 'required|email',
            'role_id' => 'required',
            'phoneno' => 'required|min:11|max:16'
        );

        $messages = array(
            'min' => 'Hmm, that looks short.',
            'max' => 'Oops, that too long.',
            'alpha_num'  => 'Use alphabet or alphabet with numbers to secure your password.');


        $validator = Validator::make($input, $rules, $messages);

        if ($validator->passes()) {
            try {
            DB::beginTransaction();

                $input = $request->all();
                $input['company_id'] = auth()->user()->company_id;
                $input['account_type'] = "admin";
//                $input['trial_ends_at'] = now()->addDays(1);

                $company=Company::where('id', '=', $input['company_id'])->first();

                $u=User::where('email','=', $input['email'])->exists();
                if($u){
                    $user=User::where('email','=', $input['email'])->first();
                    if($user->acount_type!=$input['account_type']){
                        $user->update($input);
                    }else{
                        return redirect()->route('admin.list')->with('error','Admin already exist');
                    }

                }else{
                    $input['password'] = Hash::make("12345");
                    $user = User::create($input);


                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => env('MONNIFY_url')."/api/v1/auth/login",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_HTTPHEADER => array(
                            "Authorization: Basic ".env('MONNIFY_basicAuth')
                        ),
                    ));

                    $response = curl_exec($curl);

                    curl_close($curl);
//                    echo "<br /><br />".$response;

                    $respons=json_decode($response, true);
                    $monToken=$respons['responseBody']['accessToken'];


                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => env('MONNIFY_url')."/api/v1/bank-transfer/reserved-accounts",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS =>"{ \"accountReference\": \"".env('MONNIFY_contractCode').$user->id."\", \"accountName\": \"".$input['last_name'] . $input['first_name']."\", \"currencyCode\": \"NGN\",  \"contractCode\": \"".env('MONNIFY_contractCode')."\", \"customerEmail\": \"".$input['email']."\", \"incomeSplitConfig\": [  { \"subAccountCode\": \"".$company->Monnify_subAccountCode."\", \"feePercentage\": 100,  \"splitPercentage\": 100, \"feeBearer\": true } ]}",
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json",
                            "Authorization: Bearer ".$monToken,
                        ),
                    ));

                    $response = curl_exec($curl);

                    curl_close($curl);
//                    echo "<br /><br />".$response;

                    $respons=json_decode($response, true);

                    $user->monnify_id=$respons['responseBody']['accountReference'];
                    $user->accountno=$respons['responseBody']['accountNumber'];

//                    $user->accountno='2000003152';
//                    $user->monnify_id='692568425636';

                    $user->save();
                }

                $user->assign($request->input('role_id'));

//                Stripe::setApiKey(env('STRIPE_SECRET'));
//
//                $user->createAsStripeCustomer();

                //create paysack account
                /*$curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.paystack.co/customer",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => "{\"email\": \"".$input['email']."\"}",
                    CURLOPT_HTTPHEADER => array(
                        "Content-Type: application/json",
                        "Authorization: Bearer ". env('PAYSTACK_SECRET')
                    ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
//                echo "<br /><br />".$response;

                $respons=json_decode($response, true);*/

                $user->paystack_id='CUS_80vqmpvqwyu3orj';
                $user->save();
                //end paystack creation

            DB::commit();

               /* return view('update-payment-method', [
                    'intent' => $user->createSetupIntent()
                ]);*/

                // Create the Payment Method
             /*   $paymentMethod = $user->paymentMethods()->create([
                    'type' => 'card',
                    'card' => [
                        'number'    => '4242424242424242',
                        'exp_month' => 10,
                        'cvc'       => 314,
                        'exp_year'  => 2025,
                    ],
                ]);

                $user->newSubscription('prod_Giry4d4Q3treMT', 'monthly')->create($paymentMethod->id);*/

                return redirect()->route('admin.list')->with('success','Admin created successfully');
            }catch(\Exception $e){
                DB::rollback();
                return redirect()->route('admin.list')->with('error','Error creating Admin');
            }
        }else{
            DB::rollback();
            return redirect()->route('admin.list')->with('error','Error creating Admin, check your input and try again');
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $use = User::join("assigned_roles","assigned_roles.entity_id", "=","users.id")
//            ->join("roles","roles.id", "=","assigned_roles.role_id")
            ->where("users.id", "=", $id)
            ->select("users.*", "assigned_roles.role_id")
            ->first();

        if(auth()->user()->company_id==1){
            $users = User::join("assigned_roles","assigned_roles.entity_id", "=","users.id")
                ->join("roles","roles.id", "=","assigned_roles.role_id")
                ->join("company", "company.id","=","users.company_id")
                ->select('users.*', 'roles.name as role', 'company.name as company')
                ->where('users.account_type','=','admin')
                ->orderBy('id','DESC')->get();
            $roles = Role::get();
        }else{
            $users = User::join("assigned_roles","assigned_roles.entity_id", "=","users.id")
                ->join("roles","roles.id", "=","assigned_roles.role_id")
                ->join("company", "company.id","=","users.company_id")
                ->select('users.*', 'roles.name as role', 'company.name as company')
                ->where('users.account_type','=','admin')
                ->where('users.company_id','=',auth()->user()->company_id)
                ->orderBy('id','DESC')->get();
            $roles = Role::where('company_id','=',auth()->user()->company_id)->get();
        }

        return view('admins', ['users' => $users, 'roles' =>$roles, 'use'=> $use, 'edit'=>true , 'i'=>1]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'last_name' => 'required|min:3',
            'first_name' => 'required|min:3',
            'role_id' => 'required',
            'phoneno' => 'required|min:11|max:16'
        ]);


        $input = $request->all();

//        array_shift($input);

        $user = User::where('id', $id)->update(['last_name' => $input['last_name'], 'first_name' => $input['first_name'], 'phoneno' => $input['phoneno'] ]);

        $rt=DB::table('assigned_roles')->where('entity_id', '=', $id)->update(['role_id'=>$input['role_id']]);


        return redirect()->route('admin.list')
            ->with('success','Admin updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
            ->with('success','User deleted successfully');
    }

    /**
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function disable($id)
    {
        $user=User::where('id', '=', $id)->update(['status'=>'disable']);
        $user=User::find($id);
        // cancel the subscription
//        $user->subscription('user_sub')->cancel();

        return redirect()->route('admin.list')
            ->with('success','Admin disabled successfully');
    }

    /**
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function enable($id)
    {
        $user=User::where('id', '=', $id)->update(['status'=>'active']);
        $user=User::find($id);
        // enable subscription
        // resume the plan
//        $user->subscription('user_sub')->resume();

        return redirect()->route('admin.list')
            ->with('success','Admin enabled successfully');
    }

    public function userslist(Request $request)
    {
        if(auth()->user()->company_id==1){
            $users = User::join("company","company.id", "=","company_id")
                ->select('users.*', 'company.name as company')
//                ->where('company_id','=',auth()->user()->company_id)
                ->orderBy('id','DESC')->get();
        }else{
            $users = User::join("company","company.id", "=","company_id")
                ->select('users.*', 'company.name as company')
                ->where('users.company_id','=',auth()->user()->company_id)
                ->orderBy('id','DESC')->get();
        }


        return view('users', ['users' => $users, 'i'=>1]);
    }

    public function userdetails($id){
        $user=User::where('users.id','=',$id)->exists();
        if(!$user){
            return view('errors.404');
        }

        $user = User::join("company","company.id", "=","users.company_id")
            ->join("assigned_roles","assigned_roles.entity_id", "=","users.id")
            ->join("roles","roles.id", "=","assigned_roles.role_id")
//            ->join("roles", "roles.id", "=","users.role_id")
            ->select('users.*', 'company.name as company', 'roles.name as roles')
            ->where('users.id','=',$id)->first();

        if($user->company_id!=auth()->user()->company_id && auth()->user()->company_id!=1){
            return view('errors.404');
        }

        $transactions=Transaction::where('user_id', '=', $id)->get();

        $data_order=Transaction::where([['user_id','=',$id], ['type', '=', 'data']])->count();
        $tv_order=Transaction::where([['user_id','=',$id], ['type', '=', 'tv']])->count();
        $airtime_order=Transaction::where([['user_id','=',$id], ['type', '=', 'airtime']])->count();
        $transfer_order=Transaction::where([['user_id','=',$id], ['type', '=', 'transfer']])->count();
        $electricity_order=Transaction::where([['user_id','=',$id], ['type', '=', 'electricity']])->count();

        return view('user', ['user' => $user, 'transactions'=>$transactions, 'data'=>$data_order, 'tv'=>$tv_order, 'airtime'=>$airtime_order, 'transfer'=>$transfer_order, 'electricity'=>$electricity_order, 'i'=>1]);
    }

    public function userenable($id)
    {
        $user=User::where('id', '=', $id)->update(['status'=>'active']);

        return redirect()->route('user.list')
            ->with('success','User enabled successfully');
    }

    public function userdisable($id)
    {
        $user=User::where('id', '=', $id)->update(['status'=>'disable']);

        return redirect()->route('user.list')
            ->with('success','User disabled successfully');
    }

    public function useredit($id){
        $user=User::where('users.id','=',$id)->exists();
        if(!$user){
            return view('errors.404');
        }

        $user = User::join("company","company.id", "=","users.company_id")
            ->join("assigned_roles","assigned_roles.entity_id", "=","users.id")
            ->join("roles","roles.id", "=","assigned_roles.role_id")
//            ->join("roles", "roles.id", "=","users.role_id")
            ->select('users.*', 'company.name as company', 'roles.name as roles')
            ->where('users.id','=',$id)->first();

        if($user->company_id!=auth()->user()->company_id && auth()->user()->company_id!=1){
            return view('errors.404');
        }

        return view('user_edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function userupdate(Request $request, $id)
    {
        $this->validate($request, [
            'last_name' => 'required|min:3',
            'first_name' => 'required|min:3',
            'email' => 'required',
            'phoneno' => 'required|min:11|max:16'
        ]);

        $input = $request->all();

        $v=User::where('email', $input['email'])->first();

        if($v->id!=$id){
            return redirect()->back()->with('error', 'Email Address belongs to another user');
        }

        $v=User::where('phoneno', $input['phoneno'])->first();

        if($v->id!=$id){
            return redirect()->back()->with('error', 'Phone Number belongs to another user');
        }



        array_shift($input);

        $user = User::where('id', $id)->update($input);

        return redirect()->route('user.list')
            ->with('success', $input['last_name'].' profile updated successfully');
    }

}
