<?php
    namespace App\Http\Controllers\AdminController;
    use App\Http\Controllers\Controller;
    use App\Models\CategoryBlog;
    use App\Models\Blog;
    use Illuminate\Http\Request;
    use Validator;
    use Auth;
    class CategoryBlogController extends Controller
    {
        public function __construct(){
            $this->middleware('permission:catBlog-list|catBlog-create|catBlog-update|catBlog-delete',['only'=>['index','show']]);
            $this->middleware('permission:catBlog-create',['only'=>['create','store']]);
            $this->middleware('permission:catBlog-update',['only'=>['edit','update','toggle_edit']]);
            $this->middleware('permission:catBlog-delete',['only'=>['destroy']]);
        }
        public function index(){
            $listCatB = CategoryBlog::orderBy('created_at','desc')->paginate(5);
            return view('admin/category_blog/index',compact('listCatB'));
        }
        public function store(Request $request){
            $rules = array(
                'name' => 'required|unique:categoryBlog,name',
                'slug' => 'required|unique:categoryBlog,slug',
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
            CategoryBlog::create($request->all());
            return response()->json(['success'=>'Thêm mới thành công.']);
        }
        public function edit(Request $request,$id){
            if($request->ajax()){
                $data = CategoryBlog::findOrFail($id);
                return response()->json(['data'=>$data]);
            }
        }
        public function update(Request $request,$id){
            $request->offsetUnset('_token');
            $request->offsetUnset('_method');
            $rules = array(
                'name' => 'required|unique:categoryBlog,name,'.$id,
                'slug' => 'required|unique:categoryBlog,slug,'.$id,
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
            $data = [
                'name' => $request->name,
                'slug' => $request->slug,
                'status' => $request->status
            ];
            CategoryBlog::where('id',$id)->update($data);
            return response()->json(['success'=>'Cập nhật thành công.']);
        }
        public function destroy($id){
            $data = CategoryBlog::findOrFail($id);
            $listBlog = Blog::where('catalogId',$data->id)->get();
            if(count($listBlog)>0){
                return response()->json(['errors'=>'Danh mục chứa bài viết không thể xoá']);
            }else{
                $data->delete();
            }
        }
        public function toggle_edit(Request $request){
            $ban = CategoryBlog::find($request->id);
            if($ban){
                $ban->status = $request->status;
                $ban->save();
                return response()->json(['success'=>'Cập nhật thành công']);
            }else{
                return response()->json(['error'=>'Cập nhật thất bại']);
            }
        }

    }


?>
