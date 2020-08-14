@extends('user/master') @section('main')
<div class="breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-wrap">
                    <nav aria-label="breadcrumb">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">checkout</li>
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
    <!-- checkout main wrapper start -->
    <div class="checkout-page-wrapper pt-100 pb-90 pt-sm-58 pb-sm-54">
        <div class="container">
            <div class="row">
                <!-- Checkout Billing Details -->
                @if (Session::has('cart'))
                <div class="col-lg-6">
                    <div class="checkout-billing-details-wrap">
                        <h2>Thông tin người mua</h2>
                        <div class="billing-form-wrap">

                                @csrf
                                @if (Session::has('customer'))
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="single-input-item">
                                            <label for="c_name" class="required">Tên người nhận</label>
                                            <input type="text" id="c_name" placeholder="Name" value="{{ Session::get('customer')->name }}" readonly/>
                                            <input type="hidden" name="customerId" id="customerId" value="{{ Session::get('customer')->id }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="single-input-item">
                                    <label for="c_email" class="required">Email người nhận</label>
                                    <input type="email" id="c_email" placeholder="Email Address" value="{{ Session::get('customer')->email }}" readonly/>
                                </div>
                                <div class="single-input-item">
                                    <label for="c_address" class="required">Địa chỉ</label>
                                    <input type="text" id="c_address" placeholder="Địa chỉ" value="{{ Session::get('customer')->address }}" readonly/>
                                </div>
                                <div class="single-input-item">
                                    <label for="c_phone">Phone</label>
                                    <input type="text" id="c_phone" placeholder="Phone" value="{{ Session::get('customer')->phoneNumber }}" readonly/>
                                </div>
                                <div class="checkout-box-wrap">
                                    <div class="single-input-item">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="ship_to_different">
                                            <label class="custom-control-label" for="ship_to_different">Địa chỉ người nhận</label>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="single-input-item">
                                                    <label for="r_name" class="required">Tên người nhận</label>
                                                    <input type="text" id="r_name" placeholder="First Name" value="" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="single-input-item">
                                            <label for="r_email" class="required">Email người nhận</label>
                                            <input type="email" id="r_email" placeholder="Email Address" value="" />
                                        </div>
                                        <div class="single-input-item">
                                            <label for="r_address" class="required">Địa chỉ</label>
                                            <input type="text" id="r_address" placeholder="Street address Line 1" value="" />
                                        </div>
                                        <div class="single-input-item">
                                            <label for="r_phone">Phone</label>
                                            <input type="text" id="r_phone" placeholder="Phone" value=""/>
                                        </div>
                                    </div>
                                </div>

                                <div class="single-input-item">
                                    <label for="ordernote">Order Note</label>
                                    <textarea name="orderNote" id="orderNote" cols="30" rows="3" placeholder="Notes about your order, e.g. special notes for delivery."></textarea>
                                </div>
                                @endif
                        </div>
                    </div>
                </div>

                <!-- Order Summary Details -->
                <div class="col-lg-6">
                    <div class="order-summary-details mt-md-26 mt-sm-26">
                        <h2>Đơn hàng</h2>
                        <div class="order-summary-content mb-sm-4">
                            <!-- Order Summary Table -->
                            <div class="order-summary-table table-responsive text-center">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Products</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cart->items as $item)

                                        <tr>
                                            <td><a href="#">{{ $item['name'] }} <strong> × {{ $item['quantity'] }}</strong></a> @if($item['color'] != '')
                                                <h6>Color :{{ $item['color'] }}</h6> @endif @if($item['size'] != '')
                                                <h6>Cỡ :{{ $item['size'] }}</h6> @endif
                                            </td>
                                            <td>{{ number_format($item['quantity']*$item['price']) }}</td>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td>Sub Total</td>
                                            <td><strong>{{ number_format($cart->total_amount) }} VNĐ</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Shipping</td>
                                            <td class="d-flex justify-content-center">
                                                <ul class="shipping-type">
                                                    @foreach ($listDeli as $del)
                                                    <li>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" id="flatrate{{ $del->id }}" name="deliverId" class="custom-control-input del" value="{{ $del->id }}" />
                                                            <label class="custom-control-label" for="flatrate{{ $del->id }}">{{ $del->name }}</label>
                                                        </div>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Total Amount</td>
                                            <td><strong id="price_text">{{ number_format($cart->total_amount) }}</strong><span> VNĐ</span></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- Order Payment Method -->
                            <div class="order-payment-method">
                                @foreach ($listPay as $pay)
                                <div class="single-payment-method">
                                    <div class="payment-method-name">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="cashon{{ $pay->id }}" name="paymentId" value="{{ $pay->id }}" class="custom-control-input pay"/>
                                            <label class="custom-control-label" for="cashon{{ $pay->id }}">{{ $pay->name }}</label>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                <div class="summary-footer-area">
                                    <a href="#" class="check-btn sqr-btn" id="add_order">Place Order</a>
                                    <span id="order_res"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <!-- checkout main wrapper end -->
</main>

@stop()
@section('js')
    <script>
        $('input[name="paymentId"][value=1]').attr('checked',true);
        $('input[name="deliverId"][value=1]').attr('checked',true);
        $('#ship_to_different').click(function (){
            var check = document.getElementById('ship_to_different').checked;
        if(check){
            $('#r_name').val($('#c_name').val());
            $('#r_email').val($('#c_email').val());
            $('#r_address').val($('#c_address').val());
            $('#r_phone').val($('#c_phone').val());
        }
        else{
            $('#r_name').val('');
            $('#r_email').val('');
            $('#r_address').val('');
            $('#r_phone').val('');
        }
        });
        $('#add_order').on('click',function (event){
            event.preventDefault();
            var data = new FormData();
            data.append('_token',$('input[name="_token"]').val());
            data.append('customerId',$('#customerId').val());
            data.append('r_name',$('#r_name').val());
            data.append('r_email',$('#r_email').val());
            data.append('r_address',$('#r_address').val());
            data.append('r_phone',$('#r_phone').val());
            data.append('totalAmount',{{ $cart->total_amount }});
            data.append('orderNote',$('#orderNote').val());
            data.append('paymentId',$('input[name="paymentId"]:checked').val());
            data.append('deliverId',$('input[name="deliverId"]:checked').val());
            $.ajax({
                url:"{{ route('add.order') }}",
                method:"POST",
                data:data,
                contentType:false,
                cache:false,
                processData:false,
                dataType:"json",
                success:function(data){
                    var html = '';
                    if(data.errors){
                        html += '<div class="alert alert-danger">';
                        for (var i = 0; i < data.errors.length; i++) {
                            html += '<p>'+data.errors[i]+'</p>';
                        }
                        html+= '</div>';
                        $('#order_res').html(html);
                    }if(data.error){
                        alert(data.error);
                        $('#modal_login').modal('show');
                    }if(data.success){
                        swal({
							title: data.success,
							icon: "success",
							buttons: "Done",
							dangerMode: false,
							}).then(function(){
                                window.location.href = 'http://localhost/test1/home';
						});
                    }
                }
            });
        });
    </script>
@endsection
