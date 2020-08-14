@extends('admin.master')

@section('title','Quản lý đơn hàng')

@section('main')

    <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading">Order List</div>
        <div class="panel-body">
            <form action="" method="POST" class="form-inline" role="form">
                <div class="form-group">
                    <input type="email" class="form-control" name="search" placeholder="Input keyword">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="glyphicon glyphicon-search"></i>
                </button>
            </form>
        </div>
        <div class="col-md-9">
            <select name="" id="status" class="form-control filter_order">
                <option value="#">----Lọc đơn hàng----</option>
                <option value="1">Đơn mới</option>
                <option value="2">Đang xử lý</option>
                <option value="3">Đang vận chuyển</option>
                <option value="4">Đã xử lý</option>
            </select>
        </div>
        <table class="table table-bordered" id="my_table">
            <thead>
            <th>Tên người mua</th>
            <th>Tên người nhận</th>
            <th>Phương thức thanh toán</th>
            <th>Tổng tiền</th>
            <th>Trạng thái</th>
            <th>Ngày tạo</th>
            <th>Action</th>
            </thead>
            <tbody id="my_body">
            @foreach ($listOrd as $ord)
                <tr>
                    <td>{{ $ord->cus->name }}</td>
                    <td>{{ $ord->name }}</td>
                    <td>{{ $ord->pay->name }}</td>
                    <td>{{ number_format($ord->totalAmount) }}</td>
                    <td>
                        @switch($ord->status)
                            @case(1)
                            <span class="badge badge-success">Đơn mới</span>
                            @break
                            @case(2)
                            <span class="badge badge-success">Đang xử lý</span>
                            @break
                            @case(3)
                            <span class="badge badge-success">Đang giao hàng</span>
                            @break
                            @case(4)
                            <span class="badge badge-success">Đã xử lý</span>
                            @break
                            @default

                        @endswitch
                    </td>
                    <td>{{$ord->created_at->format('d-m-y')}}</td>
                    <td>
                        @can('detail-list')
                            <a href="{{ route('order.show',['id'=>$ord->id]) }}" class="btn btn-success view"><i
                                    class="glyphicon glyphicon-eye-open"></i></a>
                        @endcan
                        @can('order-update')
                            <a href="" class="btn btn-primary edit" id="{{ $ord->id }}"><i
                                    class="glyphicon glyphicon-pencil"></i></a>
                        @endcan
                        @can('order-delete')
                            <a href="" class="btn btn-danger delete" id="{{ $ord->id }}"><i
                                    class="glyphicon glyphicon-trash"></i></a>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="clearfix"></div>
        {{ $listOrd->links() }}
        @stop()
        <div class="modal" id="myModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"></h4>
                    </div>
                    <div class="modal-body">
                        <span id="form_result"></span>
                        <form action="" method="POST" role="form" id="form_edit">
                            @csrf
                            <div class="form-group">
                                <label for="" class="control-label">Tên người nhận</label>
                                <input type="text" class="form-control" id="name" name="name">
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Địa chỉ :</label>
                                <input type="text" class="form-control" id="address" name="address">
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Số điện thoại :</label>
                                <input type="text" class="form-control" id="phoneNumber" name="phoneNumber">
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Trạng thái :</label>
                                <input type="radio" value="1" name="status" class="status" id=""> Đơn mới
                                <input type="radio" value="2" name="status" class="status" id=""> Đang xử lý
                                <input type="radio" value="3" name="status" class="status" id=""> Đang vận chuyển
                                <input type="radio" value="4" name="status" class="status" id=""> Đã xử lý
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                <input type="hidden" name="hidden_id" id="hidden_id">
                                <button type="submit" class="btn btn-success" value="" id="action"></button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        @section('js')
            <script>
                $(document).on('click', '.edit', function (event) {
                    event.preventDefault();
                    var id = $(this).attr('id');
                    $.ajax({
                        url: "order/" + id + "/edit",
                        dataType: "json",
                        success: function (html) {
                            $('#name').val(html.data.name);
                            $('#address').val(html.data.address);
                            $('#email').val(html.data.email);
                            $('#phoneNumber').val(html.data.phoneNumber);
                            $('.form-group').find(':radio[name=status][value="' + html.data.status + '"]').prop('checked', true);
                            $('#hidden_id').val(html.data.id);
                            $('.modal-title').text('Cập nhật đơn hàng');
                            $('#action').text('Cập nhật');
                            $('#myModal').modal('show');
                        }
                    });
                });
                $('#form_edit').on('submit', function (event) {
                    event.preventDefault();
                    var id_edit = $('#hidden_id').val();
                    var data = new FormData(this);
                    data['_token'] = $('input[name=_token]').val();
                    data.append('_method', 'PUT');
                    $.ajax({
                        url: "order/" + id_edit,
                        method: "POST",
                        data: data,
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType: "json",
                        success: function (data) {
                            var html = '';
                            if (data.errors) {
                                html = '<div class="alert alert-danger">';
                                for (var i = 0; i < data.errors.length; i++) {
                                    html += '<p>' + data.errors[i] + '</p>';
                                }
                                html += '</div>';
                                $('#form_result').html(html);
                            }
                            if (data.success) {
                                swal({
                                    title: data.success,
                                    icon: "success",
                                    buttons: "Done",
                                    dangerMode: false,
                                }).then(function () {
                                    location.reload();
                                });
                            }
                        }
                    });
                });
                $(document).on('click', '.delete', function (event) {
                    event.preventDefault();
                    var id = $(this).attr('id');
                    var token = $("meta[name='csrf-token']").attr("content");
                    swal({
                        title: "Bạn có chắc không ?",
                        text: "Chọn Ok để xoá",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                        .then((willDelete) => {
                            if (willDelete) {
                                $.ajax({
                                    url: "order/" + id,
                                    type: "DELETE",
                                    data: {"id": id, "_token": token,},
                                    success: function (data) {
                                        if (data.errors) {
                                            swal({
                                                title: data.errors,
                                                icon: "warning",
                                                buttons: "Done",
                                                dangerMode: true,
                                            })
                                        }
                                        if (data.success) {
                                            swal({
                                                title: data.success,
                                                icon: "success",
                                                buttons: "Done",
                                                dangerMode: false,
                                            }).then(function () {
                                                    location.reload();
                                                }
                                            );
                                        }
                                    }
                                });
                            } else {
                                swal("Dữ liệu chưa bị xoá");
                            }
                        });
                });
                $('.filter_order').change(function (event) {
                    event.preventDefault();
                    var status = $(this).val();
                    var data = new FormData();
                    data.append('status', status);
                    data.append('_token', $('input[name=_token]').val());
                    $.ajax({
                        url: "{{ route('fetch.order') }}",
                        method: "POST",
                        data: data,
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType: "json",
                        success: function (html) {
                            if (html.data) {
                                $('#my_body').html('');
                                var strin = '<tr>';
                                for (var i = 0; i < html.data.length; i++) {
                                    strin += '<td>' + html.data[i].c_name + '</td>';
                                    strin += '<td>' + html.data[i].name + '</td>';
                                    strin += '<td>' + html.data[i].pay + '</td>';
                                    strin += '<td>' + html.data[i].amount + '</td>';
                                    switch (html.data[i].status) {
                                        case 1:
                                            strin += '<td><span class="badge badge-success">Đơn mới</span></td>';
                                            break;
                                        case 2:
                                            strin += '<td><span class="badge badge-success">Đang xử lý</span></td>';
                                            break;
                                        case 3:
                                            strin += '<td><span class="badge badge-success">Đang giao hàng</span></td>';
                                            break;
                                        case 4:
                                            strin += '<td><span class="badge badge-success">Đã xử lý</span></td>';
                                            break;
                                    }
                                    var url = '{{ route("order.show", ":id") }}';
                                    url = url.replace(':id', html.data[i].id);
                                    strin += '<td>' + html.data[i].created + '</td>';
                                    strin += '<td>' +
                                        '<a href="' + url + '" class="btn btn-success view"><i class="glyphicon glyphicon-eye-open"></i></a>	'
                                        + '<a id="' + html.data[i].id + '" class="btn btn-primary edit"><i class="glyphicon glyphicon-pencil"></i></a>	'
                                        + '<a id="' + html.data[i].id + '" class="btn btn-danger delete"><i class="glyphicon glyphicon-trash"></i></a>' +
                                        '</td>';
                                    strin += '</tr>';
                                }
                                $('#my_body').html(strin);
                            }
                        }
                    });
                });
            </script>
@endsection
