<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Models\User;
use App\Models\VirtualAccount;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Copy allowed profile fields onto the user, skipping any columns that do
     * not exist on the users table. Never mass-assign raw request input.
     */
    private function fillExistingColumns(User $user, array $input, array $allowed)
    {
        $fields = [];

        foreach ($allowed as $column) {
            if (array_key_exists($column, $input) && Schema::hasColumn('users', $column)) {
                $fields[$column] = $input[$column];
            }
        }

        $user->forceFill($fields)->save();
    }

    public function getUser() {
        $user = Auth::user();
        $set = Settings::find(1);
        return response()->json(['status' => 1, 'message'=>'User details generated successfully', 'data'=> $user, 'settings'=>$set]);
    }

    public function removePhoto(Request $request) {
        $user = User::find(Auth::id());

        if ($user && $user->profile_photo_path) {
            $file = basename($user->profile_photo_path);
            if (Storage::disk('public')->exists('users/' . $file)) {
                Storage::disk('public')->delete('users/' . $file);
            }
            $user->profile_photo_path = null;
            $user->save();
        }

        return response()->json(['status'=> 1, 'message'=>'Profile photo removed successfully', 'data'=> $user]);
    }

    public function updateUser(Request $request) {
        {
            $input = $request->all();
            $rules = array(
                'first_name'   => 'required|min:3|max:20',
                'last_name'   => 'required|min:3|max:20',
                'dob' => 'required',
                'country' => 'required',
                'role' => 'required|min:4');

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

                    $user = User::find(Auth::id());

                    // Whitelist editable profile fields — never mass-assign raw
                    // request input (that would let a client overwrite status,
                    // password, company_id, uuid, etc.). Only persist columns
                    // that actually exist on the users table.
                    $this->fillExistingColumns($user, [
                        'first_name' => ucfirst($input['first_name']),
                        'last_name'  => ucfirst($input['last_name']),
                    ] + $input, ['dob', 'country', 'username', 'gender', 'address', 'phoneno', 'first_name', 'last_name']);

                    /*                $file_data= $request->input('image');
                                    //generating unique file name;
                                    $file_name = $input['username'].'.jpg';
                                    @list($type, $file_data) = explode(';', $file_data);
                                    @list(, $file_data)= explode(',', $file_data);
                                    if($file_data!=""){
                                        // storing image in storage/app/public Folder
                                        \Storage::disk('public')->put($file_name,base64_decode($file_data));
                                        // \File::put(storage_path(). '/' . $file_name, base64_decode($file_data));

                                        //Storage::put('/' . $file_name, $file_data, 'public');
                                    }*/

                    DB::commit();
                    return response()->json(['status'=> 1, 'message' => "Profile updated successfully"]);
                }catch(\Exception $e){
                    DB::rollback();
                    //dd($e);
                    return response()->json(['status'=> 0, 'message'=>'Error updating profile','error' => $e]);
                }
            }else{
                DB::rollback();
                return response()->json(['status'=> 0, 'message'=>'Error updating profile', 'error' => $validator->errors()]);
            }
        }
    }

    public function uploaddp(Request $request){
        $input = $request->all();
        $rules = array(
            'image'   => 'required');

        $messages = array(
            'min' => 'Hmm, that looks short.',
            'max' => 'Oops, that too long.',
            'alpha_num'  => 'Use alphabet or alphabet with numbers to secure your password.');


        $validator = Validator::make($input, $rules, $messages);

        if ($validator->passes()) {
            if ($input['image']) {
                // Accept base64 data URIs only (data:image/...)
                if (stripos($input['image'], 'data:image/') !== 0) {
                    return response()->json(['status'=> 0, 'message'=>'Invalid image format']);
                }

                $file_data = $input['image'];
                //generating unique file name;
                $file_name = Auth::id() ."_".time(). '.jpg';
                @list($type, $file_data) = explode(';', $file_data);
                @list(, $file_data) = explode(',', $file_data);
                if ($file_data != "") {
                    // storing image in storage/app/public Folder
                    \Storage::disk('public')->put('users/'.$file_name, base64_decode($file_data));

                    $user=User::find(Auth::id());
                    $user->profile_photo_path='user/image/'.$file_name;
                    $user->save();

                    AuditService::log('profile.photo_uploaded', 'Profile photo uploaded', 'info', 'User', $user->id);

                    return response()->json(['status'=> 1, 'message'=>'DP uploaded successfully']);
                }

                return response()->json(['status'=> 0, 'message'=>'File error', 'error' => '']);
            }
        }else{
            return response()->json(['status'=> 0, 'message'=>'Error updating DP', 'error' => $validator->errors()]);
        }
    }

    public function updateProfile(Request $request)
    {
        $input = $request->all();
        $rules = array(
            'gender'   => 'required');

        $messages = array(
            'min' => 'Hmm, that looks short.',
            'max' => 'Oops, that too long.',
            'alpha_num'  => 'Use alphabet or alphabet with numbers to secure your password.');


        $validator = Validator::make($input, $rules, $messages);

        if ($validator->passes()) {

            $user = User::find(Auth::id());

            // Only persist columns that actually exist on the users table — the
            // schema has no address/gender columns, so writing them blindly
            // throws "Unknown column" and breaks the whole profile update.
            $this->fillExistingColumns($user, $input, ['phoneno', 'email', 'first_name', 'last_name', 'address', 'gender']);

            return response()->json(['status'=> 1, 'message'=>'Profile updated successfully']);

        }else{
            return response()->json(['status'=> 0, 'message'=>'Error updating profile', 'error' => $validator->errors()]);
        }

    }

    public function changepassword(Request $request)
    {
        $input = $request->all();
        $rules = array(
            'oldpassword'   => 'required',
            'newpassword'   => 'required');

        $messages = array(
            'min' => 'Hmm, that looks short.',
            'max' => 'Oops, that too long.',
            'alpha_num'  => 'Use alphabet or alphabet with numbers to secure your password.');


        $validator = Validator::make($input, $rules, $messages);

        if ($validator->passes()) {

            $user=User::find(Auth::id());

            if(!$user){
                return response()->json(['status'=> 0, 'message'=>'User not found']);
            }

            if (! Hash::check($input['oldpassword'], $user->password)){
                return response()->json(['status'=> 0, 'message'=>'Incorrect current password']);
            }

            $user->password=Hash::make($input['newpassword']);
            $user->save();

            return response()->json(['status'=> 1, 'message'=>'Password changed successfully']);

        }else{
            return response()->json(['status'=> 0, 'message'=>'Error changing password', 'error' => $validator->errors()]);
        }

    }

    public function vaccts(Request $request)
    {
        $vs=VirtualAccount::where('user_id',Auth::id())->get();
        return response()->json(['status'=> 1, 'message'=>'Virtual accounts loaded successfully', 'data'=>$vs]);
    }

    public function documentupload(Request $request){
        $input = $request->all();
        $rules = array(
            'type' => 'required',
            'image'   => 'required');

        $messages = array(
            'min' => 'Hmm, that looks short.',
            'max' => 'Oops, that too long.',
            'alpha_num'  => 'Use alphabet or alphabet with numbers to secure your password.');


        $validator = Validator::make($input, $rules, $messages);

        if ($validator->passes()) {
            if ($input['image']) {
                $file_data = $input['image'];
                //generating unique file name;
                $file_name = Auth::id() ."_".time(). '.jpg';
                @list($type, $file_data) = explode(';', $file_data);
                @list(, $file_data) = explode(',', $file_data);
                if ($file_data != "") {
                    // storing image in storage/app/public Folder
                    \Storage::disk('public')->put('users/'.$file_name, base64_decode($file_data));
//
//                     \File::put(storage_path('app/public'). '/' . $file_name, base64_decode($file_data));

//                    $decodedImage = base64_decode("$image");
//                    file_put_contents(storage_path("app/public/avatar/". $photo) , $decodedImage);


                    $user=User::find(Auth::id());
                    $user->profile_photo_path='user/image/'.$file_name;
                    $user->save();

                    return response()->json(['status'=> 1, 'message'=>'DP uploaded successfully']);
                }

                return response()->json(['status'=> 0, 'message'=>'File error', 'error' => '']);
            }
        }else{
            return response()->json(['status'=> 0, 'message'=>'Error uploading file', 'error' => $validator->errors()]);
        }
    }


}
