<?php
    namespace App\Http\Controllers\AdminController;
    use Validator;
    use App\Http\Controllers\Controller;
    use App\Models\Product;
    use App\Models\Category;
    use App\Models\Stocks;
    use App\Models\Color;
    use App\Models\Sizes;
    use App\Models\Review;
    use App\Models\WishList;;
    use Illuminate\Http\Request;

    class ProductController extends Controller
    {
        public function __construct(){
            $this->middleware('permission:product-list|product-create|product-update|product-delete',['only'=>['index','show']]);
            $this->middleware('permission:product-create',['only'=>['create','store']]);
            $this->middleware('permission:product-update',['only'=>['edit','update','toggle_edit']]);
            $this->middleware('permission:product-delete',['only'=>['destroy','multi_delete']]);
        }
        public function index(){
            $listPro = Product::orderBy('id','desc')->paginate(10);
            return view('admin/product/index',compact('listPro'));
        }
        public function toggle_edit(Request $request){
            $ban = Product::find($request->id);
            if($ban){
                $ban->status = $request->status;
                $ban->save();
                return response()->json(['success'=>'Cập nhật thành công']);
            }else{
                return response()->json(['error'=>'Cập nhật thất bại']);
            }
        }
        public function show($id){
            $pro = Product::find($id);
            $listStock = Stocks::where('productId',$id)->get();
            $listColor = Color::all();
            $listSize = Sizes::all();
            return view('admin/product/detail',compact('pro','listStock','listSize','listColor'));
        }
        public function create(){
            $listCat = Category::all();
            $listColor = Color::all();
            $listSize = Sizes::all();
            return view('admin/product/form-add',compact('listCat','listSize','listColor'));
        }
        public function store(Request $request){
            $rules = [
                'sizeId.*' => 'required',
                'importPrice.*' => 'required|numeric|min:0|not_in:0|lt:exportPrice.*',
                'importNum.*' => 'required||numeric|min:0|not_in:0',
                'name' => 'required|unique:product,name',
                'slug' => 'required|unique:product,slug',
                'discount' => 'required|numeric|min:0|not_in:0|lt:100',
                'exportPrice.*' => 'required|numeric|min:0|not_in:0',
                'description' => 'max:1000',
                'image' => 'required',
                'catalogId' => 'required'

            ];
            $messages = [
                'sizeId.*.required' => 'Kích cỡ không được rỗng',
                'importNum.*.required' => 'Số lượng nhập không được rỗng',
                'importNum.*.min' => 'Số lượng nhập >0',
                'importPrice.*.required' => 'Giá nhập không được rỗng',
                'importPrice.*.min' => 'Giá nhập lớn hơn 0',
                'importPrice.*.lt' => 'Giá nhập nhỏ hơn giá bán',
                'name.required' => 'Tên sản phẩm không được rỗng',
                'name.unique' => 'Tên sản phẩm đã tồn tại',
                'slug.required' => 'Đường dẫn Seo không được rỗng',
                'slug.unique' => 'Đường dẫn Seo đã tồn tại',
                'discount.required' => 'Khuyến mại không được rỗng',
                'discount.lt' => 'Khuyến mại nhỏ hơn 100',
                'exportPrice.*.required' => 'Giá không được rỗng',
                'exportPrice.*.min' => 'Giá lớn hơn 0',
                'description.max' => 'Mô tả nhỏ hơn 1000 ký tự',
                'image.required' => 'Ảnh không được rỗng',
                'catalogId.required' => 'Danh mục không được rỗng'
            ];
            $errors = Validator::make($request->all(),$rules,$messages);
            if($errors->fails()){
                return response()->json(['errors'=>$errors->errors()->all()]);
            }
            $img = str_replace(url('uploads').'/','',$request->image);
            $request->merge(['image'=>$img]);
            $form_data = [
                'name' => $request->name,
                'status' => $request->status,
                'catalogId' => $request->catalogId,
                'slug' =>$request->slug,
                'description' => $request->description,
                'discount' => $request->discount,
                'priority' => $request->priority,
                'proView' => $request->proView,
                'image' => $request->image,
                'image_list' => $request->image_list
            ];
            $pro = Product::create($form_data);
            $colorId = $request->colorId;
            $sizeId = $request->sizeId;
            $importPrice = $request->importPrice;
            $importNum = $request->importNum;
            $exportPrice = $request->exportPrice;
            for ($i=0; $i < count($sizeId); $i++) {
                $data = [
                    'colorId' => $colorId[$i],
                    'sizeId' => $sizeId[$i],
                    'importPrice' => $importPrice[$i],
                    'importNum' => $importNum[$i],
                    'exportPrice' => $exportPrice[$i],
                    'productId' =>$pro->id,
                    'status' => 1
                ];
                Stocks::create($data);
            }
            return response()->json(['success'=>'Thêm mới thành công']);
        }
        public function edit($id){
            $pro = Product::find($id);
            $listCat = Category::all();
            return view('admin/product/form-edit',compact('pro','listCat'));
        }
        public function update(Request $request,$id){
            $request->offsetUnset('_method');
            $request->offsetUnset('_token');
            $rules = [
                'name' => 'required|unique:product,name,'.$request->id,
                'slug' => 'required|unique:product,slug,'.$request->id,
                'discount' => 'required|numeric|min:0|not_in:0|lt:100',
                'description' => 'max:1000',
                'image' => 'required',
                'catalogId' => 'required'

            ];
            $messages = [
                'name.required' => 'Tên sản phẩm không được rỗng',
                'name.unique' => 'Tên sản phẩm đã tồn tại',
                'slug.required' => 'Đường dẫn Seo không được rỗng',
                'slug.unique' => 'Đường dẫn Seo đã tồn tại',
                'discount.required' => 'Khuyến mại không được rỗng',
                'discount.lt' => 'Khuyến mại nhỏ hơn 100',
                'description.max' => 'Mô tả nhỏ hơn 1000 ký tự',
                'image.required' => 'Ảnh không được rỗng',
                'catalogId.required' => 'Danh mục không được rỗng'
            ];
            $errors = Validator::make($request->all(),$rules,$messages);
            if($errors->fails()){
                return response()->json(['errors'=>$errors->errors()->all()]);
            }
            $img = str_replace(url('uploads').'/','',$request->image);
            $request->merge(['image'=>$img]);
            $form_data = [
                'name' => $request->name,
                'status' => $request->status,
                'catalogId' => $request->catalogId,
                'slug' =>$request->slug,
                'description' => $request->description,
                'discount' => $request->discount,
                'priority' => $request->priority,
                'proView' => $request->proView,
                'image' => $request->image,
                'image_list' => $request->image_list
            ];
            Product::where('id',$request->id)->update($form_data);
            return response()->json(['success'=>'Cập nhật thành công']);
        }
        public function destroy($id){
            $data = Product::findOrFail($id);
            $listRev = Review::where('productId',$data->id)->get();
            $listStock = Stocks::where('productId',$data->id)->get();
            $listWish = WishList::where('productId',$data->id)->get();
            if(count($listRev)>0 ||count($listStock)>0||count($listWish)>0){
                return response()->json(['errors'=>'Không thể xoá sản phẩm']);
            }else{
                $data->delete();
                return response()->json(['success'=>'Xoá thành công']);
            }
        }
    }

?>
