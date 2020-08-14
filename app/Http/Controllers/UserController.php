<?php
    namespace App\Http\Controllers;
    use App\User;
    use Illuminate\Http\Request;
    use Spatie\Permission\Models\Role;
    use Validator;
    use DB;
    use App\Http\Controllers\Controller;
    class UserController extends Controller
    {
        public function __construct(){
            $this->middleware('permission:user-list|user-create|user-update|user-delete',['only'=>['index']]);
            $this->middleware('permission:user-create',['only'=>['store','create']]);
            $this->middleware('permission:user-update',['only'=>['edit','update_post']]);
            $this->middleware('permission:user-delete',['only'=>['destroy']]);
        }
        public function index_file(){
            return view('admin/file/index');
        }
        public function index(){
            $listUser = User::orderBy('created_at','desc')->get();
            $listRole = Role::all();
            return view('admin/user/index',compact('listUser','listRole'));
        }
        public function edit($id){
            $user = User::find($id);
            $listRole = Role::all();
            $roles = User::join('model_has_roles','users.id','model_has_roles.model_id')
                ->join('roles','roles.id','model_has_roles.role_id')
                ->where('users.id',$id)->pluck('roles.id');
            return view('admin/user/edit',compact('user','listRole','roles'));
        }
        public function store(Request $request){
            $rules = [
                'name' => 'required|unique:users,name',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|between:5,15',
                'con_pass' => 'required|between:5,15|same:password',
                'role_id' => 'required'
            ];
            $messages = [
                'name.required' => 'Tên không được trống',
                'name.unique' => 'Tên người dùng đã tồn tại',
                'email.required' => 'Email không được trống',
                'email.unique' => 'Email đã tồn tại',
                'email.email' => 'Email không đúng định dạng',
                'password.required' => 'Password không được để trống',
                'password.between' => 'Password nhiều hơn 5 và ít hơn 15 ký tự',
                'con_pass.required' => 'Confirm password không được để trống',
                'con_pass.same' => 'Confirm password không giống password',
                'con_pass.between' => 'Confirm password nhiều hơn 5 và ít hơn 15 ký tự',
                'role_id.required' => 'Quyen khong duoc rong'
            ];
            $errors = Validator::make($request->all(),$rules,$messages);
            if($errors->fails()){
                return response()->json(['errors'=>$errors->errors()->all()]);
            }
            $password = bcrypt($request->password);
            $request->merge(['password'=>$password]);
            $form_data =[
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password
            ];
            $user = User::create($form_data);
            $user->assignRole($request->role_id);
            return response()->json(['success'=>'Thêm mới thành công']);
        }
        public function update_post(Request $request)
        {
            $rules = [
                'name' => 'required|unique:users,name,'.$request->hidden_id,
                'email' => 'required|email|unique:users,email,'.$request->hidden_id,
                'password' => 'required',
                'con_pass' => 'required|same:password',
                'role_id' => 'required'
            ];
            $messages = [
                'name.required' => 'Tên không được trống',
                'name.unique' => 'Tên người dùng đã tồn tại',
                'email.required' => 'Email không được trống',
                'email.unique' => 'Email đã tồn tại',
                'email.email' => 'Email không đúng định dạng',
                'password.required' => 'Password không được để trống',
                'con_pass.required' => 'Confirm password không được để trống',
                'con_pass.same' => 'Confirm password không giống password',
                'role_id.required' => 'Quyen khong duoc rong'
            ];
            $errors = Validator::make($request->all(),$rules,$messages);
            if($errors->fails()){
                return response()->json(['errors'=>$errors->errors()->all()]);
            }
            $password = bcrypt($request->password);
            $request->merge(['password'=>$password]);
            $form_data =[
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password
            ];
            $user = User::find($request->hidden_id);
            $user->update($form_data);
            DB::table('model_has_roles')->where('model_id',$request->hidden_id)->delete();
            $user->assignRole($request->role_id);
            return response()->json(['success'=>'Cập nhật thành công']);
        }
        public function destroy($id)
        {
            User::find($id)->delete();
            return response()->json(['success'=>'Xoá thành công']);
        }
    }

?>
