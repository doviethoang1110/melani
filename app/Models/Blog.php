<?php 
    namespace App\Models;
    use Illuminate\Database\Eloquent\Model;
    class Blog extends Model
    {
        protected $table = 'blog';
        protected $fillable = ['catalogId','slug','title','notes','description','image','status'];
        public function blog(){
            return $this->hasOne(CategoryBlog::class,'id','catalogId');
        }
    }
?>