<?php
    namespace App\Http\Controllers;
    use App\Models\Category;
    use App\Models\Product;
    use App\Models\Stocks;
    use App\Models\Comment;
    use App\Models\Banner;
    use App\Models\CategoryBlog;
    use App\Models\Blog;
    use Illuminate\Http\Request;
    use DB;
    use App\Models\Customers;
    use Validator;
    class PageController extends Controller
    {
        public function home(){
            $listProSale = DB::table('product')->join('stocks','product.id','=','stocks.productId')->select('product.*','stocks.exportPrice')->where('product.status',1)->groupBy('productId')->havingRaw('MIN(exportPrice)')->orderBy('discount','desc')->limit(6)->get();
            $listProBuy = DB::table('product')->join('stocks','product.id','=','stocks.productId')->select('product.*','stocks.exportPrice')->where('product.status',1)->where('priority',1)->groupBy('productId')->havingRaw('MIN(exportPrice)')->inRandomOrder()->limit(6)->get();
            $listProView = DB::table('product')->join('stocks','product.id','=','stocks.productId')->select('product.*','stocks.exportPrice')->where('product.status',1)->groupBy('productId')->havingRaw('MIN(exportPrice)')->orderBy('proView','desc')->limit(6)->get();
            $listProViewTop = DB::table('product')->join('stocks','product.id','=','stocks.productId')->select('product.*','stocks.exportPrice')->where('product.status',1)->groupBy('productId')->havingRaw('MIN(exportPrice)')->orderBy('proView','desc')->limit(12)->get();
            $listBlog = Blog::where('status',1)->limit(6)->get();
            $banner1 = Banner::where('type',1)->where('status',1)->get();
            $banner2 = Banner::where('type',2)->where('status',1)->first();
            $banner3 = Banner::where('type',3)->where('status',1)->limit(2)->get();
            $banner4 = Banner::where('type',4)->where('status',1)->first();
            return view('user/index',compact('listProSale','listProBuy','listProView','listProViewTop','listBlog','banner1','banner2','banner3','banner4'));

        }
        public function blog(){
            $listCatB = CategoryBlog::where('status',1)->get();
            $listBlog1 = Blog::where('status',1)->orderBy('created_at','desc')->limit(3)->get();
            $listBlog = BLog::orderBy('created_at','desc')->paginate(5);
            return view('user/blog',compact('listCatB','listBlog1','listBlog'));
        }
        public function blog_detail($slug){
            if($cat = CategoryBlog::where('slug',$slug)->first()){
                $listCatB = CategoryBlog::where('status',1)->get();
                $listBlog1 = Blog::where('status',1)->orderBy('created_at','desc')->limit(3)->get();
                $listBlog = BLog::where('catalogId',$cat->id)->orderBy('created_at','desc')->paginate(5);
                return view('user/blog',compact('listCatB','listBlog1','listBlog'));
            }else{
            $listCatB = CategoryBlog::where('status',1)->get();
            $blog = Blog::where('slug','like',$slug)->first();
            $listCom = Comment::where('status',1)->where('blogId',$blog->id)->get();
            $listBlog = Blog::where('status',1)->orderBy('created_at','desc')->limit(3)->get();
            return view('user/blog_detail',compact('blog','listCatB','listBlog','listCom'));
            }
        }
        public function add_comment(Request $request){
            if($request->action == "Comment"){
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
                            'comment' => 'required'
                        ];
                        $messages = [
                            'name.required' => 'Tên khỗng được rỗng',
                            'email.required' => 'Email mới không được rỗng',
                            'comment.required' => 'Nội dung không rỗng'
                        ];
                        $errors = Validator::make($request->all(),$rules,$messages);
                        if($errors->fails()){
                            return response()->json(['errors'=>$errors->errors()->all()]);
                        }
                        $form_data = [
                            'blogId' => $request->id,
                            'customerId' => $cus->id,
                            'content' => $request->comment,
                            'parentId' => 0,
                            'status' => 0
                        ];
                        Comment::create($form_data);
                        return response()->json(['success'=>'Bình luận đã được gửi']);
                    }else{
                        return response()->json(['error'=>'Tài khoản không tồn tại,vui lòng đăng nhập']);
                    }
                }
            }else if($request->action == "Reply"){
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
                            'comment' => 'required'
                        ];
                        $messages = [
                            'name.required' => 'Tên khỗng được rỗng',
                            'email.required' => 'Email mới không được rỗng',
                            'comment.required' => 'Nội dung không rỗng'
                        ];
                        $errors = Validator::make($request->all(),$rules,$messages);
                        if($errors->fails()){
                            return response()->json(['errors'=>$errors->errors()->all()]);
                        }
                        $form_data = [
                            'blogId' => $request->id,
                            'customerId' => $cus->id,
                            'content' => $request->comment,
                            'parentId' => $request->parentId
                        ];
                        Comment::create($form_data);
                        return response()->json(['success'=>'Bình luận đã được gửi']);
                    }else{
                        return response()->json(['error'=>'Tài khoản không tồn tại,vui lòng đăng nhập']);
                    }
                }
            }
        }
        public function aboutUs(){

            return view('user/about_us');
        }
        public function shop(){
            $listPro = DB::table('product')->join('stocks','product.id','=','stocks.productId')->select('product.*','stocks.exportPrice')->where('product.status',1)->groupBy('productId')->havingRaw('MIN(exportPrice)')->paginate(9);
            $banner4 = Banner::where('type',4)->where('status',1)->first();
            $proPopular = DB::table('product')->join('stocks','product.id','=','stocks.productId')->select('product.*','stocks.exportPrice')->where('product.status',1)->groupBy('productId')->havingRaw('MIN(exportPrice)')->where('product.proView','>',500)->inRandomOrder()->limit(3)->get();
            return view('user/shop_grid',compact('listPro','proPopular','banner4'));
        }
        public function fetch(Request $request){
            if($request->get('query')){
                $query = $request->get('query');
                $data = Product::where('name','like','%'.$query.'%')->where('status',1)->get();
                $output = '<ul class="dropdown" style="display:block;position:relative">';
                foreach ($data as $row) {
                    $output .= '<li id="fetch" class="media"><a href="'.route('shop_detail',$row->slug).'"><img src="'.url('uploads').'/'.$row->image.'" width="50px"/><a href="'.route('shop_detail',$row->slug).'">'.$row->name.'</a></a></li>';
                }
                $output .= '</ul>';
                echo $output;
            }
        }
        public function login(Request $request){
            $rules = [
                'name' => 'required',
                'password' => 'required'
            ];
            $messages = [
                'name.required' => 'Tên không được rỗng',
                'password.required' => 'Mật khẩu không được rỗng'
            ];
            $errors = Validator::make($request->all(),$rules,$messages);
            if($errors->fails()){
                return response()->json(['errors'=>$errors->errors()->all()]);
            }
            $cus = Customers::where('name','like',$request->name)->where('password','like',$request->password)->first();
            if(empty($cus)){
                return response()->json(['error'=>'Tài khoản không tồn tại']);
            }else{
                $request->session()->put('customer',$cus);
                return response()->json(['success'=>'Đăng nhập thành công']);
            }

        }
        public function logout(Request $request){
            $request->session()->forget('customer');
            return response()->json(['success'=>'Đăng xuất thành công']);
        }


    }




?>
