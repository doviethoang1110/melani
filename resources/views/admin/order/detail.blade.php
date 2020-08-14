@extends('admin/master')
@section('title','Chi tiết sản phẩm')
@section('main')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <table class="table" id="myTable">
                    <thead>
                    <tr>
                        <th>Stt</th>
                        <th scope="col">Name</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Price</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody><?php $i = 1;?>
                    @foreach ($listOrdet as $ord)
                        <tr>
                            <td>{{ $i }}</td>
                            <td>{{ $ord->stock->pro->name }}
                                @if (empty($ord->stock->color->id))
                                    <p>Màu : Không</p>
                                @else
                                    <p>Màu : {{ $ord->stock->color->name }}</p>
                                @endif
                                <p>Cỡ : {{ $ord->stock->size->name }}</p>
                            </td>
                            <td>{{ $ord->quantity }}</td>
                            <td>{{ number_format($ord->price) }}</td>
                            <td>
                                @switch($ord->status)
                                    @case(1)
                                    <span class="badge badge-success">Đang xử lý</span>
                                    @break
                                    @case(2)
                                    <span class="badge badge-success">Đã xử lý</span>
                                    @break
                                    @case(3)
                                    <span class="badge badge-success">Đã thanh toán</span>
                                    @break
                                    @default
                                @endswitch
                            </td>
                            <td>
                                @can('detail-update')
                                    <a href="" class="btn btn-primary edit" id="{{ $ord->id }}"><i
                                            class="glyphicon glyphicon-pencil"></i></a>
                                @endcan
                                @can('detail-delete')
                                    <a href="" class="btn btn-danger delete" id="{{ $ord->id }}"><i
                                            class="glyphicon glyphicon-trash"></i></a>
                                @endcan
                            </td>
                        </tr><?php $i++;?>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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
                            <label for="" class="control-label">Trạng thái :</label>
                            <input type="radio" value="1" name="status" class="status" id=""> Đang xử lý
                            <input type="radio" value="2" name="status" class="status" id=""> Đã xử lý
                            <input type="radio" value="3" name="status" class="status" id=""> Đã thanh toán
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
@endsection
@section('js')
    <script>
        $('#myTable').DataTable();
        $(document).on('click', '.edit', function (event) {
            event.preventDefault();
            var id = $(this).attr('id');
            data = new FormData();
            data.append('id', id);
            data.append('_token', $('input[name="_token"]').val());
            $.ajax({
                url: "{{ route('order.detail') }}",
                method: "POST",
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json",
                success: function (html) {
                    $('#quantity').val(html.data.quantity);
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
            data.append('_token', $('input[name="_token"]').val());
            data.append('id', id_edit);
            data
            $.ajax({
                url: "{{ route('detail.update') }}",
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
            var data = new FormData();
            data.append('id', $(this).attr('id'));
            data.append('_token', $("meta[name='csrf-token']").attr("content"));
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
                            url: "{{ route('detail.delete') }}",
                            method: "POST",
                            data: data,
                            contentType: false,
                            cache: false,
                            processData: false,
                            dataType: "json",
                            success: function (data) {
                                if (data.error) {
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
    </script>
@endsection
