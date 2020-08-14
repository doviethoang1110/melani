<?php 
    namespace App\Http\Controllers\AdminController;
    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use App\Models\Product;
    use App\Models\Orders;
    use App\Models\Customers;
    use DB;
    use Carbon\Carbon;
    use Auth;
    class AdminController extends Controller
    {
        public function index(){
            $listOrd = Orders::all()->count();
            $totalAmount = Orders::select(DB::raw('sum(totalAmount) as total'))->first();
            $orders = Orders::select(
                DB::raw('sum(totalAmount) as sums'), 
                DB::raw("DATE_FORMAT(created_at,'%m') as monthKey")
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('monthKey')
            ->orderBy('created_at', 'ASC')
            ->get();
            $data = [0,0,0,0,0,0,0,0,0,0,0,0];
            foreach($orders as $order){
                $data[$order->monthKey-1] = $order->sums;
            }
            $orderArr = Orders::select('id', 'created_at')
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('m');
            });
            $orderCount = [];
            $orderMonth = [];
            foreach ($orderArr as $key => $value) {
                $orderCount[(int)$key] = count($value);
            }

            for($i = 1; $i <= 12; $i++){
                if(!empty($orderCount[$i])){
                    $orderMonth[] = $orderCount[$i];    
                }else{
                    $orderMonth[] = 0;    
                }
            }
            $listCus = Customers::all();
            $listPro = Product::all();
            return view('admin/index',compact('listOrd','listCus','listPro','totalAmount','orderMonth','data'));
        }
        public function login(){
            return view('admin/login');
        }
        public function post_login(Request $request){
            $this->validate($request,[
                'name' => 'required',
                'password' => 'required'
            ],[
                'name.required' => 'Tên không được rỗng',
                'password.required' => 'Password không được rỗng'
            ]);
            if(Auth::attempt($request->only('name','password'),$request->has('remember'))){
                return redirect()->route('admin.index');
            }else{
                return redirect()->back();
            }
        }
        public function logout(){
            Auth::logout();
            return redirect()->route('admin.login');
        }
    }
    

?>