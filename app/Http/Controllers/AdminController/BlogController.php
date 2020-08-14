<?php
    namespace App\Http\Controllers\AdminController;
    use App\Http\Controllers\Controller;
    use App\Models\Blog;
    use App\Models\Comment;
    use App\Models\CategoryBlog;
    use Illuminate\Http\Request;
    use Validator;
    use Auth;
    class BlogController extends Controller
    {
        public function __construct(){
            $this->middleware('permission:blog-list|blog-create|blog-update|blog-delete',['only'=>['index','show']]);
            $this->middleware('permission:blog-create',['only'=>['create','store']]);
            $this->middleware('permission:blog-update',['only'=>['edit','update','toggle_edit']]);
            $this->middleware('permission:blog-delete',['only'=>['destroy']]);
        }
        public function index(){
            $listBlog = Blog::orderBy('created_at','desc')->paginate(5);
            return view('admin/blog/index',compact('listBlog'));
        }
        public function create(){
            $listCatB = CategoryBlog::all();
            return view('admin/blog/form-add',compact('listCatB'));
        }
        public function show($id){
            $blog = Blog::find($id);
            return view('admin/blog/detail',compact('blog'));
        }
        public function store(Request $request){
            $rules = [
                'title' => 'required|unique:blog,title',
                'slug' => 'required|unique:blog,slug',
                'catalogId' => 'required',
                'notes' => 'required',
                'description' => 'required',
                'image' => 'required',
                'status' => 'required'

            ];
            $messages = [
                'title.required' => 'Tiêu đề không được rỗng',
                'title.unique' => 'Tiêu đề đã tồn tại',
                'slug.required' => 'Đường dẫn Seo không được rỗng',
                'slug.unique' => 'Đường dẫn Seo đã tồn tại',
                'catalogId.required' => 'Danh mục không được rỗng',
                'notes.required' => 'Chú thích không được rỗng',
                'description.required' => 'Mô tả không được rỗng',
                'image.required' => 'Ảnh không được rỗng',
                'status.required' => 'Trạng thái không được rỗng'
            ];
            $errors = Validator::make($request->all(),$rules,$messages);
            if($errors->fails()){
                return response()->json(['errors'=>$errors->errors()->all()]);
            }
            $form_data = [
                'title' => $request->title,
                'status' => $request->status,
                'notes' => $request->notes,
                'catalogId' => $request->catalogId,
                'slug' =>$request->slug,
                'description' => $request->description,
                'image' => $request->image,
                'status' => $request->status
            ];
            Blog::create($form_data);
            return response()->json(['success'=>'Thêm mới thành công']);
        }
        public function edit($id){
            $blog = Blog::find($id);
            $listCatB = CategoryBlog::all();
            return view('admin/blog/form-edit',compact('blog','listCatB'));
        }
        public function update(Request $request,$id){
            $request->offsetUnset('_token');
            $request->offsetUnset('_method');
            $rules = [
                'title' => 'required|unique:blog,title,'.$request->id,
                'slug' => 'required|unique:blog,slug,'.$request->id,
                'catalogId' => 'required',
                'notes' => 'required',
                'description' => 'required',
                'image' => 'required',
                'status' => 'required'

            ];
            $messages = [
                'title.required' => 'Tiêu đề không được rỗng',
                'title.unique' => 'Tiêu đề đã tồn tại',
                'slug.required' => 'Đường dẫn Seo không được rỗng',
                'slug.unique' => 'Đường dẫn Seo đã tồn tại',
                'catalogId.required' => 'Danh mục không được rỗng',
                'notes.required' => 'Chú thích không được rỗng',
                'description.required' => 'Mô tả không được rỗng',
                'image.required' => 'Ảnh không được rỗng',
                'status.required' => 'Trạng thái không được rỗng'
            ];
            $errors = Validator::make($request->all(),$rules,$messages);
            if($errors->fails()){
                return response()->json(['errors'=>$errors->errors()->all()]);
            }
            $form_data = [
                'title' => $request->title,
                'status' => $request->status,
                'notes' => $request->notes,
                'catalogId' => $request->catalogId,
                'slug' =>$request->slug,
                'description' => $request->description,
                'image' => $request->image,
                'status' => $request->status
            ];
            Blog::where('id',$request->id)->update($form_data);
            return response()->json(['success'=>'Cập nhật thành công']);
        }
        public function destroy($id){
            $data = Blog::findOrFail($id);
            $listCom = Comment::where('blogId',$data->id)->get();
            if(count($listCom)>0){
                return response()->json(['errors'=>'Bài viết chứa bình luận không thể xoá']);
            }else{
                $data->delete();
            }
        }
        public function toggle_edit(Request $request){
            $ban = Blog::find($request->id);
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
