<?php 
    namespace App\Models;
    use Illuminate\Database\Eloquent\Model;
    class WishList extends Model
    {
        protected $table = 'wishList';
        protected $fillable = ['productId','customerId','status'];  
        public function pro(){
            return $this->hasOne(Product::class,'id','productId');
        }  
    }
    




?>