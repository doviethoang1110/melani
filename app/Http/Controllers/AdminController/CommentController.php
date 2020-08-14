<?php
    namespace App\Http\Controllers\AdminController;
    use App\Http\Controllers\Controller;
    use App\Models\Comment;
    use Illuminate\Http\Request;
    use Validator;
    use Auth;
    class CommentController extends Controller
    {
        public function __construct(){
            $this->middleware('permission:comment-list|comment-update|comment-delete',['only'=>['index']]);
            $this->middleware('permission:comment-update',['only'=>['edit','update','toggle_edit']]);
            $this->middleware('permission:comment-delete',['only'=>['destroy','multi_delete']]);
        }
        public function index(){
            $listCom = Comment::orderBy('created_at','desc')->paginate(5);
            return view('admin/comment/index',compact('listCom'));
        }
        public function edit(Request $request,$id){
            if($request->ajax()){
                $data = Comment::findOrFail($id);
                return response()->json(['data'=>$data]);
            }
        }
        public function destroy($id){
            $data = Comment::findOrFail($id);
            if($data){
                $data->delete();
                return response()->json(['success'=>'Xoá thành công']);
            }else{
                return response()->json(['error'=>'Có lỗi xảy ra']);
            }
        }
        public function multi_delete(Request $request){
            $id = $request->id;
            $listCom = Comment::whereIn('id',$id)->get();
            if($listCom){
                foreach ($listCom as $value) {
                    $value->delete();
                }
                return response()->json(['success'=>'Xoá thành công']);
            }else{
                return response()->json(['error'=>'Xoá thất bại']);
            }
        }
        public function toggle_edit(Request $request){
            $ban = Comment::find($request->id);
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
