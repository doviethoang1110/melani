@extends('user/master')
@section('head')

@stop()

@section('main')


<div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-wrap">
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Login-Register</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb area end -->

    <!-- page main wrapper start -->
    <main>
        <!-- login register wrapper start -->
        <div class="login-register-wrapper pt-100 pb-100 pt-sm-58 pb-sm-58">
            <div class="container">
                <div class="member-area-from-wrap">
                    <div class="row">
                        <!-- Login Content Start -->
                        {{-- <div class="col-lg-6">
                            <div class="login-reg-form-wrap  pr-lg-50">
                                <h2>Đăng nhập</h2>
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
                                    </div>
                                    <div class="single-input-item">
                                        <button class="sqr-btn" type="submit" value="Login" id="but_log">Đăng nhập</button>
                                    </div>
                                </form>
                            </div>
                        </div> --}}
                        <!-- Login Content End -->

                        <!-- Register Content Start -->
                        <div class="col-lg-6" style="margin:0 auto">
                            <div class="login-reg-form-wrap mt-md-100 mt-sm-58">
                                <h2 style="text-align:center">Form đăng ký</h2>
                                <form action="" method="post" id="form_regis">
                                    @csrf
                                    <span id="regis_res"></span>
                                    <div class="single-input-item">
                                        <input type="text" placeholder="Tên đầy đủ" name="name" />
                                    </div>
                                    <div class="single-input-item">
                                        <input type="email" placeholder="Nhập Email" name="email" />
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="single-input-item">
                                                <input type="text" placeholder="Số điện thoại" name="phoneNumber" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="single-input-item">
                                                <input type="text" placeholder="Địa chỉ" name="address" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-input-item">
                                        <input type="password" placeholder="Nhập mật khẩu" name="password" />
                                    </div>
                                    <div class="single-input-item">
                                        <input type="password" placeholder="nhập lại mật khẩu" name="re_pass" />
                                    </div>
                                    <div class="single-input-item">
                                        <button class="sqr-btn" type="submit" id="but_reg" value="Register" >Đăng ký</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Register Content End -->
                    </div>
                </div>
            </div>
        </div>
        <!-- login register wrapper end -->
    </main>
@stop()
@section('js')
    <script>
        $('#form_regis').on('submit',function(event){
            event.preventDefault();
            if($('#but_reg').val() == 'Register'){
            $.ajax({
                url:"{{ route('customers.store')}}",
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
                        html+= '</div>';
                        $('#regis_res').html(html);
                    }if(data.success){
                        swal({
							title: data.success,
							icon: "success",
							buttons: "Done",
							dangerMode: false,
							})
                            $('#regis_res').html('');
                    }
                    $('#form_regis')[0].reset();
                }
            });
            }
        });
        
    </script>
@endsection