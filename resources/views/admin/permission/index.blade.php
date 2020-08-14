@extends('admin.master')

@section('title','Quản lý quyền')

@section('main')

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
                @can('permission-create')
                    <a href="" class="btn btn-success" id="per_add">Add new</a>
                @endcan
            </form>
        </div>
        <!-- Table -->
        <table class="table table-bordered" id="my_table">
            <thead>
            <tr>
                <th><a href="" class="btn btn-danger" id="multidelete"><i class="glyphicon glyphicon-remove"></i></a>
                </th>
                <th>STT</th>
                <th>Name</th>
                <th>Created at</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($listPer as $p)
                <tr>
                    <td><input type="checkbox" name="permissionId[]" value="{{ $p->id }}" class="multidelete"></td>
                    <td>{{ $loop->index+1 }}</td>
                    <td>{{$p->name}}</td>
                    <td>{{$p->created_at->format('d-m-y')}}</td>
                    <td>
                        @can('permission-update')
                            <a href="" class="btn btn-primary edit" id="{{ $p->id }}"><i
                                    class="glyphicon glyphicon-pencil"></i></a>
                        @endcan
                        @can('permission-delete')
                            <a href="" class="btn btn-danger delete" id="{{ $p->id }}"><i
                                    class="glyphicon glyphicon-trash"></i></a>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $listPer->links() }}
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
                        <label for="" class="control-label">Tên quyền :</label>
                        <input type="text" class="form-control" id="name" name="name">
                        <input type="hidden" id="hidden_id" value="" name="hidden_id">
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

        $('#per_add').click(function (event) {
            event.preventDefault();
            $('.modal-title').text('Thêm mới quyền');
            $('#action').text('Thêm mới');
            $('#action').val('Add');
            $('#form_demo').attr('id', 'form_add');
            $('#name').val('');
            $('#hidden_id').val('');
            $('#myModal').modal('show');
        });
        $('#form_demo').on('submit', function (event) {
            event.preventDefault();
            if ($('#action').val() == 'Add') {
                $.ajax({
                    url: "{{ route('permission.store') }}",
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
                    url: "permission/" + id_edit,
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
        $(document).on('click', '.edit', function (event) {
            event.preventDefault();
            var id = $(this).attr('id');
            console.log(id);
            $('#form_result').html('');
            $.ajax({
                url: "permission/" + id + "/edit",
                dataType: "json",
                success: function (html) {
                    $('#name').val(html.data.name);
                    $('#hidden_id').val(html.data.id);
                    $('.modal-title').text('Cập nhật sản phẩm');
                    $('#form_demo').attr('id', 'form_edit');
                    $('#form_add').attr('id', 'form_edit');
                    $('#action').text('Cập nhật');
                    $('#action').attr('value', 'Edit');
                    $('#myModal').modal('show');
                }
            });
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
                            url: "permission/" + cat_id,
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
        $('#multidelete').on('click', function (event) {
            event.preventDefault();
            var id = [];
            if (confirm('Bạn muốn xoá không')) {
                $('.multidelete:checked').each(function () {
                    id.push($(this).val());
                });
                if (id.length > 0) {
                    $.ajax({
                        url: "{{ route('permission.multi') }}",
                        method: "GET",
                        data: {id: id},
                        dataType: "json",
                        success: function (data) {
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
                            if (data.error) {
                                alert(data.error);
                            }
                        }
                    });
                } else {
                    alert('Chọn ít nhất 1 bản ghi');
                }
            }
        });
    </script>
@endsection
