<?php
    namespace App\Http\Controllers;
    use App\Helper\Cart;
    use App\Models\Stocks;
    use App\Models\Delivery;
    use App\Models\Payment;
    use App\Models\Orders;
    use App\Models\OrderDetail;
    use App\Models\Customers;
    use Illuminate\Http\Request;
    use App\Mail\SendMail;
    use Mail;
    use Validator;
    class CartController extends Controller
    {
        public function view(){
            return view('user/cart');
        }
        public function checkout(){
            $listPay = Payment::all();
            $listDeli = Delivery::all();
            return view('user/checkout',compact('listPay','listDeli'));
        }
        public function add(Request $request,Cart $cart){
            $quantity = $request->quantity;
            $quantity = (int)$quantity==(float)$quantity ?(int)$quantity :(float)$quantity;
            if($quantity<=0){
                return response()->json(['error1'=>'Tối thiểu 1 sản phẩm']);
            }else if(!is_numeric($quantity)){
                return response()->json(['error1'=>'Vui lòng nhập số nguyên lớn hơn 1']);
            }else if(is_float($quantity)){
                return response()->json(['error1'=>'Vui lòng nhập số nguyên lớn hơn 1']);
            }else if(is_int($quantity)){
                if(is_null($request->colorId)){
                    $product = Stocks::where('productId',$request->productId)
                ->whereNull('colorId')->where('sizeId',$request->sizeId)->first();
                    if($product){
                        if($product->importNum<=$quantity){
                            $quantity = $product->importNum;
                            $cart->add($product,$quantity);
                        }else if($product->importNum==0){
                            return response()->json(['error'=>'Sản phẩm đã hết hàng']);
                        }else{
                            $cart->add($product,$quantity);
                        }
                    }else{
                        return response()->json(['error'=>'Sản phẩm đã hết hàng']);
                    }
                }else{
                    $product = Stocks::where('productId',$request->productId)
                    ->where('colorId',$request->colorId)->where('sizeId',$request->sizeId)->first();
                    if($product){
                        if($product->importNum<=$quantity){
                            $quantity = $product->importNum;
                            $cart->add($product,$quantity);
                        }else if($product->importNum==0){
                            return response()->json(['error'=>'Sản phẩm đã hết hàng']);
                        }else{
                            $cart->add($product,$quantity);
                        }
                    }else{
                        return response()->json(['error'=>'Sản phẩm đã hết hàng']);
                    }
                }
            }
            return response()->json(['success'=>'Thêm giỏ hàng thành công']);
        }
        public function update(Request $request,Cart $cart){
            $id = $request->id;
            $product = Stocks::find($id);
            $quantity = $request->quantity;
            $quantity = (int)$quantity==(float)$quantity ?(int)$quantity :(float)$quantity;
            if($quantity<=0){
                return response()->json(['error'=>'Giá trị tối thiểu là 1']);
            }else if(!is_numeric($quantity)){
                return response()->json(['error'=>'Vui lòng nhập số nguyên lớn hơn 1']);
            }else if(is_float($quantity)){
                return response()->json(['error'=>'Vui lòng nhập số nguyên lớn hơn 1']);
            }else if(is_int($quantity)){
                if($product->importNum>$quantity){
                    $cart->update($id,$quantity);
                }else{
                    $cart->update($id,$product->importNum);
                }
                return response()->json(['success'=>'Cập nhật giỏ hàng thành công']);
            }
        }
        public function remove(Cart $cart,Request $request){
            $id = $request->id;
            $cart->remove($id);
            return response()->json(['success'=>'Xoá thành công']);
        }
        public function clear(Cart $cart,Request $request){
            $cart->clear();
            return response()->json(['success'=>'Xoá thành công']);
        }
        public function add_order(Request $request){
            $customer = Customers::find($request->customerId);
            if($customer){
                $rules = [
                    'r_name' => 'required',
                    'r_email' => 'required|email',
                    'r_address' => 'required',
                    'r_phone' => 'required',
                    'paymentId' => 'required',
                    'deliverId' => 'required'
                ];
                $messages = [
                    'r_name.required' => 'Tên người nhận không được rỗng',
                    'r_email.required' => 'Email người nhận không được rỗng',
                    'r_email.email' => 'Email không đúng định dạng',
                    'r_address.required' => 'Địa chỉ không được rỗng',
                    'r_phone.required' => 'Số điện thoại không được rỗng',
                    'paymentId.required' => 'Phải chọn phương thức thanh toán',
                    'deliverId.required' => 'Phải chọn phương thức giao hàng'
                ];
                $errors = Validator::make($request->all(),$rules,$messages);
                if($errors->fails()){
                    return response()->json(['errors'=>$errors->errors()->all()]);
                }else{
                    $data = [
                        'customerId' => $customer->id,
                        'paymentId' => $request->paymentId,
                        'deliverId' => $request->deliverId,
                        'name' => $request->r_name,
                        'email' => $request->r_email,
                        'address' => $request->r_address,
                        'phoneNumber' =>$request->r_phone,
                        'totalAmount' => $request->totalAmount,
                        'orderNote' => $request->orderNote
                    ];
                    $carts = $request->session()->get('cart');
                    $order = Orders::create($data);
                    if($order){
                        foreach ($carts as $cart) {
                            $orderDetail = new OrderDetail();
                            if($cart['color'] == ""){
                                $stock = Stocks::where('productId',$cart['id'])
                            ->whereNull('colorId')->where('sizeId',$cart['sizeId'])->first();
                            }else{
                                $stock = Stocks::where('productId',$cart['id'])
                                ->where('colorId',$cart['colorId'])->where('sizeId',$cart['sizeId'])->first();
                            }
                            $orderDetail->orderId = $order->id;
                            $orderDetail->stockId = $stock->id;
                            $orderDetail->quantity = $cart['quantity'];
                            $orderDetail->price = $cart['price'];
                            $orderDetail->save();
                            $data = [
                                'importNum' => $stock->importNum-$cart['quantity']
                            ];
                            $stock->update($data);
                        }
                        Mail::to($order->email)->send(new SendMail($order,$carts));
                        $request->session()->forget('cart');
                        return response()->json(['success'=>'Đặt hàng thành công']);
                    }else{
                        return response()->json(['error'=>'Lỗi xử lý']);
                    }
                }
            }else{
                return response()->json(['error'=>'Bạn chưa đăng nhập']);
            }
        }
    }


?>
