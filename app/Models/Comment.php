<?php 
    namespace App\Models;
    use Illuminate\Database\Eloquent\Model;
    class Comment extends Model
    {
        protected $table = 'comment';
        protected $fillable = ['content','customerId','blogId','parentId','status'];
        public function cus(){
            return $this->hasOne(Customers::class,'id','customerId');
        }  
        public function blog(){
            return $this->hasOne(Blog::class,'id','blogId');
        } 
    }
    




?>