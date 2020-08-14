@extends('user/master')
@section('head')

<div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-wrap">
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">My account</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
</div>


@stop()

@section('main')
    <!-- breadcrumb area end -->

    <!-- page main wrapper start -->
    @if (Session::has('customer'))
    <main>
        <!-- my account wrapper start -->
        <div class="my-account-wrapper pt-100 pb-100 pt-sm-58 pb-sm-58">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- My Account Page Start -->
                        <div class="myaccount-page-wrapper">
                            <!-- My Account Tab Menu Start -->
                            <div class="row">
                                <div class="col-lg-3 col-md-4">
                                    <div class="myaccount-tab-menu nav" role="tablist">
                                        <a href="#dashboad" class="active" data-toggle="tab"><i class="fa fa-dashboard"></i>
                                            Thông tin người dùng</a>
                                        <a href="#orders" data-toggle="tab"><i class="fa fa-cart-arrow-down"></i> Orders</a>
                                        <a href="#payment-method" data-toggle="tab"><i class="fa fa-credit-card"></i> Danh sách yêu thích</a>
                                            <a href="#address-edit" data-toggle="tab"><i class="fa fa-map-marker"></i> Đổi mật khẩu</a>
                                        <a href="#account-info" data-toggle="tab"><i class="fa fa-user"></i> Account Details</a>
                                    </div>
                                </div>
                                <!-- My Account Tab Menu End -->
        
                                <!-- My Account Tab Content Start -->
                                <div class="col-lg-9 col-md-8">
                                    <div class="tab-content" id="myaccountContent">
                                        <!-- Single Tab Content Start -->
                                        <div class="tab-pane fade show active" id="dashboad" role="tabpanel">
                                            <div class="myaccount-content">
                                                <h3>Thông tin</h3>
                                                <div class="row">
                                                    <div class="col-lg-4">
                                                        <h6>Ảnh đại diện</h6>
                                                        <img src="{{ url('uploads') }}/{{$cus->avatar }}" alt="" width="200px" style="border-radius: 100%;border: solid 2px;">
                                                        <form action="" method="POST" enctype="multipart/form-data" id="upload_ava">
                                                            @csrf
                                                            <input type="file" name="avatar">
                                                            <input type="hidden" name="action" value="upload">
                                                            <input type="hidden" name="customerId" value="{{ $cus->id }}">
                                                            <input type="submit" value="Cập nhật">
                                                        </form>
                                                    </div>
                                                    <div class="col-lg-8">
                                                        @if (isset($cus))
                                                            <p><b>Tên :</b> {{ $cus->name }}</p>
                                                            <p><b>Email :</b> {{ $cus->email }}</p>
                                                            <p><b>Số điện thoại :</b> {{ $cus->phoneNumber }}</p>
                                                            <p><b>Địa chỉ :</b> {{ $cus->address }}</p>
                                                            @else
                                                            <p>Không có thông tin</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Single Tab Content End -->
                                        <div class="tab-pane fade" id="address-edit" role="tabpanel">
                                            <div class="myaccount-content">
                                                <h3>Đổi mật khẩu</h3>
                                                <span id="change_res"></span>
                                                <form action="#" method="POST" id="form_change_pass">
                                                    @csrf
                                                    <fieldset>
                                                        <div class="single-input-item">
                                                            <label for="current-pwd" class="required">Current Password</label>
                                                            <input type="password" id="old_pass" placeholder="Current Password" name="password"/>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="single-input-item">
                                                                    <label for="new-pwd" class="required">New Password</label>
                                                                    <input type="password" id="new_pass" placeholder="New Password" name="new_pass"/>
                                                                    @if (isset($cus))
                                                                    <input type="hidden" name="customerId" value="{{ $cus->id }}">
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="single-input-item">
                                                                    <label for="confirm-pwd" class="required">Confirm Password</label>
                                                                    <input type="password" id="re_new_pass" placeholder="Confirm Password" name="re_new_pass"/>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </fieldset><hr>
                                                    <input type="hidden" name="action" value="Change" id="action">
                                                    <button class="check-btn sqr-btn " type="submit"><i class="fa fa-edit"></i> Đổi mật khẩu</button>
                                                </form>
                                            </div>
                                        </div>
                                        <!-- Single Tab Content Start -->
                                        <div class="tab-pane fade" id="orders" role="tabpanel">
                                            <div class="myaccount-content">
                                                <h3>Orders</h3>
                                                @if (count($listOrd)>0)
                                                <div class="myaccount-table table-responsive text-center">
                                                    <table class="table table-bordered">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th>Id</th>
                                                                <th>Date</th>
                                                                <th>Status</th>
                                                                <th>Total</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($listOrd as $ord)
                                                                
                                                            <tr>
                                                                <td>MDH00{{ $ord->id }}</td>
                                                                <td>{{$ord->created_at->format('d-m-y')}}</td>
                                                                <td>
                                                                    @switch($ord->status)
                                                                        @case(1)
                                                                        <span class="badge badge-success">Đơn mới</span>
                                                                            @break
                                                                        @case(2)
                                                                        <span class="badge badge-primary">Đang xử lý</span>
                                                                            @break
                                                                        @case(3)
                                                                        <span class="badge badge-warning">Đang vận chuyển</span>
                                                                            @break
                                                                            @case(4)
                                                                        <span class="badge badge-danger">Đã xử lý</span>
                                                                            @break
                                                                        @default
                                                                            
                                                                    @endswitch
                                                                </td>
                                                                <td>{{ number_format($ord->totalAmount) }}</td>
                                                                <td><a href="javascript:void(0)" class="check-btn sqr-btn view_ord" id="{{ $ord->id }}">View</a></td>
                                                            </tr>
                                                            @endforeach

                                                        </tbody>
                                                    </table>
                                                </div>
                                                @else
                                                Bạn chưa có đơn hàng nào
                                                @endif
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="payment-method" role="tabpanel">
                                            <div class="myaccount-content">
                                                <h3>Danh sách yêu thích</h3>
                                                <div class="cart-table table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th class="pro-thumbnail">Image</th>
                                                            <th class="pro-title">Product</th>
                                                            <th class="pro-quantity">Category</th>
                                                            <th class="pro-price">Price</th>
                                                            <th class="pro-remove">Remove</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($listWish as $wish)
                                                                
                                                            <tr>
                                                                <td class="pro-thumbnail"><a href="{{ route('shop_detail',["slug"=>$wish->slug]) }}"><img class="img-fluid" src="{{ url('uploads') }}/{{ $wish->image }}"
                                                                                                            alt="Product"/></a></td>
                                                                <td class="pro-title"><a href="{{ route('shop_detail',["slug"=>$wish->slug]) }}">{{ $wish->proName }}</a></td>
                                                                <td class="pro-price"><span></span>{{ $wish->catName }}</td>
                                                                <td class="pro-quantity"><span class="text-success">{{ number_format($wish->price-($wish->price*$wish->discount)/100) }} VNĐ</span></td>
                                                                <td class="pro-remove"><a href="#" id="{{ $wish->id }}" class="remove_wish"><i class="fa fa-trash-o"></i></a></td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="account-info" role="tabpanel">
                                            <div class="myaccount-content">
                                                <h3>Account Details</h3>
                                                <div class="account-details-form">
                                                    <span id="edit_res"></span>
                                                    <form action="#" method="POST" id="form_update">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="single-input-item">
                                                                    <label for="first-name" class="required">Name</label>
                                                                    @if (isset($cus))
                                                                        <input type="text" id="name" name="name" placeholder="First Name" value="{{ $cus->name }}"/>
                                                                    @else
                                                                    <input type="text" id="name" placeholder="First Name" name="name"/>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="single-input-item">
                                                            <label for="email" class="required">Email Addres</label>
                                                            @if (isset($cus))
                                                                <input type="email" id="email" name="email" placeholder="First Name" value="{{ $cus->email }}"/>
                                                            @else
                                                                <input type="email" id="email" placeholder="First Name" name="email"/>
                                                            @endif
                                                        </div>
                                                        <div class="single-input-item">
                                                            <label for="email" class="required">Address</label>
                                                            @if (isset($cus))
                                                                <input type="text" id="address" name="address" placeholder="First Name" value="{{ $cus->address }}"/>
                                                            @else
                                                                <input type="text" id="address" placeholder="First Name" name="address"/>
                                                            @endif
                                                        </div>
                                                        <div class="single-input-item">
                                                            <label for="email" class="required">Phone Number</label>
                                                            @if (isset($cus))
                                                                <input type="text" id="phoneNumber" name="phoneNumber" placeholder="First Name" value="{{ $cus->phoneNumber }}"/>
                                                            @else
                                                                <input type="text" id="phoneNumber" placeholder="First Name" name="phoneNumber"/>
                                                            @endif
                                                        </div>
                                                        <div class="single-input-item">
                                                            <input type="hidden" name="action" value="Edit">
                                                            @if (isset($cus))
                                                            <input type="hidden" name="customerId" value="{{ $cus->id }}">
                                                            @endif
                                                            <button class="check-btn sqr-btn " id="btn_edit" value="Edit">Save Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div> <!-- Single Tab Content End -->
                                    </div>
                                </div> <!-- My Account Tab Content End -->
                            </div>
                        </div> <!-- My Account Page End -->
                    </div>
                </div>
            </div>
        </div>
        <!-- my account wrapper end -->
    </main>
    @else
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3>Bạn chưa đăng nhập.</h3>
                <h4>Bấm vào <a href="javascript:void(0);" id="login_c">đây</a> để đăng nhập</h4>
            </div>
        </div>
    </div>
    @endif
    <div class="modal fade" id="myModal">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalCenterTitle">Chi tiết đơn hàng</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="container">
                  <div class="row">
                    <div class="col-md-12">
                        <h3>Mã đơn hàng : <span id="ordId"></span></h3>
                        <table class="table" id="myTable">
                            <thead>
                              <tr>
                                <th>Stt</th>
                                <th scope="col">Name</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Price</th>
                                <th scope="col">Status</th>
                              </tr>
                            </thead>
                            <tbody id="t_content">
                            
                            </tbody>
                          </table>
                    </div>
                  </div>
                </div>
              </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

