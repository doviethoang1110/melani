<?php 
    namespace App\Models;
    use Illuminate\Database\Eloquent\Model;
    class OrderDetail extends Model
    {
        protected $table = 'orderDetail';
        protected $fillable = ['orderId','stockId','quantity','price','status'];
        public function stock(){
            return $this->hasOne(Stocks::class,'id','stockId');
        }  
    }
    




?>