<?php 
    namespace App\Models;
    use Illuminate\Database\Eloquent\Model;
    class Category extends Model
    {
        protected $table = 'category';
        protected $fillable = ['name','slug','parentId','status'];
        public function product(){
            return $this->hasMany(Product::class,'catalogId','id');
        }
        public function getChildValues() {
            return $this->hasMany(Category::class, 'parentId');
        }     
    
        public function getParentValues() {
            return $this->belongsTo(Category::class, 'id');
        } 
    }
    




?>