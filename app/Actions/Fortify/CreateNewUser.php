<?php

namespace App\Actions\Fortify;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Bouncer;

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
        Validator::make($input, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
        ])->validate();

        $input['Monnify_subAccountCode'] = 'MFY_SUB_179344387755';
        $input['bank_account_name'] ='ODEJINMI TOLUWALOPE ABRAHAM';
        $input['personal_email']=$input['email'];
        $input['email']=$input['company_email'];
        $input['name']=$input['company_name'];
        $input['bank_code']="058";
        $input['trial_ends_at']=now()->addDays(7);
        $company=Company::create($input);

        $role = Bouncer::role()->firstOrCreate([
            'name' => 'ceo',
            'title' => 'The owner of the business or company',
            'company_id' => $company->id,
        ]);
        $role->allow()->everything();


//        $comp = Company::find($company->id);
//        $plan_name ="standard"; // Paystack plan name e.g default, main, yakata
//        $plan_code ="PLN_wyvsjtd8dhou7ix"; // Paystack plan code  e.g PLN_gx2wn530m0i3w3m
//
//// The customer's most recent authorization would be used to charge subscription
////        $comp->newSubscription($plan_name, $plan_code)->create();
//// Initialize a new charge for a subscription
//        $comp->newSubscription($plan_name, $plan_code)->charge();

        $user=User::create([
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'email' => $input['personal_email'],
            'phoneno' => $input['phoneno'],
            'company_id' => $company->id,
            'account_type' => 'admin',
//            'paystack_id' => $data['paystack_id'],
//            'monnify_id' => $data['monnify_id'],
//            'accountno' => $data['accountno'],
            'password' => Hash::make($input['password']),
        ]);

        $user->assign(6);

        return $user;

    }
}
