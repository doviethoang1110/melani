<?php 
    namespace App\Models;
    use Illuminate\Database\Eloquent\Model;
    class Review extends Model
    {
        protected $table = 'review';
        protected $fillable = ['productId','customerId','rating','content','status'];
        public function cus(){
            return $this->hasOne(Customers::class,'id','customerId');
        }  
        public function pro(){
            return $this->hasOne(Product::class,'id','productId');
        }  
    }
?>