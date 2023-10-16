<?php

namespace App\Actions\Fortify;

use App\Models\BouncerRoleModel;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Bouncer;
use Illuminate\Support\Facades\Auth;
use App\Models\VirtualAccount;
use App\Mail\newAccountMail;
use App\Models\Subscription;
use Illuminate\Support\Facades\Mail;

use Carbon\Carbon;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
       
        if (!App::environment(['local', 'staging'])) {
            Validator::make($input, [
                'first_name' => ['required', 'string', 'max:20'],
                'last_name' => ['required', 'string', 'max:20'],
                'email' => ['required', 'string', 'max:30', 'email:rfc,dns', 'unique:users'],
                'company_name' => ['required', 'string', 'max:50', 'unique:company,name'],
                'company_email' => ['required', 'string', 'max:50', 'email:rfc,dns', 'unique:company,email'],
                'bank_code' => ['required'],
                'password' => $this->passwordRules(),
            ])->validate();
        }else{
            Validator::make($input, [
                'first_name' => ['required', 'string', 'max:20'],
                'last_name' => ['required', 'string', 'max:20'],
                'email' => ['required', 'string', 'max:30', 'unique:users'],
                'company_name' => ['required', 'string', 'max:50', 'unique:company,name'],
                'company_email' => ['required', 'string', 'max:50', 'unique:company,email'],
                'bank_code' => ['required'],
                'password' => $this->passwordRules(),
            ])->validate();
        }
        
                //validating bank code
               $comp = new Company;

   
               //create company account
               $comp=$input;
               $comp['email']=$input['company_email'];
               $comp['name']=$input['company_name'];
               $comp['trial_ends_at']=now()->addDays(3);
               $company=Company::create($comp);

               //create role for ceo
               Bouncer::useRoleModel(BouncerRoleModel::class);
               $role = Bouncer::role()->firstOrCreate([
                   'name' => 'ceo',
                   'title' => 'The owner of the business or company',
                   'company_id' => $company->id,
               ]);

               //assign all abilities
               $role->allow()->everything();

               //create admin account
               $user=User::create([
                   'first_name' => $input['first_name'],
                   'last_name' => $input['last_name'],
                   'email' => $input['email'],
                   'phoneno' => $input['phoneno'],
                   'company_id' => $company->id,
                   'account_type' => 'admin',
                   'password' => Hash::make($input['password']),
                   'referral_id' => auth()->user()->id
               ]);

               //assign role to user
               $user->assign(6);


               //Create subscription for company
               $comp = Company::find($company->id);
               $plan_name ="standard"; // Paystack plan name e.g default, main, yakata
               $plan_code ="PLN_wyvsjtd8dhou7ix"; // Paystack plan code  e.g PLN_gx2wn530m0i3w3m

       // The customer's most recent authorization would be used to charge subscription
       //        $comp->newSubscription($plan_name, $plan_code)->create();
       // Initialize a new charge for a subscription
            //    $comp->newSubscription($plan_name, $plan_code)->charge();


               //Korapay
            $reference = substr(md5(time()), 0, 10);
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://api.korapay.com/merchant/api/v1/virtual-bank-account',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>'{
                "account_name": "'. $input['first_name'] . " " . $input['last_name'] .  '",
                "account_reference": "' . $reference . '",
                "permanent": true,
                "bvn": ["'. $input['bvn'] .'"],
                "bank_code": "035",
                "customer": {
                    "name": "'. $input['first_name'] . " " . $input['last_name'] .  '"
                }
            }',
              CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . env('KORAPAY_SECRET'),
                'Content-Type: application/json',
               
              ),
            ));
            
            $response = curl_exec($curl);
            
            curl_close($curl);
            
            $rep=json_decode($response, true);
            if($rep['status']== true) {
                    //save details to virtual_account table
                    $virtual_account = new VirtualAccount();
                    $virtual_account->account_name = $input['first_name']  ." " . $input['last_name'];
                    $virtual_account->user_id = $user->id;
                    $virtual_account->account_reference =  $reference ;
                    $virtual_account->permanent = true;
                    $virtual_account->bvn =  $input['bvn'];
                    $virtual_account->bank_code = "035";
                    
                    $virtual_account->save();    


                    $trialSub = new Subscription();
                    $trialSub->company_id = $company->id;
                    $trialSub->trial_start_date = Carbon::now();
                    $trialSub->trial_end_date =   Carbon::now()->addDays(3);
                    $trialSub->trial_status = "activated";

                    $trialSub->save();

                }


            // }else{
            //     dd('here');
            //     return redirect()->back()->with(['erorr' => 'error with account details ']);
            // }

            Mail::to($user)->send(new newAccountMail($user));

            return $user;
   
        
            

            /*$curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => env('MONNIFY_url') . 'sub-accounts',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '[
    {
        "currencyCode": "NGN",
        "bankCode": "' . $input['bank_code'] . '",
        "accountNumber": "' . $input['bank_account'] . '",
        "email": "' . env('ADMINEmail') . '",
        "defaultSplitPercentage": "100"
    }
]',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Basic ' . env('MONNIFY_basicAuth'),
                    'Cookie: __cfduid=d56a91752424a1013ce3e810ed3c192481612902071'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $rep=json_decode($response, true);

            if($rep['responseCode']==0) {
                $input['Monnify_subAccountCode'] = $rep['responseBody'][0]['subAccountCode'];
            }else{
                return back()->with(['error'=>'Error with account details']);
            }
        }else{
            $input['Monnify_subAccountCode'] = 'MFY_SUB_179344387755';
        }
        */

   

        

    }
}
