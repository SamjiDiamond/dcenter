<?php


namespace App\Http\Controllers;


use App\Models\BouncerRoleModel;
use App\Models\Transaction;
use App\Models\User;
use App\Services\AuditService;
use Bouncer;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Silber\Bouncer\Database\Role;
use Stripe\Stripe;


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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
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
            $roles = BouncerRoleModel::get();
        }else{
            $users = User::join("assigned_roles","assigned_roles.entity_id", "=","users.id")
                ->join("roles","roles.id", "=","assigned_roles.role_id")
                ->join("company", "company.id","=","users.company_id")
                ->select('users.*', 'roles.name as role', 'company.name as company')
                ->where('users.account_type','=','admin')
                ->where('users.company_id','=',auth()->user()->company_id)
                ->orderBy('id','DESC')->get();
            $roles = BouncerRoleModel::where('company_id','=',auth()->user()->company_id)->get();
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
            return back()->withToast('Subscription is completed.');
        } catch (Exception $e) {
            return back()->withToast($e->getMessage(), 'danger');
        }

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
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

                $input = $request->all();
                $nu['company_id'] = auth()->user()->company_id;
                $nu['account_type'] = "admin";
                $nu['last_name'] = $input['last_name'];
                $nu['first_name'] = $input['first_name'];
                $nu['email'] = $input['email'];
                $nu['phoneno'] = $input['phoneno'];

                $u = User::where('email', '=', $input['email'])->exists();
                if ($u) {
                    $user = User::where('email', '=', $input['email'])->first();
                    if ($user->account_type != 'admin') {
                        $user->update([
                            'company_id'   => $nu['company_id'],
                            'account_type' => 'admin',
                            'last_name'    => $nu['last_name'],
                            'first_name'   => $nu['first_name'],
                            'email'        => $nu['email'],
                            'phoneno'      => $nu['phoneno'],
                        ]);
                    } else {
                        return redirect()->route('admin.list')->withToast('Admin already exist', 'danger');
                    }

                }else {
                    $nu['password'] = Hash::make("12345");
                    $user = User::create($nu);
                }

                $user->assign($request->input('role_id'));

                return redirect()->route('admin.list')->withToast('Admin created successfully');
            }catch(\Exception $e){
                return redirect()->route('admin.list')->withToast('Error creating Admin', 'danger');
            }
        }else{
            return redirect()->route('admin.list')->withToast('Error creating Admin, check your input and try again', 'danger');
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        if (! $this->canManage($user)) {
            return redirect()->route('admin.list')->withToast('You cannot manage users from another company.', 'danger');
        }

        $use = User::join("assigned_roles","assigned_roles.entity_id", "=","users.id")
//            ->join("roles","roles.id", "=","assigned_roles.role_id")
            ->where("users.id", "=", $user->id)
            ->select("users.*", "assigned_roles.role_id")
            ->first();

        if (! $use) {
            return redirect()->route('admin.list')->withToast('Admin not found', 'danger');
        }

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
    public function update(Request $request, User $user)
    {
        if (! $this->canManage($user)) {
            return redirect()->route('admin.list')->withToast('You cannot manage users from another company.', 'danger');
        }

        $this->validate($request, [
            'last_name' => 'required|min:3',
            'first_name' => 'required|min:3',
            'role_id' => 'required',
            'phoneno' => 'required|min:11|max:16'
        ]);


        $input = $request->all();

//        array_shift($input);

        User::where('id', $user->id)->update(['last_name' => $input['last_name'], 'first_name' => $input['first_name'], 'phoneno' => $input['phoneno'] ]);

        DB::table('assigned_roles')->where('entity_id', $user->id)->update(['role_id'=>$input['role_id']]);


        return redirect()->route('admin.list')
            ->withToast('Admin updated successfully');
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
            ->withToast('User deleted successfully');
    }

    /**
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function disable(User $user)
    {
        if (! $this->canManage($user)) {
            return redirect()->route('admin.list')->withToast('You cannot manage users from another company.', 'danger');
        }

        User::where('id', '=', $user->id)->update(['status'=>'disable']);

        AuditService::log('admin.disabled', 'Admin ' . $user->email . ' disabled', 'warning', 'User', $user->id);

        return redirect()->route('admin.list')
            ->withToast('Admin disabled successfully');
    }

    /**
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function enable(User $user)
    {
        if (! $this->canManage($user)) {
            return redirect()->route('admin.list')->withToast('You cannot manage users from another company.', 'danger');
        }

        User::where('id', '=', $user->id)->update(['status'=>'active']);

        AuditService::log('admin.enabled', 'Admin ' . $user->email . ' enabled', 'warning', 'User', $user->id);

        return redirect()->route('admin.list')
            ->withToast('Admin enabled successfully');
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

    public function userdetails(User $user){
        if (! $this->canManage($user)) {
            return view('errors.404');
        }

        $transactions=Transaction::where('user_id', '=', $user->id)->get();

        $data_order=Transaction::where([['user_id','=',$user->id], ['type', '=', 'data']])->count();
        $tv_order=Transaction::where([['user_id','=',$user->id], ['type', '=', 'tv']])->count();
        $airtime_order=Transaction::where([['user_id','=',$user->id], ['type', '=', 'airtime']])->count();
        $transfer_order=Transaction::where([['user_id','=',$user->id], ['type', '=', 'transfer']])->count();
        $electricity_order=Transaction::where([['user_id','=',$user->id], ['type', '=', 'electricity']])->count();

        return view('user', ['user' => $user, 'transactions'=>$transactions, 'data'=>$data_order, 'tv'=>$tv_order, 'airtime'=>$airtime_order, 'transfer'=>$transfer_order, 'electricity'=>$electricity_order, 'i'=>1]);
    }

    public function userenable(User $user)
    {
        if (! $this->canManage($user)) {
            return redirect()->route('user.list')->withToast('You cannot manage users from another company.', 'danger');
        }

        User::where('id', '=', $user->id)->update(['status'=>'active']);

        AuditService::log('user.enabled', 'User ' . $user->email . ' enabled', 'warning', 'User', $user->id);

        return redirect()->route('user.list')
            ->withToast('User enabled successfully');
    }

    public function userdisable(User $user)
    {
        if (! $this->canManage($user)) {
            return redirect()->route('user.list')->withToast('You cannot manage users from another company.', 'danger');
        }

        User::where('id', '=', $user->id)->update(['status'=>'disable']);

        AuditService::log('user.disabled', 'User ' . $user->email . ' disabled', 'warning', 'User', $user->id);

        return redirect()->route('user.list')
            ->withToast('User disabled successfully');
    }

    /**
     * Only a user's own company (or the platform super admin, company 1) may
     * manage that account.
     */
    private function canManage(User $user)
    {
        return (int) $user->company_id === (int) auth()->user()->company_id
            || (int) auth()->user()->company_id === 1;
    }

    public function useredit(User $user){
        $user = User::join("company","company.id", "=","users.company_id")
            ->join("assigned_roles","assigned_roles.entity_id", "=","users.id")
            ->join("roles","roles.id", "=","assigned_roles.role_id")
//            ->join("roles", "roles.id", "=","users.role_id")
            ->select('users.*', 'company.name as company', 'roles.name as roles')
            ->where('users.id','=',$user->id)->first();

        // The inner joins can leave $user null when the account has no assigned role.
        if (!$user) {
            return view('errors.404');
        }

        if (! $this->canManage($user)) {
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
    public function userupdate(Request $request, User $user)
    {
        if (! $this->canManage($user)) {
            return redirect()->route('user.list')->withToast('You cannot manage users from another company.', 'danger');
        }

        $this->validate($request, [
            'last_name' => 'required|min:3',
            'first_name' => 'required|min:3',
            'email' => 'required',
            'phoneno' => 'required|min:11|max:16'
        ]);

        $input = $request->all();

        $v = User::where('email', $input['email'])->first();

        // The email may legitimately belong to nobody (a brand new address);
        // only reject it when it belongs to a different user.
        if ($v && $v->id != $user->id) {
            return redirect()->back()->withToast('Email Address belongs to another user', 'danger');
        }

        $v = User::where('phoneno', $input['phoneno'])->first();

        if ($v && $v->id != $user->id) {
            return redirect()->back()->withToast('Phone Number belongs to another user', 'danger');
        }

        // Update only the editable columns — never mass-assign raw request input.
        $user->update([
            'last_name'  => $input['last_name'],
            'first_name' => $input['first_name'],
            'email'      => $input['email'],
            'phoneno'    => $input['phoneno'],
        ]);

        return redirect()->route('user.list')
            ->withToast($input['last_name'] . ' profile updated successfully');
    }

    public function uploadImage(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg',
        ]);
$im = auth()->user();
        if($request->hasfile('image')){
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time(). '.' . $extension;
            $file->move('public/images/', $filename);
            $im->image = $filename;
        }
       $im->save();
        if($im->save()){
            return redirect()->back()->withToast('Image uploaded successfully');
        }
//        if( $request->hasFile('image') && $request->file('image')->isValid())
//        {
//         $image = $request->file('image');

//         $filename = $request->file('image')->getClientOriginalName();
//         $path = $request->file('image')->store('public');
// return $path;
//         $avatar = substr($path, 14);

//         $user = auth()->user();
//         $prevImage = $user->image;
//         if($prevImage){
//             $path = $_SERVER['DOCUMENT_ROOT']. '\storage\images\\' . $prevImage;
//             // unlink($path);
//         }
//         $user->image = $avatar;

//         if($user->save()){

//             return redirect()->back()->withToast('Image uploaded successfully');
//         }

        }

    }




