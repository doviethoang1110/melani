<?php


namespace App\Http\Controllers\AdminController;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Validator;
use DB;
class RoleController extends Controller
{
    public function __construct(){
        $this->middleware('permission:role-list|role-create|role-update|role-delete',['only'=>['index','show']]);
        $this->middleware('permission:role-create',['only'=>['store']]);
        $this->middleware('permission:role-update',['only'=>['edit','update']]);
        $this->middleware('permission:role-delete',['only'=>['destroy']]);
    }
    public function index(){
        $roles = Role::orderBy('created_at','desc')->paginate(10);
        $permissions = Permission::all();
        return view('admin/role/index',compact('roles','permissions'));
    }
    public function show($id){
        $role = Role::where('id',$id)->value('name');
        $rolePermission = Permission::join('role_has_permissions','role_has_permissions.permission_id','permissions.id')
            ->where('role_has_permissions.role_id',$id)->pluck('name')->toArray();
        return view('admin/role/detail',compact('rolePermission','role'));
    }
    public function store(Request $request){
        $rules = [
            'name'=>'required|unique:roles,name',
            'permission_id' => 'required'
        ];
        $messages = [
            'name.required' => 'Tên không được rỗng',
            'name.unique' => 'Ten da ton tai',
            'permission_id.required' => 'Quyền không được rỗng'
        ];
        $errors = Validator::make($request->all(),$rules,$messages);
        if($errors->fails()){
            return response()->json(['errors'=>$errors->errors()->all()]);
        }else{
            $role = Role::create(['name' => $request->name]);
            $role->syncPermissions($request->permission_id);
            return response()->json(['success'=>'Thêm mới thành công']);
        }
    }
    public function edit(Request $request,$id){
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->select('role_has_permissions.permission_id as id','permissions.name as name')
            ->join('permissions','permissions.id','role_has_permissions.permission_id')
            ->where("role_has_permissions.role_id",$id)->get()->toArray();
        $permissionName = [];
        $permissionId = [];
        foreach($rolePermissions as $r){
            $permissionName[] = $r->name;
            $permissionId[] = $r->id;
        }
        $data = [
            'id' => $role->id,
            'name' => $role->name,
            'permission_id' =>$permissionId,
            'permission_name' => $permissionName
        ];
        if($request->ajax()){
            return response()->json(['data'=>$data]);
        }
    }
    public function update(Request $request,$id){
        $request->offsetUnset('_token');
        $request->offsetUnset('_method');
        $rules = [
            'name'=>'required|unique:roles,name,'.$request->hidden_id,
            'permission_id' => 'required'
        ];
        $messages = [
            'name.required' => 'Tên không được rỗng',
            'name.unique' => 'Ten da ton tai',
            'permission_id.required' => 'Quyền không được rỗng'
        ];
        $errors = Validator::make($request->all(),$rules,$messages);
        if($errors->fails()){
            return response()->json(['errors'=>$errors->errors()->all()]);
        }else{
            $role = Role::find($id);
            $role->name = $request->input('name');
            $role->save();
            $role->syncPermissions($request->permission_id);
            return response()->json(['success'=>'Thêm mới thành công']);
        }
    }
    public function destroy($id){
        DB::table("roles")->where('id',$id)->delete();
        return response()->json(['success'=>'Xoá thành công']);
    }
}