@stop()
@section('js')
    <script>
        $('#form_update').on('submit',function(event){
            event.preventDefault();
			var data = new FormData(this);
            if($('#btn_edit').val() == 'Edit'){
                $.ajax({
                    url:"{{ route('account.edit') }}",
                    method:"POST",
                    data:data,
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
                            $('#edit_res').html(html);
                        }
                        if(data.success){
                            swal({
							title: data.success,
							icon: "success",
							buttons: "Done",
							dangerMode: false,
							})
                            $('#edit_res').html('');

                        }

                    }
                });
            }
        });
        $('#form_change_pass').on('submit',function (event){
            event.preventDefault();
            var data = new FormData();
            data.append('_token',$('input[name="_token"]').val());
            data.append('id',$('input[name="customerId"]').val());
            data.append('password',$('#old_pass').val());
            data.append('new_pass',$('#new_pass').val());
            data.append('re_new_pass',$('#re_new_pass').val());
            data.append('action',$('#action').val());
            for (var value of data.values()) {
            console.log(value); 
            }
            $.ajax({
                url:"{{ route('account.edit') }}",
                method:"POST",
                data:data,
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
                            $('#change_res').html(html);
                        }
                        if(data.error){
                            html += '<div class=alert alert-danger>'+data.error+'</div>';
                        }
                        if(data.success){
                            swal({
							title: data.success,
							icon: "success",
							buttons: "Done",
							dangerMode: false,
							})
                            $('#change_res').html('');
                        }
                }
            });
        });
        $(document).on('click','.remove_wish',function(event){
            event.preventDefault();
            var data = new FormData();
            data.append('id',$(this).attr('id'));
            data.append('_token',$('input[name="_token"]').val());
            $.ajax({
                url:"{{ route('remove.wish') }}",
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
                        swal({
							title: data.success,
							icon: "success",
							buttons: "Done",
							dangerMode: false,
							}).then(function(){ 
							location.reload();
						});
                    }
                }
            });
        });
        $(document).on('click','.view_ord',function (event){
            event.preventDefault();
            $('#ordId').html('MDH00'+$(this).attr('id'));
            var data = new FormData();
            data.append('id',$(this).attr('id'));
            data.append('_token',$('input[name="_token"]').val());
            $('#t_content').html('');
            $.ajax({
                url:"{{ route('order.view') }}",
                method:"POST",
                data:data,
                contentType:false,
                cache:false,
                processData:false,
                dataType:"json",
                success:function(html){
                    if(html.data){
                        var string = '';
                        for (var i = 0; i < html.data.length; i++) {
                            string += '<tr>';
                            string += '<td>'+(i+1)+'</td>';
                            string += '<td>'+html.data[i].name+'</td>';
                            string += '<td>'+html.data[i].quantity+'</td>';
                            string += '<td>'+html.data[i].price+'</td>';
                            switch (html.data[i].status) {
                            case 1:
                                string += '<td><span class="badge badge-success">Đang xử lý</span></td>';
                                break;
                            case 2:
                                string += '<td><span class="badge badge-warning">Đã xử lý</span></td>';
                                break;
                            case 3:
                                string += '<td><span class="badge badge-danger">Đã thanh toán</span></td>';
                                break;
                            
                            }
                            string += '</tr>';
                        }
                        $('#t_content').html(string);
                        $('#myModal').modal('show');
                    }
                }
            });
        });
        $('#login_c').click(function (event){
            $('#modal_login').modal('show');
        });
        $('#upload_ava').on('submit',function (event){
            event.preventDefault();
            $.ajax({
                url:"{{ route('account.edit') }}",
                method:"POST",
                data:new FormData(this),
                contentType:false,
                cache:false,
                processData:false,
                dataType:"json",
                success:function(data){
                    if(data.error){
                        alert(data.error);
                    }
                    if(data.success){
                        swal({
							title: data.success,
							icon: "success",
							buttons: "Done",
							dangerMode: false,
							}).then(function(){ 
							location.reload();
						});
                    }
                }
            });
        });
    </script>
@endsection