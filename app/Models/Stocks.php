<?php 
    namespace App\Models;
    use Illuminate\Database\Eloquent\Model;
    class Stocks extends Model
    {
        protected $table = 'stocks';
        protected $fillable = ['productId','colorId','sizeId','status','importPrice','importNum','exportPrice'];
        public function color(){
            return $this->hasOne(Color::class,'id','colorId');
        }
        public function size(){
            return $this->hasOne(Sizes::class,'id','sizeId');
        }
        public function pro(){
            return $this->hasOne(Product::class,'id','productId');
        }
        public function order(){
            return $this->hasMany(OrderDetail::class,'stockId','id');
        }
    }
    




?>