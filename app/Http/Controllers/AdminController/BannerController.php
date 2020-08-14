<?php
    namespace App\Http\Controllers\AdminController;
    use App\Http\Controllers\Controller;
    use App\Models\Banner;
    use Illuminate\Http\Request;
    use Validator;
    use Auth;
    class BannerController extends Controller
    {
        public function __construct(){
            $this->middleware('permission:banner-list|banner-create|banner-update|banner-delete',['only'=>['index','show']]);
            $this->middleware('permission:banner-create',['only'=>['create','store']]);
            $this->middleware('permission:banner-update',['only'=>['edit','update','toggle_edit']]);
            $this->middleware('permission:banner-delete',['only'=>['destroy']]);
        }
        public function index(){
            $listBanner = Banner::orderBy('created_at','desc')->paginate(5);
            return view('admin/banner/index',compact('listBanner'));
        }
        public function show($id){
            $blog = Blog::find($id);
            return view('admin/blog/detail',compact('blog'));
        }
        public function store(Request $request){
            if($request->type!=1){
                $rules = [
                    'links' => 'required',
                    'image' => 'required|image|mimes:jpg,jpeg,png,gif'
                ];
                $messages = [
                    'links.required' => 'Đường dẫn seo không được rỗng',
                    'image.required' => 'Ảnh không được rỗng',
                    'image.image' => ' Định dạng không đúng'
                ];
                $errors = Validator::make($request->all(),$rules,$messages);
                if($errors->fails()){
                    return response()->json(['errors'=>$errors->errors()->all()]);
                }
                $file = $request->image;
                $file->move(base_path('uploads'),$file->getClientOriginalName());
                $request->merge(['image'=>$file->getClientOriginalName()]);
                $data = [
                    'title' => '',
                    'content' => '',
                    'links' => $request->links,
                    'status' => $request->status,
                    'type' => $request->type,
                    'image' => $file->getClientOriginalName()
                ];
                Banner::create($data);
                return response()->json(['success'=>'Thêm mới thành công']);
            }else{
                $rules = [
                    'title' => 'required',
                    'content' => 'required',
                    'links' => 'required',
                    'image' => 'required|image|mimes:jpg,jpeg,png,gif'
                ];
                $messages = [
                    'title.required' => 'Tiêu đề không được rỗng',
                    'content.required' => 'Nội dung không được rỗng',
                    'links.required' => 'Đường dẫn seo không được rỗng',
                    'image.required' => 'Ảnh không được rỗng',
                    'image.image' => ' Định dạng không đúng'
                ];
                $errors = Validator::make($request->all(),$rules,$messages);
                if($errors->fails()){
                    return response()->json(['errors'=>$errors->errors()->all()]);
                }
                $file = $request->image;
                $file->move(base_path('uploads'),$file->getClientOriginalName());
                $request->merge(['image'=>$file->getClientOriginalName()]);
                $data = [
                    'title' => $request->title,
                    'content' => $request->content,
                    'links' => $request->links,
                    'status' => $request->status,
                    'type' => $request->type,
                    'image' => $file->getClientOriginalName()
                ];
                Banner::create($data);
                return response()->json(['success'=>'Thêm mới thành công']);
            }
        }
        public function edit(Request $request,$id){
            $data = Banner::find($id);
            if($request->ajax()){
                return response()->json(['data'=>$data]);
            }
        }
        public function update(Request $request,$id){
            $request->offsetUnset('_token');
            $request->offsetUnset('_method');
            $img = '';
            if($request->has('image')){
                $file = $request->image;
                $file->move(base_path('uploads'),$file->getClientOriginalName());
                $request->merge(['image'=>$file->getClientOriginalName()]);
                $img = $file->getClientOriginalName();
            }else{
                $img = $request->img;
            }
            if($request->type!=1){
                $rules = [
                    'links' => 'required'
                ];
                $messages = [
                    'links.required' => 'Đường dẫn seo không được rỗng'
                ];
                $errors = Validator::make($request->all(),$rules,$messages);
                if($errors->fails()){
                    return response()->json(['errors'=>$errors->errors()->all()]);
                }
                $data = [
                    'title' => '',
                    'content' => '',
                    'links' => $request->links,
                    'status' => $request->status,
                    'type' => $request->type,
                    'image' => $img
                ];
                Banner::where('id',$request->hidden_id)->update($data);
                return response()->json(['success'=>'Cập nhật thành công']);
            }else{
                $rules = [
                    'title' => 'required',
                    'content' => 'required',
                    'links' => 'required'
                ];
                $messages = [
                    'title.required' => 'Tiêu đề không được rỗng',
                    'content.required' => 'Nội dung không được rỗng',
                    'links.required' => 'Đường dẫn seo không được rỗng'
                ];
                $errors = Validator::make($request->all(),$rules,$messages);
                if($errors->fails()){
                    return response()->json(['errors'=>$errors->errors()->all()]);
                }
                $data = [
                    'title' => $request->title,
                    'content' => $request->content,
                    'links' => $request->links,
                    'status' => $request->status,
                    'type' => $request->type,
                    'image' => $img
                ];
                Banner::where('id',$request->hidden_id)->update($data);
                return response()->json(['success'=>'Cập nhật thành công']);
            }
        }
        public function destroy($id){
            $data = Banner::findOrFail($id);
            $data->delete();
            return response()->json(['success'=>'Xoá thành công']);
        }
        public function toggle_edit(Request $request){
            $ban = Banner::find($request->id);
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
