<?php

    namespace App\Http\Controllers\AdminController;
    use App\Models\Category;
    use App\Models\Product;
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use Validator;
    class CategoryController extends Controller
    {
        public function __construct(){
            $this->middleware('permission:category-list|category-create|category-update|category-delete',['only'=>['index','show']]);
            $this->middleware('permission:category-create',['only'=>['create','store']]);
            $this->middleware('permission:category-update',['only'=>['edit','update','toggle_edit']]);
            $this->middleware('permission:category-delete',['only'=>['destroy']]);
        }
        public function index(){
            $listCata = Category::orderBy('created_at','desc')->get();
            $listCat1 = Category::all();
            return view('admin/category/index',compact('listCata','listCat1'));
        }
        public function toggle_edit(Request $request){
            $ban = Category::find($request->id);
            if($ban){
                $ban->status = $request->status;
                $ban->save();
                return response()->json(['success'=>'Cập nhật thành công']);
            }else{
                return response()->json(['error'=>'Cập nhật thất bại']);
            }
        }
        public function store(Request $request){
            $rules = array(
                'name' => 'required|unique:category,name',
                'slug' => 'required|unique:category,slug',
                'status' => 'required'
            );
            $messages = array(
                'name.required' => 'Tên danh mục không được để trống',
                'name.unique' => 'Tên danh mục đã tồn tại',
                'slug.required' => 'Tên thay thế không được để trống',
                'slug.unique' => 'Tên thay thế không được để trống',
                'status.required' => 'Trạng thái không được để trống'
            );
            $errors = Validator::make($request->all(),$rules,$messages);
            if($errors->fails()){
                return response()->json(['errors'=>$errors->errors()->all()]);

            }
            Category::create($request->all());
            return response()->json(['success'=>'Thêm mới thành công.']);
        }

        public function edit(Request $request,$id){
            if($request->ajax()){
                $data = Category::findOrFail($id);
                return response()->json(['data'=>$data]);
            }
        }

        public function update(Request $request,$id){
            $request->offsetUnset('_token');
            $request->offsetUnset('_method');
            $id = $request->hidden_id;
            $rules = array(
                'name' => 'required|unique:category,name,'.$id,
                'slug' => 'required|unique:category,slug,'.$id,
                'status' => 'required'
            );
            $messages = array(
                'name.required' => 'Tên danh mục không được để trống',
                'name.unique' => 'Tên danh mục đã tồn tại',
                'slug.required' => 'Tên thay thế không được để trống',
                'slug.unique' => 'Tên thay thế không được để trống',
                'status.required' => 'Trạng thái không được để trống'
            );
            $errors = Validator::make($request->all(),$rules,$messages);
            if($errors->fails()){
                return response()->json(['errors'=>$errors->errors()->all()]);
            }
            $form_data = [
                'name' => $request->name,
                'status' =>$request->status,
                'parentId' =>$request->parentId,
                'slug' =>$request->slug
            ];
            Category::where('id',$id)->update($form_data);
            return response()->json(['success'=>'Cập nhật thành công.']);
        }

        public function destroy($id){
            $data = Category::findOrFail($id);
            $listPro = Product::where('catalogId',$data->id)->get();
            $parent = Category::where('parentId',$data->id)->get();
            if(count($listPro)>0){
                return response()->json(['errors'=>'Danh mục chứa sản phẩm không thể xoá']);
            }else if(count($parent)>0){
                return response()->json(['errors'=>'Danh mục chứa danh mục con không thể xoá']);
            }else{
                $data->delete();
            }
        }
    }


?>
