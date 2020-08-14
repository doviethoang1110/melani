<?php
    namespace App\Http\Controllers\AdminController;
    use App\Http\Controllers\Controller;
    use App\Models\Orders;
    use App\Models\OrderDetail;
    use Illuminate\Http\Request;
    use Validator;
    use Auth;
    class OrderDetailController extends Controller
    {
        public function __construct(){
            $this->middleware('permission:detail-list|detail-update|detail-delete',['only'=>['show']]);
            $this->middleware('permission:detail-update',['only'=>['update']]);
            $this->middleware('permission:detail-delete',['only'=>['delete']]);
        }
        public function show(Request $request){
            if($request->ajax()){
                $data = OrderDetail::findOrFail($request->id);
                return response()->json(['data'=>$data]);
            }
        }
        public function update(Request $request){
            $request->offsetUnset('_token');
            $request->offsetUnset('_method');
            $form_data = [
                'status' => $request->status
            ];
            OrderDetail::where('id',$request->id)->update($form_data);
            return response()->json(['success'=>'Cập nhật thành công']);
        }
        public function delete(Request $request){
            $order = OrderDetail::find($request->id);
            if($order){
                $order->delete();
                return response()->json(['success'=>'Xoá thành công']);
            }else{
                return response()->json(['error'=>'Không thể xoá']);
            }
        }
    }


?>
