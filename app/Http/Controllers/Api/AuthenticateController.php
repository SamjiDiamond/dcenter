<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use App\Models\User;
use Illuminate\Validation\ValidationException;

class AuthenticateController extends Controller
{
    public function signup(Request $request)
    {
        $input = $request->all();
        $rules = array(
            'first_name'   => 'required|min:3',
            'last_name'   => 'required|min:3',
            'email'      => 'required|email',
            'password' => 'required|min:6|max:20',
            'phoneno' => 'required|min:11|max:11',
            'company_id' => 'required');

        $messages = array(
            'min' => 'Hmm, that looks short.',
            'max' => 'Oops, that too long.',
            'alpha_num'  => 'Use alphabet or alphabet with numbers to secure your password.');


        $validator = Validator::make($input, $rules, $messages);

        if ($validator->passes())
        {
            try
            {
                DB::beginTransaction();
                $input['first_name'] = ucfirst($input['first_name']);
                $input['last_name'] = ucfirst($input['last_name']);
                $input['password'] = Hash::make($input['password']);
                $input['status'] = "active";
                $input['role'] = "user";
                $input['referral_id'] = $input['referral'];
                $input['account_type'] = "admin";

                $email=User::where('email', $input['email'])->exists();
                if($email){
                    return response()->json(['status' => 0, 'message' => 'Email has been taken']);
                }

                $phone=User::where('phoneno', $input['phoneno'])->exists();
                if($phone){
                    return response()->json(['status' => 0, 'message' => 'Phone number has been taken']);
                }

                if($input['referral']!=null){
                    $referral=User::where('phoneno', $input['referral'])->exists();
                    if(!$referral){
                        return response()->json(['status' => 0, 'message' => 'Invalid referral ID']);
                    }
                }

                User::create($input);

                DB::commit();
                return response()->json(['status'=> 1, 'message' => "Account created successfully"]);
            }catch(\Exception $e){
                DB::rollback();
                //dd($e);
                return response()->json(['status'=> 0, 'message'=>'Error creating account','error' => $e]);
            }
        }else{
            DB::rollback();
            return response()->json(['status'=> 0, 'message'=>'Error creating account', 'error' => $validator->errors()]);
        }
    }

    public function login(Request $request)
    {
        $input = $request->all();
        $rules = array(
            'username'      => 'required',
            'password' => 'required',
            'device_name' => 'required'
        );

        $validator = Validator::make($input, $rules);

        if ($validator->passes())
        {
            $user = User::where('email', $request->username)->orWhere('phoneno', $request->username)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                return response()->json(['status'=> 0, 'message'=>'Incorrect Login Details', 'error'=>'']);
            }

            if($user->status != "active"){
                return response()->json(['status'=> 0, 'message'=>'User '.auth()->user()->status.', kindly contact support']);
            }

//            $set=DB::table("Settings")->where("id","=","1")->first();

            $company=Company::find($user->company_id);

            $token=$user->createToken($request->device_name)->plainTextToken;
            return response()->json(['status'=> 1, 'message' => "User authenticated successfully", 'token' => $token, 'wallet'=>$user->wallet, 'first_name'=>$user->first_name, 'last_name'=>$user->last_name, 'company'=>$company->name, 'profile_path'=> 'https://ui-avatars.com/api/?name='. substr($user->first_name,0,2).'&color=7F9CF5&background=EBF4FF']);

        }else{
            return response()->json(['status'=> 0, 'message'=>'Unable to login with errors', 'error' => $validator->errors()]);;
        }
    }

    public function resetpassword(Request $request)
    {
        $input = $request->all();
        $rules = array(
            'username'   => 'required');

        $messages = array(
            'min' => 'Hmm, that looks short.',
            'max' => 'Oops, that too long.',
            'alpha_num'  => 'Use alphabet or alphabet with numbers to secure your password.');

        $validator = Validator::make($input, $rules, $messages);

        if ($validator->passes()) {

            $user=User::where('email', $request->username)->orWhere('phoneno', $request->username)->first();

            if(!$user){
                return response()->json(['status'=> 0, 'message'=>'User not found']);
            }
//            email reset link

            return response()->json(['status'=> 1, 'message'=>'Password Reset successfully! Kindly check your email.']);

        }else{
            return response()->json(['status'=> 0, 'message'=>'Error resetting password', 'error' => $validator->errors()]);
        }

    }
}
