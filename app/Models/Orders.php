<?php 
    namespace App\Models;
    use Illuminate\Database\Eloquent\Model;
    class Orders extends Model
    {
        protected $table = 'orders';
        protected $fillable = ['customerId','paymentId','deliverId','name','email','address','phoneNumber','totalAmount','orderNote','status'];
        public function cus(){
            return $this->hasOne(Customers::class,'id','customerId');
        } 
        public function pay(){
            return $this->hasOne(Payment::class,'id','paymentId');
        }  
    }
    




?>