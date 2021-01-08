<?php


namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Facades\Auth;
use Bouncer;
use Silber\Bouncer\Database\Ability;
use Silber\Bouncer\Database\Role;


class RoleController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('auth');
//        $this->middleware('subware');
//        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
//        $this->middleware('permission:role-create', ['only' => ['create','store']]);
//        $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
//        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(auth()->user()->company_id==1){
            $roles = Role::join("company", "company.id","=","roles.company_id")
                ->select('roles.*', 'company.name as company')
                ->orderBy('id','DESC')->get();
        }else{
            $roles = Role::join("company", "company.id","=","roles.company_id")
                ->select('roles.*', 'company.name as company')
                ->where('company_id','=',auth()->user()->company_id)
                ->orderBy('id','DESC')->get();
        }

        $rolePermissions = Role::join("permissions","permissions.entity_id","=","roles.id")
            ->join("abilities","abilities.id","=","permissions.ability_id")
            ->select('roles.*', 'permissions.*', 'abilities.title as ability_name')
            ->get();
        $permission =  Ability::all();

//        auth()->user()->assign('admin');
////        auth()->user()->assign('admin');

//       echo Bouncer::is(auth()->user())->a('admin');
//
//        echo auth()->user()->getAbilities();

        return view('roles', ['roles' => $roles, 'permissions' =>$permission, 'rolePermissions' => $rolePermissions, 'i'=>1]);
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
            'name' => 'required',
            'permission' => 'required');

        $messages = array(
            'min' => 'Hmm, that looks short.',
            'max' => 'Oops, that too long.',
            'alpha_num'  => 'Use alphabet or alphabet with numbers to secure your password.');


        $validator = Validator::make($input, $rules, $messages);

        if ($validator->passes())
        {
            try
            {
                $rol=Role::where([['name','=',$request->input('name')], ['company_id', '=', auth()->user()->company_id]])->exists();
                if($rol) {
                    return redirect()->route('role.list')->with('error','Role already exist');
                }

                $role = Bouncer::role()->firstOrCreate([
                    'name' => $request->input('name'),
                    'title' => $request->input('description'),
                    'company_id' => auth()->user()->company_id,
                ]);

                $role->allow($request->input('permission'));

                return redirect()->route('role.list')
            ->with('success','Role created successfully');
            }catch(\Exception $e){
                DB::rollback();
                return redirect()->route('role.list')->with('error','Error creating Role');
            }
        }else{
            DB::rollback();
            return redirect('/roles')
                ->withErrors($validator)
                ->withInput();
//            return redirect()->route('role.list')->with('error','Error creating Role');
//            return response()->json(['status'=> 0, 'message'=>'Error creating account', 'error' => $validator]);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::get();
        $rolePermissions = Role::join("role_has_permissions","role_has_permissions.role_id","=","roles.id")
            ->join("permissions","role_has_permissions.permission_id","=","permissions.id")
            ->get();
        $permission = Permission::get();

        $permi="";

//        foreach($permission as $permissions) {



                    foreach($permission as $permissions) {
//                        echo $permissions->name;
                        foreach ($rolePermissions as $rolePermission) {
                            if ($rolePermission->role_id == $id) {

                        if ($permi != $rolePermission->name) {
                            $permi = $rolePermission->name;
                            if ($rolePermission->permission_id == $permissions->id) {
                                echo $rolePermission->name . "---";


                                echo "yes <br /><br />";
                            } else {
                                echo "no <br /><br />";
                            }
                        }
                    }

//                    if ($rolePermission->permission_id == $permissions->id && $permi!=$rolePermission->name) {
//                        $permi=$rolePermission->name;
////                    echo $permission->name;
//                        if ($rolePermission->name == $permissions->name) {
//                            echo $rolePermission->name . " Checked <br /><br />";
//                        }
//                    }else {
//                        echo $rolePermission->name . " unChecked <br /><br />";
//                    }
                }
            }
//        }

        echo $role . " <br /><br />" . $rolePermissions . " <br /><br />" . $permission;

//        return view('roles.show',compact('role','rolePermissions'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $rol = Role::find($id);

        if(auth()->user()->company_id==1){
            $roles = Role::join("company", "company.id","=","roles.company_id")
                ->select('roles.*', 'company.name as company')
                ->orderBy('id','DESC')->get();
        }else{
            $roles = Role::join("company", "company.id","=","roles.company_id")
                ->select('roles.*', 'company.name as company')
                ->where('company_id','=',auth()->user()->company_id)
                ->orderBy('id','DESC')->get();
        }

        $rolePermissions = Role::join("permissions","permissions.entity_id","=","roles.id")
            ->join("abilities","permissions.ability_id","=","abilities.id")
            ->get();
        $permission =  Ability::all();

        $rolePermission = DB::table("permissions")->where("permissions.entity_id",$id)
            ->pluck('permissions.ability_id')
            ->all();

//        if(auth()->user()->company_id==1){
//            $roles = Role::join("company", "company.id","=","roles.company_id")
//                ->select('roles.*', 'company.name as company')
//                ->orderBy('id','DESC')->get();
//        }else{
//            $roles = Role::join("company", "company.id","=","roles.company_id")
//                ->select('roles.*', 'company.name as company')
//                ->where('company_id','=',auth()->user()->company_id)
//                ->orderBy('id','DESC')->get();
//        }
//
//        $permission = Permission::get();
//        $rolePermissiona = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
//            ->select('role_has_permissions.permission_id','role_has_permissions.permission_id')
//            ->get();
//        $rolePermission = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
//            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
//            ->all();
//        $rolePermissions = Role::join("role_has_permissions","role_has_permissions.role_id","=","roles.id")
//            ->join("permissions","role_has_permissions.permission_id","=","permissions.id")
//            ->get();

        return view('roles', ['roles' => $roles, 'rol' => $rol, 'permissions' =>$permission, 'rolePermissions' => $rolePermissions , 'roleP' => json_decode(json_encode($rolePermission), true), 'edit' => 'true', 'i'=>1])->with('edit','Role');;
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
            'name' => 'required',
            'permission' => 'required',
        ]);


        $rol=Role::where([['name','=',$request->input('name')], ['company_id', '=', auth()->user()->company_id]])->first();
        if($rol->id!=$id) {
            return redirect()->route('role.list')->with('error','Duplicate Role name is not allowed');
        }

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->title = $request->input('description');
        $role->save();

        $da=Role::join("permissions","permissions.entity_id","=","roles.id")
            ->where('roles.id','=',$id)
            ->pluck('permissions.ability_id')
            ->all();

        $role->disallow($da);

        $role->allow($request->input('permission'));

        return redirect()->route('role.list')
            ->with('success','Role updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table("roles")->where('id',$id)->delete();
        DB::table("permissions")->where("permissions.entity_id",$id)->delete();
        return redirect()->route('role.list')
            ->with('success','Role deleted successfully');
    }

    public function createPermission(){
        $admin = Bouncer::role()->firstOrCreate([
            'name' => 'ceo',
            'title' => 'The owner',
        ]);

        $ban = Bouncer::ability()->firstOrCreate([
            'name' => 'role-view',
            'title' => 'Role View',
        ]);

        $ban = Bouncer::ability()->firstOrCreate([
            'name' => 'role-create',
            'title' => 'Role Create',
        ]);

        $ban = Bouncer::ability()->firstOrCreate([
            'name' => 'role-edit',
            'title' => 'Role Edit',
        ]);

        $ban = Bouncer::ability()->firstOrCreate([
            'name' => 'role-delete',
            'title' => 'Role Delete',
        ]);
    }
}
