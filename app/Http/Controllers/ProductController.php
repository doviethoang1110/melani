<?php
    namespace App\Http\Controllers;
    use App\Models\Category;
    use App\Models\Product;
    use App\Models\ImageList;
    use App\Models\Stocks;
    use App\Models\Banner;
    use App\Models\Color;
    use App\Models\Customers;
    use App\Models\Sizes;
    use App\Models\Review;
    use DB;
    use Illuminate\Http\Request;
    use Validator;
    class ProductController extends Controller
    {
        public function product_detail($slug){
            $listSize=[];
            $listColor=[];
            $pro = DB::table('product')->join('stocks','product.id','=','stocks.productId')->select('product.*','stocks.exportPrice')->where('slug','=',$slug)->where('product.status',1)->first();
            $listReview = Review::where('status',1)->where('productId',$pro->id)->get();
            $listProCat = DB::table('product')->join('stocks','product.id','=','stocks.productId')->select('product.*','stocks.exportPrice')->where('product.status',1)->where('catalogId','=',$pro->catalogId)->groupBy('productId')->havingRaw('MIN(exportPrice)')->inRandomOrder()->limit(6)->get();
            $proPopular = DB::table('product')->join('stocks','product.id','=','stocks.productId')->select('product.*','stocks.exportPrice')->where('product.status',1)->groupBy('productId')->havingRaw('MIN(exportPrice)')->where('product.proView','>',500)->inRandomOrder()->limit(3)->get();
            $t =0;
            foreach ($listReview as $rev) {
                $t += $rev->rating;
            }
            if(count($listReview)==0){
                $total = 0;
            }else{
                $total = (int)($t / count($listReview));
            }
            $banner4 = Banner::where('type',4)->where('status',1)->first();
            if(!empty($pro)){
            $listStockSize = Stocks::where('productId','=',$pro->id)->distinct('sizeId')->get('sizeId');
            $listStockColor = Stocks::where('productId','=',$pro->id)->distinct('colorId')->get('colorId');
            for ($i=0; $i < $listStockSize->count(); $i++) { 
                $size = Sizes::where("id","=",$listStockSize[$i]->sizeId)->first();
                $listSize[] = $size;
            }
            for ($i=0; $i < $listStockColor->count(); $i++) { 
                $color = Color::where("id","=",$listStockColor[$i]->colorId)->first();
                if($color==null){
                    $listColor = [];
                }else{
                    $listColor[] = $color;
                }
            }
            return view('user/product_detail',compact('listProCat','pro','listSize','listColor','proPopular','listReview','banner4','total'));
            }
        }

        public function product_order(Request $request){
            $proPopular = DB::table('product')->join('stocks','product.id','=','stocks.productId')->select('product.*','stocks.exportPrice')->where('product.status',1)->groupBy('productId')->havingRaw('MIN(exportPrice)')->where('product.proView','>',500)->inRandomOrder()->limit(3)->get();
            $banner4 = Banner::where('type',4)->where('status',1)->first();
            if(!empty($request)){
                $field = !empty($request['field']) ? $request['field'] : '';
                $attr = !empty($request['attr']) ? $request['attr'] :'';
                $listPro = DB::table('product')->join('stocks','product.id','=','stocks.productId')->select('product.*','stocks.exportPrice')->where('product.status',1)->orderBy($field,$attr)->groupBy('productId')->havingRaw('MIN(exportPrice)')->paginate(9);
                return view('user/shop_grid',compact('listPro','proPopular','banner4'));
            }
            else{
                $listPro = DB::table('product')->join('stocks','product.id','=','stocks.productId')->select('product.*','stocks.exportPrice')->where('product.status',1)->orderBy($field,$attr)->groupBy('productId')->havingRaw('MIN(exportPrice)')->paginate(9);
                return view('user/shop_grid',compact('listPro','proPopular','banner4'));
            }
        }
        public function pro_cat($slug){
            $listCats = [];
            $pro = Product::where('proView','>',500)->inRandomOrder()->limit(3)->get();
            $proPopular = DB::table('product')->join('stocks','product.id','=','stocks.productId')->select('product.*','stocks.exportPrice')->where('product.status',1)->groupBy('productId')->havingRaw('MIN(exportPrice)')->where('product.proView','>',500)->inRandomOrder()->limit(3)->get();
            $cat = Category::where('slug','like',$slug)->first();
            $banner4 = Banner::where('type',4)->where('status',1)->first();
            array_push($listCats, $cat->id);
            $listCat = Category::select('id')->where('parentId',$cat->id)->get();
            foreach ($listCat as $cats) {
                array_push($listCats, $cats['id']);
                $listCat1 = Category::select('id')->where('parentId',$cats['id'])->get();
                foreach ($listCat1 as $c) {
                    array_push($listCats, $c['id']);
                }
            }
            $listPro = DB::table('product')->join('stocks','product.id','=','stocks.productId')->select('product.*','stocks.exportPrice')->where('product.status',1)->whereIn('catalogId',$listCats)->groupBy('productId')->havingRaw('MIN(exportPrice)')->paginate(9);
            return view('user/shop_grid',compact('listPro','proPopular','cat','banner4'));

        }
        public function add_review(Request $request){
            if($request->name == null){
                return response()->json(['error'=>'Tên không được rỗng']);
            }else if($request->email == null){
                return response()->json(['error'=>'Email không được rỗng']);
            }else{
                $cus = Customers::where('name','like',$request->name)->where('email','like',$request->email)->first();
                if($cus){
                    $rules = [
                        'name' =>'required',
                        'email' => 'required',
                        'content' => 'required',
                        'rating' =>'required'
                    ];
                    $messages = [
                        'name.required' => 'Tên khỗng được rỗng',
                        'email.required' => 'Email mới không được rỗng',
                        'rating.required' => 'Đánh giá không được rỗng',
                        'content.required' => 'Nội dung không rỗng'
                    ];
                    $errors = Validator::make($request->all(),$rules,$messages);
                    if($errors->fails()){
                        return response()->json(['errors'=>$errors->errors()->all()]);
                    }
                    $form_data = [
                        'productId' => $request->id,
                        'customerId' => $cus->id,
                        'rating' => $request->rating,
                        'content' => $request->content,
                        'status' =>0
                    ];
                    Review::create($form_data);
                    return response()->json(['success'=>'Đánh giá đã được gửi']);
                }else{
                    return response()->json(['error'=>'Tài khoản không tồn tại,vui lòng đăng nhập']);
                }
            }
        }
    }
    



?>