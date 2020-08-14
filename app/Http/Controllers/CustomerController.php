<?php 
    namespace App\Http\Controllers;
    use App\Models\Customers;
    use Illuminate\Http\Request;
    use App\Models\WishList;
    use App\Models\Orders;
    use App\Models\OrderDetail;
    use App\Models\Product;
    use App\Models\Stocks;
    use App\Models\Review;
    use DB;
    use Validator;
    class CustomerController extends Controller
    {
        public function create(){
            return view('user/login_register');
        }
        public function store(Request $request){
            $rules =[
                'name' => 'required|unique:customers,name',
                'email' => 'required|unique:customers,email|email',
                'password' => 'required|between:5,15',
                're_pass' => 'required|same:password',
                'address' => 'required',
                'phoneNumber' => 'required|unique:customers,phoneNumber'
            ];
            $messages = [
                'name.required' => 'Tên không được trống',
                'name.unique' => 'Tên này đã tồn tại',
                'email.required' =>'Email không được rỗng',
                'email.unique' => 'Email đã tồn tại',
                'email.email' => 'Email không đúng định dạng',
                'password.required' => 'Password không được rỗng',
                'password.between' => 'Password nhiều hơn 5 và nhỏ hơn 15 ký tự',
                're_pass.required' => 'Password nhập lại không được rỗng',
                're_pass.same' => 'Password nhập lại không giống password',
                'address.required' => 'Địa chỉ không được rỗng',
                'phoneNumber.required' => 'Số điện thoại không được rỗng',
                'phoneNumber.unique' => 'Số điện thoại đã tồn tại'
            ];
            $errors = Validator::make($request->all(),$rules,$messages);
            if($errors->fails()){
                return response()->json(['errors'=>$errors->errors()->all()]);
            }
            Customers::create($request->all());
            return response()->json(['success'=>'Đăng ký thành công']);
        }
        public function my_account(Request $request){
            $cus = Customers::find($request->id);
            $listWish = DB::table('wishList')
            ->where('customerId',$request->id)->join('product','product.id','=','wishList.productId')
            ->join('category','category.id','=','product.catalogId')
            ->join('stocks','stocks.productId','=','product.id')
            ->groupBy('stocks.productId')
            ->havingRaw('MIN(stocks.exportPrice)')
            ->select('product.name as proName','wishList.id as id','product.discount as discount','product.slug as slug','product.image as image','category.name as catName','stocks.exportPrice as price')
            ->get();
            $listOrd = Orders::where('customerId',$request->id)->get();
            return view('user/my_account',compact('listWish','listOrd','cus'));
        }
        public function edit_account(Request $request){
            $request->offsetUnset('_token');
            if($request->action == 'Edit'){
                $rules = [
                    'name' => 'required|unique:customers,name,'.$request->customerId,
                    'email' => 'required|email|unique:customers,email,'.$request->customerId,
                    'address' => 'required',
                    'phoneNumber' => 'required|exists:customers,phoneNumber'
                ];
                $messages = [
                    'name.required' => 'Tên không được trống',
                    'name.unique' => 'Tên đã tồn tại',
                    'email.required' =>'Email không được rỗng',
                    'email.email' => 'Email không đúng định dạng',
                    'email.unique' => 'Email đã tồn tại',
                    'phoneNumber.required' => 'Số điện thoại không được rỗng',
                    'phoneNumber.unique' => 'Số điện thoại đã tồn tại'
                ];
                $errors = Validator::make($request->all(),$rules,$messages);
                if($errors->fails()){
                    return response()->json(['errors'=>$errors->errors()->all()]);
                }
                $cus = Customers::where('id',$request->customerId)->first();
                $password = $cus->password;
                $form_data = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'phoneNumber' =>$request->phoneNumber,
                    'password' =>$password,
                    'address' =>$request->address,
                    'status' => $cus->status
                ];
                Customers::where('id',$cus->id)->update($form_data);
                return response()->json(['success'=>'Cập nhật thành công']);
            }
            if($request->action == 'Change'){
                $rules = [
                    'password' =>'required|between:5,15',
                    'new_pass' => 'required|between:5,15',
                    're_new_pass' => 'required|same:new_pass'
                ];
                $messages = [
                    'password.required' => 'Mật khẩu khỗng được rỗng',
                    'new_pass.required' => 'Mật khẩu mới không được rỗng',
                    'password.between' => 'Mật khẩu chứa 5 đến 15 ký tự',
                    'new_pass.between' => 'Mật khẩu chứa 5 đến 15 ký tự',
                    're_new_pass.required' => 'Mật khẩu không được rỗng',
                    're_new_pass.same' => 'Mật khẩu không khớp'
                ];
                $errors = Validator::make($request->all(),$rules,$messages);
                if($errors->fails()){
                    return response()->json(['errors'=>$errors->errors()->all()]);
                }
                $cus = Customers::where('id',$request->customerId)->first();
                if($cus->password != $request->password){
                    return response()->json(['error'=>'Mật khẩu cũ không khớp']);
                }
                $form_data = [
                    'password' =>$request->new_pass
                ];
                Customers::where('id',$cus->id)->update($form_data);
                return response()->json(['success'=>'Cập nhật thành công']);
            }
            if($request->action == 'upload'){
                if($request->avatar ==''){
                    return response()->json(['error'=>'Ảnh không được rỗng']);   
                }else{
                    $file = $request->avatar;
                    $file->move(base_path('uploads'),$file->getClientOriginalName());
                    $request->merge(['avatar'=>$file->getClientOriginalName()]);
                    $cus = Customers::where('id',$request->customerId)->first();
                    $data = [
                        'avatar' => $file->getClientOriginalName()
                    ];
                    Customers::where('id',$cus->id)->update($data);
                    return response()->json(['success'=>'Cập nhật thành công']);
                }
            }
        }
        public function add_wishList(Request $request){
            if($request->customerId == ''){
                return response()->json(['error'=>'Bạn chưa đăng nhập']);
            }else{
                $wish = WishList::where('customerId',$request->customerId)->where('productId',$request->productId)->first();
                if($wish){
                    return response()->json(['error1'=>'Sản phẩm đã có trong danh sách yêu thích']);
                }else{
                $data = [
                    'productId' => $request->productId,
                    'customerId' => $request->customerId
                ];
                WishList::create($data);
                return response()->json(['success'=>'Đã thêm vào danh sách yêu thích']);
                }
            }
        }
        public function remove_wish(Request $request){
            $id = $request->id;
            $wish = WishList::find($id);
            if($wish){
                $wish->delete();
                return response()->json(['success'=>'Xoá thành công']);
            }else{
                return response()->json(['error'=>'Không thể xoá']);
            }
        }
        public function compare(){
            return view('user/compare');
        }
        public function add_compare(Request $request){
            $items = session('pro') ? session('pro'):[];
            $listColor = [];
            $listSize = [];
            $pro = Product::where('id',$request->productId)->first();
            $listReview = Review::where('productId',$request->productId)->get();
            $t =0;
            foreach ($listReview as $rev) {
                $t += $rev->rating;
            }
            if(count($listReview)==0){
                $total = 0;
            }else{
                $total = (int)($t / count($listReview));
            }
            $listStock = Stocks::where('productId',$request->productId)->get();
            foreach ($listStock as $stock) {
                if($stock->colorId ==null){
                    $color = 'Không';
                }else{
                    $listColor[]=$stock->color->name;
                }
                $listSize[] = $stock->size->name;
            }
            $data = [
                'id' => $pro->id,
                'name' => $pro->name,
                'category' => $pro->cat->name,
                'image' => $pro->image,
                'description' => $pro->description,
                'color' => $listColor,
                'size' => $listSize,
                'review' => $total

            ];
            if(isset($items[$request->productId])){
                return response()->json(['error'=>'Sản phẩm đã có trong so sánh']);
            }
            if(count($items)>2){
                return response()->json(['error'=>'Danh sách so sánh đã đầy']);
            }
            $items[$request->productId] = $data;
            session(['pro'=>$items]);
            
            return response()->json(['success'=>'Thêm vào so sánh thành công']);
        }
        public function remove_compare(Request $request){
            $items = session('pro') ? session('pro'):[];
            unset($items[$request->id]);
            session(['pro'=>$items]);
            return response()->json(['success'=>'Xoá thành công']);
        }
        public function view_order(Request $request){
            $ord = OrderDetail::where('orderId',$request->id)->get();
            $data = [];
            if($request->ajax()){
                foreach ($ord as $item) {
                    $dat = [
                        'name' => $item->stock->pro->name,
                        'quantity' =>$item->quantity,
                        'price' => $item->price,
                        'status' => $item->status
                    ];
                    $data[] = $dat;
                }
                return response()->json(['data'=>$data]);
            }
        }
    }
    

?>