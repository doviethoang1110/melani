<?php
    namespace App\Http\Controllers\AdminController;
    use App\Http\Controllers\Controller;
    use App\Models\Orders;
    use App\Models\OrderDetail;
    use Illuminate\Http\Request;
    use Validator;
    use Auth;
    class OrderController extends Controller
    {
        public function __construct(){
            $this->middleware('permission:order-list|order-update|order-delete',['only'=>['index','show','fetch_orders']]);
            $this->middleware('permission:order-update',['only'=>['edit','update']]);
            $this->middleware('permission:order-delete',['only'=>['destroy']]);
        }
        public function index(){
            $listOrd = Orders::orderBy('created_at','desc')->paginate(5);
            return view('admin/order/index',compact('listOrd'));
        }
        public function show($id){
            $listOrdet = OrderDetail::where('orderId',$id)->get();
            return view('admin/order/detail',compact('listOrdet'));
        }
        public function edit(Request $request,$id){
            if($request->ajax()){
                $data = Orders::findOrFail($id);
                return response()->json(['data'=>$data]);
            }
        }
        public function update(Request $request,$id){
            $request->offsetUnset('_token');
            $request->offsetUnset('_method');
            $rules = [
                'name' => 'required',
                'address' => 'required',
                'phoneNumber' => 'required',
                'status' => 'required'
            ];
            $messages = [
                'name.required' => 'Tên không được rỗng',
                'address.required' => 'Địa chỉ không được rỗng',
                'phoneNumber.required' => 'Số điện thoại không được rỗng',
                'status.required' => 'Trạng thái không được rỗng'
            ];
            $errors = Validator::make($request->all(),$rules,$messages);
            if($errors->fails()){
                return response()->json(['errors'=>$errors->errors()->all()]);
            }
            $form_data = [
                'name' => $request->name,
                'address' => $request->address,
                'phoneNumber' => $request->phoneNumber,
                'status' => $request->status
            ];
            Orders::where('id',$id)->update($form_data);
            return response()->json(['success'=>'Cập nhật thành công']);
        }
        public function destroy($id){
            $data = Orders::findOrFail($id);
            $listOrd = OrderDetail::where('orderId',$data->id)->get();
            if(count($listOrd)>0){
                return response()->json(['errors'=>'Đơn hàng không thể xoá']);
            }else{
                $data->delete();
                return response()->json(['success'=>'Xoá thành công']);
            }
        }
        public function fetch_orders(Request $request){
            if($request->ajax()){
                $data = [];
                $ord = Orders::where('status',$request->status)->orderBy('created_at','desc')->get();
                foreach ($ord as $item) {
                    $item = [
                        'id' =>$item->id,
                        'c_name' => $item->cus->name,
                        'name' => $item->name,
                        'pay' => $item->pay->name,
                        'amount' => $item->totalAmount,
                        'status' => $item->status,
                        'created' => $item->created_at->format('d-m-y')
                    ];
                    $data[] = $item;
                }
                return response()->json(['data'=>$data]);
            }
        }

    }


?>
