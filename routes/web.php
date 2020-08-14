<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('home','PageController@home')->name('home');
Route::get('blog','PageController@blog')->name('blog');
Route::get('blog/{slug}','PageController@blog_detail')->name('blog.detail');
Route::get('about-us','PageController@aboutUs')->name('about-us');
Route::get('shop','PageController@shop')->name('shop');
Route::get('contact','SendEmailController@contact')->name('contact');
Route::post('contact','SendEmailController@send_email')->name('send.email');



Route::get('{slug}','ProductController@product_detail')->name('shop_detail');
Route::post('review', 'ProductController@add_review')->name('product.review');
Route::post('blog/comment', 'PageController@add_comment')->name('blog.comment');
Route::post('stock/fetch', 'AdminController\StockController@fetch')->name('stock.fetch');
Route::get('add/compare','CustomerController@compare')->name('compare');
Route::post('add_compare','CustomerController@add_compare')->name('add.compare');
Route::post('remove_compare','CustomerController@remove_compare')->name('remove.compare');


Route::get('modal/{slug}','ProductController@pro_modal');
Route::get('sortBy/{field}/{attr}','ProductController@product_order')->name('sortBy');
Route::get('shop/{slug}','ProductController@pro_cat')->name('pro_cat');
Route::get('admin/login','AdminController\AdminController@login')->name('login');
Route::post('admin/login','AdminController\AdminController@post_login')->name('admin.login');
Route::post('data/fetch','PageController@fetch')->name('data.fetch');
Route::group(['prefix' => ''], function () {
    Route::resource('customers', 'CustomerController');
    Route::post('customers/login','PageController@login')->name('customer.login');
    Route::post('customers/logout','PageController@logout')->name('customer.logout');
    Route::post('customer/my_account', 'CustomerController@my_account')->name('my_account');
    Route::post('customer/my_account/edit', 'CustomerController@edit_account')->name('account.edit');
    Route::post('customer/add_wishList','CustomerController@add_wishList')->name('add.wishList');
    Route::post('customer/remove_wish','CustomerController@remove_wish')->name('remove.wish');
    Route::post('customer/view_order','CustomerController@view_order')->name('order.view');
});
Route::group(['prefix' => 'admin','middleware'=>'auth'], function () {
    Route::get('file', 'UserController@index_file')->name('file.index');
    Route::get('home','AdminController\AdminController@index')->name('admin.index');
    Route::get('logout','AdminController\AdminController@logout')->name('logout');
    Route::get('comment/multi', 'AdminController\CommentController@multi_delete')->name('comment.multi');
    Route::get('review/multi', 'AdminController\ReviewController@multi_delete')->name('review.multi');
    Route::get('permission/multi', 'AdminController\PermissionController@multi_delete')->name('permission.multi');
    Route::post('banner/update','AdminController\BannerController@toggle_edit')->name('banner.toggle');
    Route::post('product/update','AdminController\ProductController@toggle_edit')->name('product.toggle');
    Route::post('review/update','AdminController\ReviewController@toggle_edit')->name('review.toggle');
    Route::post('catBlog/update','AdminController\CategoryBlogController@toggle_edit')->name('catBlog.toggle');
    Route::post('blog/update','AdminController\BlogController@toggle_edit')->name('blog.toggle');
    Route::post('category/update','AdminController\CategoryController@toggle_edit')->name('category.toggle');
    Route::post('comment/update','AdminController\CommentController@toggle_edit')->name('comment.toggle');
    Route::post('user/update','UserController@update_post')->name('user.update_post');
    Route::resources([
        'user' => 'UserController',
        'banner' => 'AdminController\BannerController',
        'category' => 'AdminController\CategoryController',
        'product' => 'AdminController\ProductController',
        'stocks' => 'AdminController\StockController',
        'order' => 'AdminController\OrderController',
        'review' => 'AdminController\ReviewController',
        'blog' => 'AdminController\BlogController',
        'categoryBlog' => 'AdminController\CategoryBlogController',
        'comment' => 'AdminController\CommentController',
        'permission' => 'AdminController\PermissionController',
        'role' =>'AdminController\RoleController'
    ]);
    Route::get('stocks/filter/{val}', 'AdminController\StockController@stock_filter')->name('stocks.filter');
    Route::group(['prefix' => 'stocks'], function () {
        Route::post('edit', 'AdminController\StockController@edit_stocks')->name('stock.edit');
        Route::post('update', 'AdminController\StockController@update_stocks')->name('stock.update');
        Route::post('insert', 'AdminController\StockController@insert_stocks')->name('stock.insert');
        Route::post('delete', 'AdminController\StockController@stock_delete')->name('stock.delete');
    });

});
Route::post('detail','AdminController\OrderDetailController@show')->name('order.detail');
Route::post('fetch/order','AdminController\OrderController@fetch_orders')->name('fetch.order');
Route::post('detail/update', 'AdminController\OrderDetailController@update')->name('detail.update');
Route::post('detail/delete', 'AdminController\OrderDetailController@delete')->name('detail.delete');
Route::group(['prefix' => 'cart'], function () {
    Route::get('view', 'CartController@view')->name('cart.view');
    Route::get('checkout', 'CartController@checkout')->name('cart.checkout');
    Route::post('remove' ,'CartController@remove')->name('cart.remove');
    Route::post('clear' ,'CartController@clear')->name('clear');
    Route::post('update' ,'CartController@update')->name('cart.update');
    Route::post('add', 'CartController@add')->name('add.cart');
    Route::post('order', 'CartController@add_order')->name('add.order');
});
