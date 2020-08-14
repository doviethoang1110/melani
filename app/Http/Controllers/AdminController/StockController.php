<?php
    namespace App\Http\Controllers\AdminController;
    use App\Models\Stocks;
    use App\Models\Product;
    use App\Models\Color;
    use App\Models\Sizes;
    use App\Models\OrderDetail;
    use Illuminate\Http\Request;
    use App\Http\Controllers\Controller;
    use Validator;
    use DB;
    class StockController extends Controller
    {
        public function __construct(){
            $this->middleware('permission:stock-list|stock-create|stock-update|stock-delete',['only'=>['index','stock_filter']]);
            $this->middleware('permission:stock-create',['only'=>['store','insert_stocks']]);
            $this->middleware('permission:stock-update',['only'=>['edit_stocks','update_stocks']]);
            $this->middleware('permission:stock-delete',['only'=>['stock_delete']]);
        }
        public function index(){
            $listStock = Stocks::orderBy('id','desc')->paginate(5);
            $listColor = Color::all();
            $listSize = Sizes::all();
            return view('admin/stock/index',compact('listStock','listColor','listSize'));
        }
        public function insert_stocks(Request $request){
            if($request->ajax()){
                $rules = [
                    'sizeId.*' => 'required',
                    'importPrice.*' => 'required|numeric|min:0|not_in:0|lt:exportPrice.*',
                    'exportPrice.*' => 'required|numeric|min:0|not_in:0',
                    'importNum.*' => 'required||numeric|min:0|not_in:0',
                ];
                $messages = [
                    'sizeId.*.required' => 'Kích cỡ không được rỗng',
                    'importNum.*.required' => 'Số lượng nhập không được rỗng',
                    'importNum.*.min' => 'Số lượng nhập >0',
                    'importPrice.*.required' => 'Giá nhập không được rỗng',
                    'importPrice.*.min' => 'Giá nhập lớn hơn 0',
                    'importPrice.*.lt' => 'Giá nhập nhỏ hơn giá bán',
                    'exportPrice.*.required' => 'Giá bán không được rỗng',
                    'exportPrice.*.min' => 'Giá bán lớn hơn 0'
                ];
                $errors = Validator::make($request->all(),$rules,$messages);
                if($errors->fails()){
                    return response()->json(['errors'=>$errors->errors()->all()]);
                }
                $sizeId = $request->sizeId;
                $colorId = $request->colorId;
                $importPrice = $request->importPrice;
                $importNum = $request->importNum;
                $exportPrice = $request->exportPrice;
                for ($i=0; $i < count($sizeId); $i++) {
                    $data = [
                        'productId' => $request->productId,
                        'sizeId' => $request->sizeId[$i],
                        'colorId' => $request->colorId[$i],
                        'importPrice' =>$request->importPrice[$i],
                        'importNum' => $request->importNum[$i],
                        'exportPrice' => $request->exportPrice[$i]
                    ];
                    Stocks::create($data);
                }
                return response()->json(['success'=>'Thêm mới thành công']);

            }
        }
        public function store(Request $request){
            $rules = [
                'sizeId.*' => 'required',
                'importPrice.*' => 'required|numeric|min:0|not_in:0|lt:exportPrice.*',
                'exportPrice.*' => 'required|numeric|min:0|not_in:0',
                'importNum.*' => 'required||numeric|min:0|not_in:0',
            ];
            $messages = [
                'sizeId.*.required' => 'Kích cỡ không được rỗng',
                'importNum.*.required' => 'Số lượng nhập không được rỗng',
                'importNum.*.min' => 'Số lượng nhập >0',
                'importPrice.*.required' => 'Giá nhập không được rỗng',
                'importPrice.*.min' => 'Giá nhập lớn hơn 0',
                'importPrice.*.lt' => 'Giá nhập nhỏ hơn giá bán',
                'exportPrice.*.required' => 'Giá bán không được rỗng',
                'exportPrice.*.min' => 'Giá bán lớn hơn 0'
            ];
            $errors = Validator::make($request->all(),$rules,$messages);
            if($errors->fails()){
                return response()->json(['errors'=>$errors->errors()->all()]);
            }
            $sizeId = $request->sizeId;
            $colorId = $request->colorId;
            $importPrice = $request->importPrice;
            $importNum = $request->importNum;
            $exportPrice = $request->exportPrice;
            for ($i=0; $i < count($sizeId); $i++) {
                $data = [
                    'productId' => $request->productId,
                    'sizeId' => $request->sizeId[$i],
                    'colorId' => $request->colorId[$i],
                    'importPrice' =>$request->importPrice[$i],
                    'importNum' => $request->importNum[$i],
                    'exportPrice' => $request->exportPrice[$i]
                ];
                Stocks::create($data);
            }
            return response()->json(['success'=>'Thêm mới thành công']);
        }
        public function edit_stocks(Request $request){
            if($request->ajax()){
                $stock = Stocks::findOrFail($request->id);
                $data =[
                    'id' => $stock->id,
                    'productId' => $stock->pro->id,
                    'name' => $stock->pro->name,
                    'importNum' =>$stock->importNum,
                    'importPrice' => $stock->importPrice,
                    'exportPrice' => $stock->exportPrice,
                    'colorId' =>$stock->colorId,
                    'sizeId' =>$stock->sizeId,
                    'status' => $stock->status
                ];
                return response()->json(['data'=>$data]);
            }
        }
        public function update_stocks(Request $request){
            $request->offsetUnset('_token');
            $id = $request->hidden_id;
            $rules = array(
                'importNum' => 'required|integer|min:1|' ,
                'importPrice' => 'required|numeric|min:0|not_in:0|lt:exportPrice',
                'exportPrice' => 'required|numeric|min:0|not_in:0'
            );
            $messages = array(
                'importNum.required' => 'Không được để trống số lượng nhập',
                'importNum.integer' => 'Số lượng nhập lớn hơn 1',
                'importPrice.required' => 'Giá nhập không được  trống',
                'importPrice.min' => 'Giá nhập lớn hơn 0',
                'importPrice.lt' => 'Giá nhập nhỏ hơn giá bán',
                'exportPrice.required' => 'Giá bán không được trống',
                'exportPrice.min' => 'Giá bán lớn hơn 0'
            );
            $errors = Validator::make($request->all(),$rules,$messages);
            if($errors->fails()){
                return response()->json(['errors'=>$errors->errors()->all()]);
            }
            $form_data = [
                'productId' => $request->productId,
                'status' =>$request->status,
                'importPrice' =>$request->importPrice,
                'exportPrice' =>$request->exportPrice,
                'importNum' =>$request->importNum,
                'colorId' => $request->colorId,
                'sizeId' => $request->sizeId
            ];
            Stocks::where('id',$id)->update($form_data);
            return response()->json(['success'=>'Cập nhật thành công.']);
        }
        public function stock_delete(Request $request){
            $data = Stocks::findOrFail($request->id);
            $listOrd = OrderDetail::where('stockId',$data->id)->get();
            if(count($listOrd)>0){
                return response()->json(['errors'=>'Không thể xoá']);
            }else{
                $data->delete();
                return response()->json(['success'=>'Xoá thành công']);
            }
        }
        public function fetch(Request $request){
            if($request->colorId == null){
                $stock = Stocks::where('productId',$request->productId)
            ->whereNull('colorId')->where('sizeId',$request->sizeId)->first();
            }else{
                $stock = Stocks::where('productId',$request->productId)
                ->where('colorId',$request->colorId)->where('sizeId',$request->sizeId)->first();
            }
            if(!empty($stock)){
                $data = [
                    'discount' => $stock->pro->discount,
                    'price' => number_format($stock->exportPrice-($stock->pro->discount*$stock->exportPrice)/100),
                    'importNum' => $stock->importNum,
                    'exportPrice' => number_format($stock->exportPrice)
                ];
                return response()->json(['data'=>$data]);
            }else{
                return response()->json(['out'=>'Sản phẩm hết hàng']);
            }
        }
        public function stock_filter(Request $request){
            if($request['val'] == 1){
                $listStock = Stocks::where('importNum','>',5)->paginate(5);
                    $listColor = Color::all();
                    $listSize = Sizes::all();
                    return view('admin/stock/index',compact('listStock','listColor','listSize'));
            }else if($request['val'] ==2){
                $listStock = Stocks::where('importNum','>',0)->where('importNum','<=',5)->paginate(5);
                    $listColor = Color::all();
                    $listSize = Sizes::all();
                    return view('admin/stock/index',compact('listStock','listColor','listSize'));
            }else if($request['val'] ==3){
                    $listStock = Stocks::select('stocks.*')->join('orderDetail','orderDetail.stockId','=','stocks.id')
                    ->groupBy('orderDetail.stockId')->havingRaw('SUM(orderDetail.quantity) > ?',[5])->paginate(5);
                    $listColor = Color::all();
                    $listSize = Sizes::all();
                    return view('admin/stock/index',compact('listStock','listColor','listSize'));
            }else if($request['val'] == 4){
                $listStock = Stocks::where('importNum','=',0)->paginate(5);
                    $listColor = Color::all();
                    $listSize = Sizes::all();
                    return view('admin/stock/index',compact('listStock','listColor','listSize'));
            }

        }
    }

?>
