<?php

    namespace App\Http\Controllers\AdminController;
    use App\Models\Category;
    use App\Models\Product;
    use App\Models\Review;
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use Validator;
    class ReviewController extends Controller
    {
        public function __construct(){
            $this->middleware('permission:review-list|review-update|review-delete',['only'=>['index']]);
            $this->middleware('permission:review-update',['only'=>['edit','update','toggle_edit']]);
            $this->middleware('permission:review-delete',['only'=>['destroy','multi_delete']]);
        }
        public function index(){
            $listRev = Review::orderBy('created_at','desc')->paginate(5);
            return view('admin/review/index',compact('listRev'));
        }

        public function edit(Request $request,$id){
            if($request->ajax()){
                $data = Review::findOrFail($id);
                return response()->json(['data'=>$data]);
            }
        }

        public function destroy($id){
            $data = Review::findOrFail($id);
            if($data){
                $data->delete();
                return response()->json(['success'=>'Xoá thành công']);
            }else{
                return response()->json(['error'=>'Có lỗi xảy ra']);
            }
        }
        public function multi_delete(Request $request){
            $id = $request->id;
            $listCom = Review::whereIn('id',$id)->get();
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
            $ban = Review::find($request->id);
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
