<?php 
    namespace App\Models;
    use Illuminate\Database\Eloquent\Model;
    class CategoryBlog extends Model
    {
        protected $table = 'categoryBlog';
        protected $fillable = ['name','slug','status'];
    }
    




?>