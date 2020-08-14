@extends('admin.master')

@section('title','Quản lý quyền')

@section('main')
    <style>
        .select2-container {
            width: 100% !important;
            padding-bottom: 5px;
        }
    </style>
    <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading">Admin Permission list</div>
        <div class="panel-body">
            <form action="" method="POST" class="form-inline" role="form">
                <div class="form-group">
                    <input type="email" class="form-control" name="search" placeholder="Input keyword">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="glyphicon glyphicon-search"></i>
                </button>
                @can('role-create')
                    <a href="" class="btn btn-success" id="per_add">Add new</a>
                @endcan
            </form>
        </div>
        <!-- Table -->
        <table class="table table-bordered" id="my_table">
            <thead>
            <tr>
                <th>Stt</th>
                <th>Role</th>
                <th>Created_at</th>
                <th>Updated_at</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($roles as $r)
                <tr>
                    <td>{{ $loop->index+1 }}</td>
                    <td>{{$r->name}}</td>
                    <td>{{ $r->created_at->format('d-m-y') }}</td>
                    <td>{{ $r->updated_at->format('d-m-y') }}</td>
                    <td>
                        <a href="{{ route('role.show',['id'=>$r->id]) }}" class="btn btn-success view"><i
                                class="glyphicon glyphicon-eye-open"></i></a>
                        @can('role-update')
                            <a href="" class="btn btn-primary edit" id="{{ $r->id }}"><i
                                    class="glyphicon glyphicon-pencil"></i></a>
                        @endcan
                        @can('role-delete')
                            <a href="" class="btn btn-danger delete" id="{{ $r->id }}"><i
                                    class="glyphicon glyphicon-trash"></i></a>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $roles->links() }}
@stop()
<div class="modal" id="myModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <span id="form_result"></span>
                <form action="" method="POST" role="form" id="form_demo">
                    @csrf
                    <div class="form-group">
                        <label for="" class="control-label">Tên chức danh :</label>
                        <input type="text" class="form-control" id="name" name="name">
                        <input type="hidden" id="hidden_id" value="" name="hidden_id">
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="permission_id">Quyền hạn:</label><br>
                        <select class="form-control select2-multi" name="permission_id[]" multiple="multiple">
                            @foreach($permissions as $per)
                                <option value='{{ $per->id }}'>{{ $per->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" value="Add" id="action"></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<div class="modal" id="modalDel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <span id="result_del"></span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Xác nhận</h4>
            </div>
            <div class="modal-body">
                <p>Bạn có muốn xoá không ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="check_del">Ok</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@section('js')
    <script !src="">
        $('.select2-multi').select2();
        $('#per_add').click(function (event) {
            event.preventDefault();
            $('.modal-title').text('Thêm mới chức danh');
            $('#action').text('Thêm mới');
            $('#action').val('Add');
            $('#form_demo').attr('id', 'form_add');
            $('#name').val('');
            $('#form_result').html('');
            $('#hidden_id').val('');
            $('#myModal').modal('show');
        });
        $(document).on('click', '.edit', function (event) {
            event.preventDefault();
            var id = $(this).attr('id');
            $('#form_result').html('');
            $.ajax({
                url: "role/" + id + "/edit",
                dataType: "json",
                success: function (html) {
                    $('#name').val(html.data.name);
                    $('#hidden_id').val(html.data.id);
                    $('.modal-title').text('Cập nhật sản phẩm');
                    $('#form_demo').attr('id', 'form_edit');
                    $('#form_add').attr('id', 'form_edit');
                    $('.select2-multi').select2('val', [html.data.permission_id]);
                    $('#action').text('Cập nhật');
                    $('#form_result').html('');
                    $('#action').attr('value', 'Edit');
                    $('#myModal').modal('show');
                }
            });
        });
        $('#form_demo').on('submit', function (event) {
            event.preventDefault();
            if ($('#action').val() == 'Add') {
                $.ajax({
                    url: "{{ route('role.store') }}",
                    method: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    success: function (data) {
                        var html = '';
                        if (data.errors) {
                            html += '<div class="alert alert-danger">';
                            for (var i = 0; i < data.errors.length; i++) {
                                html += '<p>' + data.errors[i] + '</p>';
                            }
                            html += '</div>';
                        }
                        if (data.success) {
                            $('#form_add')[0].reset();
                            swal({
                                title: data.success,
                                icon: "success",
                                buttons: "Done",
                                dangerMode: false,
                            }).then(function () {
                                location.reload();
                            });
                        }
                        $('#form_result').html(html);
                    }
                });
            }
            if ($('#action').val() == 'Edit') {
                var id_edit = $('#hidden_id').val();
                var data = new FormData(this);
                data['_token'] = $('input[name=_token]').val();
                data.append('_method', 'PUT');
                $.ajax({
                    url: "role/" + id_edit,
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
                        }
                        if (data.success) {
                            $('#form_edit')[0].reset();
                            swal({
                                title: data.success,
                                icon: "success",
                                buttons: "Done",
                                dangerMode: false,
                            }).then(function () {
                                location.reload();
                            });
                        }
                        $('#form_result').html(html);
                    }
                });
            }
        });
        $(document).on('click', '.delete', function (event) {
            event.preventDefault();
            var cat_id = $(this).attr('id');
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
                            url: "role/" + cat_id,
                            type: "DELETE",
                            data: {"cat_id": cat_id, "_token": token,},
                            success: function (data) {
                                if (data.errors) {
                                    swal({
                                        title: data.errors,
                                        icon: "warning",
                                        buttons: "Done",
                                        dangerMode: true,
                                    })
                                } else {
                                    swal({
                                        title: "Xoá thành công",
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
