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
                                <li class="breadcrumb-item active" aria-current="page">cart</li>
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
        <!-- cart main wrapper start -->
        <div class="cart-main-wrapper pt-100 pb-100 pt-sm-58 pb-sm-58">
            @if (count($cart->items)>0)
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- Cart Table Area -->
                        <div class="cart-table table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th class="pro-thumbnail">Stt</th>
                                    <th class="pro-title">Product</th>
                                    <th class="pro-price">Price</th>
                                    <th class="pro-quantity">Quantity</th>
                                    <th class="pro-subtotal">Total</th>
                                    <th class="pro-remove">Remove</th>
                                </tr>
                                </thead>
                                <tbody><?php $i=0?>
                                    @foreach ($cart->items as $key => $item)
                                        <?php $i++;?>
                                    <tr>
                                        <td class="pro-thumbnail">{{ $i }}</td>
                                        <td class="pro-title">
                                            <img class="img-fluid" src="{{ url('uploads') }}/{{ $item['image'] }}" alt="Product" width="60px"/>
                                            <a href="#">{{ $item['name'] }}</a>
                                            @csrf
                                            @if($item['color'] != '')
                                            <p>Color :{{ $item['color'] }}</p>
                                            @endif
                                            @if($item['size'] != '')
                                            <p>Cỡ :{{ $item['size'] }}</p>
                                            @endif
                                        </td>
                                        <td class="pro-price"><span>{{ number_format($item['price']) }} VNĐ</span></td>
                                        <td class="pro-quantity">
                                            <div class="">
                                                <input type="number" value="{{ $item['quantity'] }}" name="quantity" id="{{ $key }}"><br>
                                                <span id="err" style="color:red"></span>
                                            </div>
                                        </td>
                                        <td class="pro-subtotal"><span>{{ number_format($item['price']*$item['quantity']) }} VNĐ</span></td>
                                        <td class="pro-remove"><a href="#" id="{{ $key }}" class="remove"><i class="fa fa-trash-o"></i></a></td>
                                    </tr>
                                    @endforeach
                               
                                </tbody>
                            </table>
                        </div>
                        <!-- Cart Update Option -->
                        <div class="cart-update-option d-block d-md-flex justify-content-between">
                            <div class="apply-coupon-wrapper">
                            </div>
                            <div class="cart-update mt-sm-16">
                                <a href="#" class="sqr-btn" id="clear">Clear all</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-5 ml-auto">
                        <!-- Cart Calculation Area -->
                        <div class="cart-calculator-wrapper">
                            <div class="cart-calculate-items">
                                <h3>Cart Totals</h3>
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <td>Sub Total</td>
                                            <td>{{ number_format($cart->total_amount) }} VNĐ</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            @if (Session::has('customer'))
                            <a href="{{ route('cart.checkout') }}" class="sqr-btn d-block">Proceed To Checkout</a>
                            @else
                            <input type="hidden" id="customerId" value="">
                            <a href="#" class="sqr-btn d-block" id="check_out">Proceed To Checkout</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h3>Bạn có không có sản phẩm nào trong giỏ hàng của bạn.</h3>
                        <h4>Bấm vào <a href="{{ route('home') }}">đây</a> để tiếp tục mua sắm</h4>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <!-- cart main wrapper end -->
    </main>


@stop()
@section('js')
    <script>
        $('#clear').click(function(event){
            event.preventDefault();
            var data = new FormData();
            data.append('_token',$('input[name="_token"]').val());
            $.ajax({
                url:"{{ route('clear') }}",
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
        $(document).on('change','input[name="quantity"]',function (event){
            event.preventDefault();
            var data = new FormData();
            data.append('id',$(this).attr('id'));
            data.append('_token',$('input[name="_token"]').val());
            data.append('quantity',$(this).val());
            $.ajax({
                url:"{{ route('cart.update') }}",
                method:"POST",
                data:data,
                contentType:false,
                cache:false,
                processData:false,
                dataType:"json",
                success:function(data){
                    if(data.success){
                        setTimeout(function(){location.reload();}, 0001);
                    }else if(data.error){
                        $('#err').text(data.error);
                    }
                }
            });
        });
        $(document).on('submit','input[name="quantity"]',function (event){
            event.preventDefault();
            var data = new FormData();
            data.append('id',$(this).attr('id'));
            data.append('_token',$('input[name="_token"]').val());
            data.append('quantity',$(this).val());
            $.ajax({
                url:"{{ route('cart.update') }}",
                method:"POST",
                data:data,
                contentType:false,
                cache:false,
                processData:false,
                dataType:"json",
                success:function(data){
                    if(data.success){
                        setTimeout(function(){location.reload();}, 0001);
                    }else if(data.error){
                        $('#err').text(data.error);
                    }
                }
            });
        });
        $('#check_out').click(function(event){
            event.preventDefault();
            if($('#customerId').val()==''){
                alert('Bạn chưa đăng nhập');
                $('#modal_login').modal('show');
            }
        })
    </script>
@endsection