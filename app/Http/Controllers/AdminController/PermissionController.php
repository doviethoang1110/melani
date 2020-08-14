<?php


namespace App\Http\Controllers\AdminController;


use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Review;
use Validator;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct(){
        $this->middleware('permission:permission-list|permission-create|permission-update|permission-delete',['only'=>['index']]);
        $this->middleware('permission:permission-create',['only'=>['store']]);
        $this->middleware('permission:permission-update',['only'=>['edit','update']]);
        $this->middleware('permission:permission-delete',['only'=>['destroy','multi_delete']]);
    }
    public function index(){
        $listPer = Permission::orderBy('created_at','desc')->paginate(10);
        return view('admin/permission/index',compact('listPer'));
    }
    public function store(Request $request){
        $rules = [
            'name' => 'required|unique:permissions,name'
        ];
        $messages = [
            'name.required' => 'Quyền không được để trống',
            'name.unique' => 'Quyen da ton tai'
        ];
        $errors = Validator::make($request->all(),$rules,$messages);
        if($errors->fails()){
            return response()->json(['errors'=>$errors->errors()->all()]);
        }else{
            Permission::create(['name'=>$request->name]);
            return response()->json(['success'=>'Thêm mới thành công']);
        }
    }
    public function edit(Request $request,$id){
        $data = Permission::find($id);
        if($request->ajax()){
            return response()->json(['data'=>$data]);
        }
    }
    public function update(Request $request,$id){
        $request->offsetUnset('_token');
        $request->offsetUnset('_method');
            $rules = [
                'name' => 'required|unique:permissions,name,'.$request->hidden_id
            ];
            $messages = [
                'name.required' => 'Quyền không được rỗng',
                'name.unique' => 'Quyen da ton tai'
            ];
            $errors = Validator::make($request->all(),$rules,$messages);
            if($errors->fails()){
                return response()->json(['errors'=>$errors->errors()->all()]);
            }
            $per = Permission::findById($request->hidden_id);
            $per->name = $request->name;
            $per->save();
            return response()->json(['success'=>'Cập nhật thành công']);
    }
    public function destroy($id){
        $data = Permission::findById($id);
        $data->delete();
        return response()->json(['success'=>'Xoá thành công']);
    }
    public function multi_delete(Request $request){
        $id = $request->id;
        $listCom = Permission::whereIn('id',$id)->get();
        if($listCom){
            foreach ($listCom as $value) {
                $value->delete();
            }
            return response()->json(['success'=>'Xoá thành công']);
        }else{
            return response()->json(['error'=>'Xoá thất bại']);
        }
    }
}
