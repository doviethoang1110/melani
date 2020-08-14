<?php 
    namespace App\Models;
    use Illuminate\Database\Eloquent\Model;
    class Product extends Model
    {
        protected $table = 'product';
        protected $fillable = ['name','slug','catalogId','status','priority','proView','image','description','discount','image_list'];
        public function cat(){
            return $this->hasOne(Category::class,'id','catalogId');
        }  
    }
    




?>