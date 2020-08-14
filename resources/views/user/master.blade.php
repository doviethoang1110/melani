<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="meta description">

    <!-- Site title -->
    <title>Melani</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ url('/public') }}/img/favicon.ico" type="image/x-icon" />
    <!-- Bootstrap CSS -->
    <link href="{{ url('/public') }}/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font-Awesome CSS -->
    <link href="{{ url('/public') }}/css/font-awesome.min.css" rel="stylesheet">
    <!-- IonIcon CSS -->
    <link href="{{ url('/public') }}/css/ionicons.min.css" rel="stylesheet">
    <!-- helper class css -->
    <link href="{{ url('/public') }}/css/helper.min.css" rel="stylesheet">
    <!-- Plugins CSS -->
    <link href="{{ url('/public') }}/css/plugins.css" rel="stylesheet">
    <!-- Main Style CSS -->
    <link href="{{ url('/public') }}/css/style.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css
    ">
    <style>
        .media:hover{
            background-color: grey;
            cursor: pointer;
        }   
    </style>
</head>

<body>

    <!-- header area start -->
    <header>

        <!-- main menu area start -->
        <div class="header-main sticky">
            <div class="container">
                <div class="row align-items-center" style="margin-bottom: 20px;">
                    <div class="col-lg-3 col-md-6 col-6">
                        <div class="logo">
                            <a href="{{ route('home') }}">
                                <img src="{{ url('/public') }}/img/logo/logo.png" alt="Brand logo">
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6 d-none d-lg-block">
                        <div class="main-header-inner">
                            <div class="main-menu">
                                <nav id="mobile-menu">
                                    <ul>
                                        <li class="active"><a href="{{ route('home') }}">Home</a></li>

                                        <li><a href="{{ route('shop') }}">shop <i class="fa fa-angle-down"></i></a>
                                            <?php showCategories2($listCat);?>
                                        </li>
                                        <?php 
                                        function showCategories2($categories ,$parentId = 0, $ul_class = 'dropdown',$i_class = 'fa fa-angle-right')
                                            {
                                                $cate_child = array();
                                                foreach ($categories as $key => $item)
                                                {
                                                    if ($item['parentId'] == $parentId)
                                                    {
                                                        $cate_child[] = $item;
                                                        unset($categories[$key]);
                                                    }
                                                }
                                                if ($cate_child)
                                                {
                                                   
                                                    echo '<ul class="'.$ul_class.'">';
                                                    foreach ($cate_child as $key => $itemc)
                                                    {
                                                        if ($itemc['parentId'] == $key['id']) {
                                                            echo '<li>'.'<a class="catId" href="'.route('pro_cat',["slug"=>$itemc->slug]).'" id="'.$itemc->id.'">'.$itemc['name'].'<i class="'.$i_class.'">'.'</i>'.'</a>';
                                                            showCategories2($categories, $itemc['id'],'dropdown','fa fa-angle-right');
                                                            echo '</li>';
                                                        }else{
                                                            echo '<li>'.'<a class="catId" href="'.route('pro_cat',["slug"=>$itemc->slug]).'" id="'.$itemc->id.'">'.$itemc['name'].'<i class="'.$i_class.'">'.'</i>'.'</a>';
                                                            showCategories2($categories, $itemc['id'],'dropdown','');
                                                            echo '</li>';
                                                        }
                                                    }
                                                    echo '</ul>';
                                                }

                                            }
                                     ?>
                                        
                                        <li><a href="{{ route('blog') }}">Blog</a></li>
                                        <li><a href="{{ route('about-us') }}">About us</a></li>
                                        <li><a href="{{ route('contact') }}">Contact us</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-6 ml-auto">
                        <div class="header-setting-option">
                            <div class="header-mini-cart">
                                <div class="mini-cart-btn">
                                    <i class="ion-bag"></i>
                                    <span class="cart-notification">{{ $cart->total_quantity }}</span>
                                </div>
                                <ul class="cart-list">
                                    @foreach ($cart->items as $key=>$item)
                                        <li>
                                            <div class="cart-img">
                                                <a href="product-details.html"><img src="{{ url('/uploads') }}/{{ $item['image'] }}" alt=""></a>
                                            </div>
                                            <div class="cart-info">
                                                <h4><a href="product-details.html">{{ $item['name'] }}</a></h4>
                                                @if($item['color'] != '')
                                                <h6>Color :{{ $item['color'] }}</h6>
                                                @endif
                                                @if($item['size'] != '')
                                                <h6>Cỡ :{{ $item['size'] }}</h6>
                                                @endif
                                                <span>{{ number_format($item['price']) }} VNĐ</span>
                                            </div>
                                            <div class="del-icon">
                                                <a href="#" id="{{ $key }}" class="remove"><i class="fa fa-trash-o"></i></a>
                                            </div>
                                        </li>
                                    @endforeach
                                    <li class="mini-cart-price">
                                        <span class="subtotal">subtotal : </span>
                                        <span class="subtotal-price ml-auto">{{ number_format($cart->total_amount) }}</span>
                                    </li>
                                    <li class="checkout-btn">
                                        <a href="{{ route('cart.view') }}">View Cart</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="settings-top">
                                <div class="settings-btn">
                                    <i class="ion-android-settings"></i>
                                </div>
                                <ul class="settings-list">
                                    <li>
                                        @if (Session::has('customer'))
                                        Hello {{ Session::get('customer')->name }} <i class="fa fa-angle-down"></i>
                                        @else
                                        <li><p></p></li>
                                        @endif
                                        <ul>
                                            @if (Session::has('customer'))
                                            <li>
                                                <form action="{{ route('my_account') }}" method="POST">
                                                    <input type="hidden" value="{{ Session::get('customer')->id }}" name="id">
                                                    <button href="#" class="my_account" type="submit">Tài khoản của tôi</button>
                                                @csrf
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('customers.create') }}" method="GET">
                                                    <button type="submit">Đăng ký</button>
                                                </form>
                                            </li>
                                                <form action="" id="logout" method="POST">
                                                    <button type="submit">Đăng xuất</button>@csrf
                                                </form>
                                            @else
                                            <li><a href="javascript:void(0)" id="login_click">Đăng nhập</a></li>
                                            <li><a href="{{ route('customers.create') }}">Đăng ký</a></li>
                                            @endif
                                            
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6" style="margin: 0 auto">
                        <input type="text" class="form-control" placeholder="Tìm kiếm" id="searching" style="display:block;">
                        <div id="list_item" style="display:block;z-index:9999;position:absolute;color:grey"></div>
                        @csrf
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-12 d-block d-lg-none">
                        <div class="mobile-menu"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- main menu area end -->

        <!-- Start Search Popup -->
        <div class="box-search-content search_active block-bg close__top">
            <form class="minisearch" action="#">
                <div class="field__search">
                    <input type="text" placeholder="Search Our Catalog">
                    <div class="action">
                        <a href="#"><i class="fa fa-search"></i></a>
                    </div>
                </div>
            </form>
            <div class="close__wrap">
                <span>close</span>
            </div>
        </div>
        <!-- End Search Popup -->

    </header>
    <!-- header area end -->

    <!-- breadcrumb area start -->
    @yield('head')
    @yield('main')
    <!-- footer area start -->
    <footer>
        <!-- newsletter area end -->

        <!-- footer widget area start -->
        <div class="footer-widget-area pt-62 pb-56 pb-md-26 pt-sm-56 pb-sm-20">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="footer-widget">
                            <div class="footer-widget-title">
                                <h3>shipping and delivery</h3>
                            </div>
                            <div class="footer-widget-body">
                                <p>Here you can read some details about a nifty little lifecycle of your order's journey from the time you place your order to your new treasures arriving at your doorstep.</p>
                            </div>
                            <div class="footer-widget-title mt-20">
                                <h3>payment method</h3>
                            </div>
                            <div class="footer-widget-body">
                                <p>It is equally important to choose the solution which offers a specific selection of credit cards. We take Visa & MasterCard as they are widely used by cyber customers.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="footer-widget">
                            <div class="footer-widget-title">
                                <div class="footer-logo">
                                    <a href="{{ route('home') }}">
                                        <img src="{{ url('/public') }}/img/logo/logo.png" alt="">
                                    </a>
                                </div>
                            </div>
                            <div class="footer-widget-body">
                                <ul class="address-box">
                                    <li>
                                        <span>ADDRESS:</span>
                                        <p>Melani - Responsive Prestashop Theme
                                            <br> 169-C, Technohub, Dubai Silicon</p>
                                    </li>
                                    <li>
                                        <span>call us now:</span>
                                        <p>+880123456789</p>
                                    </li>
                                    <li>
                                        <span>email:</span>
                                        <p>demo@yourdomain.com</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- footer widget area end -->

        <!-- footer botton area start -->
        <div class="footer-bottom-area">
            <div class="container">
                <div class="bdr-top pt-18 pb-18">
                    <div class="row align-items-center">
                        <div class="col-md-6 order-2 order-md-1">
                            <div class="copyright-text">
                                <p>copyright <a href="{{ url('/public') }}/#">HasTech</a>. All Rights Reserved</p>
                            </div>
                        </div>
                        <div class="col-md-6 ml-auto order-1 order-md-2">
                            <div class="footer-payment">
                                <img src="{{ url('/public') }}/img/payment.png" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- footer botton area end -->
        <div class="modal fade" id="modal_login">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h2 class="modal-title" id="exampleModalLabel" style="text-align: center">Đăng nhập</h2>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    <form action="#" method="POST" id="form_login">
                        @csrf
                        <div class="single-input-item">
                            <input type="text" placeholder="Nhập tên" name="name" />
                        </div>
                        <div class="single-input-item">
                            <input type="password" placeholder="Nhập mật khẩu" name="password" />
                        </div>
                        <div class="single-input-item">
                            <div class="login-reg-form-meta d-flex align-items-center justify-content-between">
                                <div class="remember-meta">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="rememberMe">
                                        <label class="custom-control-label" for="rememberMe">Remember Me</label>
                                    </div>
                                </div>
                                <a href="#" class="forget-pwd">Forget Password?</a>
                            </div>
                            <span id="login_res"></span>
                        </div>
                        <div class="single-input-item">
                            <button class="sqr-btn" type="submit" value="Login" id="but_log">Đăng nhập</button>
                        </div>
                    </form>
                </div>
              </div>
            </div>
          </div>
    </footer>
    @yield('modal')
    <!-- footer area end -->

    <!-- Quick view modal start -->

    <!-- Scroll to Top End -->
    <div class="scroll-top not-visible">
        <i class="fa fa-angle-up"></i>
    </div>

    <!--All jQuery, Third Party Plugins & Activation (main.js) Files-->
    <script src="{{ url('/public') }}/js/vendor/modernizr-3.6.0.min.js"></script>
    <!-- Jquery Min Js -->
    <script src="{{ url('/public') }}/js/vendor/jquery-3.3.1.min.js"></script>
    <!-- Popper Min Js -->
    <script src="{{ url('/public') }}/js/vendor/popper.min.js"></script>
    <!-- Bootstrap Min Js -->
    <script src="{{ url('/public') }}/js/vendor/bootstrap.min.js"></script>
    <!-- Plugins Js-->
    <script src="{{ url('/public') }}/js/plugins.js"></script>
    <!-- Ajax Mail Js -->
    <script src="{{ url('/public') }}/js/ajax-mail.js"></script>
    <!-- Active Js -->
    <script src="{{ url('/public') }}/js/main.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        toastr.options = {
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": 2000,
            "extendedTimeOut": 1000,
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut",
            "tapToDismiss": false
            }
            toastr.options.onHidden = function(){
            window.location.reload();
            };
    </script>
    <script>
            $(document).on('keyup','#searching',function(){
                var query = $(this).val();
                if(query != ''){
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('data.fetch') }}",
                        method:"POST",
                        data: {query:query,_token:_token},
                        success:function(data){
                            $('#list_item').fadeIn();
                            $('#list_item').html(data);
                        }
                    });
                }else{
                    $('#list_item').fadeOut();
                }
            });
            $(document).on('click','#fetch',function(){
                $('#searching').val($(this).text());
                $('#list_item').fadeOut();
            });
        $('#login_click').on('click',function(event){
            event.preventDefault();
            $('#modal_login').modal('show');
        });
        $('#form_login').on('submit',function(event){
            event.preventDefault();
            if($('#but_log').val() == 'Login'){
                $.ajax({
                    url:"{{ route('customer.login') }}",
                    method:"POST",
                    data:new FormData(this),
                    contentType:false,
                    cache:false,
                    processData:false,
                    dataType:"json",
                    success:function(data){
                        var html ='';
                        if(data.errors){
                            html += '<div class="alert alert-danger">';
                            for (var i = 0; i < data.errors.length; i++) {
                                html += '<p>'+data.errors[i]+'</p>';
                            }
                            html += '</div>';
                            $('#login_res').html(html);
                        }
                        if(data.error){
                            html += '<div class="alert alert-danger">'+data.error+'</div>';
                            $('#login_res').html(html);
                        }
                        if(data.success){
                            setTimeout(function(){location.reload();}, 0001);  
                            $('#modal_login').modal('hide');
                            $('#login_res').html('');
                        }
                    }
                })
            }
        });
        $('#logout').on('submit',function(event){
            event.preventDefault();
            $.ajax({
                url:"{{ route('customer.logout') }}",
                method:"POST",
                data:new FormData(this),
                contentType:false,
                cache:false,
                processData:false,
                dataType:"json",
                success:function(data){
                    if(data.success){
                        setTimeout(function(){location.reload();}, 0001);    
                    }
                }
            });
        });
        $(document).on('click','.remove',function (event){
            event.preventDefault();
            var id = $(this).attr('id');
            var _token = $('input[name="_token"]').val();
            var data = new FormData();
            data.append('id',id);
            data.append('_token',_token);
            $.ajax({
                url:"{{ route('cart.remove') }}",
                method:"POST",
                data:data,
                contentType:false,
                cache:false,
                processData:false,
                dataType:"json",
                success:function(data){
                    if(data.success){
                        setTimeout(function(){location.reload();}, 0001);
                    }
                }
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $(document).on('click','.add_wishList',function (event){
                event.preventDefault();
                var data = new FormData();
                data.append('productId',$(this).attr('id'));
                data.append('customerId',$('input[name="customerId"]').val());
                data.append('_token',$('input[name="_token"]').val());
                $.ajax({
                    url:"{{ route('add.wishList') }}",
                    method:"POST",
                    data:data,
                    contentType:false,
                    cache:false,
                    processData:false,
                    dataType:"json",
                    success:function(data){
                        if(data.error){
                            alert(data.error);
                            $('#modal_login').modal('show');
                        }
                        if(data.error1){
                            alert(data.error1);
                        }
                        if(data.success){
                            toastr.options = {
                                "positionClass": "toast-top-right",
                                "preventDuplicates": false,
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": 2000,
                                "extendedTimeOut": 1000,
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut",
                                "tapToDismiss": false
                                }
                            toastr["success"](data.success)
                        }
                    }
                });
            });
        });
        $(document).on('click','.compare',function (event){
                event.preventDefault();
                var data = new FormData();
                data.append('productId',$(this).attr('id'));
                data.append('_token',$('input[name="_token"]').val());
                $.ajax({
                    url:"{{ route('add.compare') }}",
                    method:"POST",
                    data:data,
                    contentType:false,
                    cache:false,
                    processData:false,
                    dataType:"json",
                    success:function(data){
                        if(data.error){
                            alert(data.error);
                        }
                        if(data.success){
                            toastr.options = {
                                "positionClass": "toast-top-right",
                                "preventDuplicates": false,
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": 2000,
                                "extendedTimeOut": 1000,
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut",
                                "tapToDismiss": false
                                }
                            toastr["success"](data.success)
                        }
                    }
                });
        });
    </script>
    @yield('js')
</body>

</html>