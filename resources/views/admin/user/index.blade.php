@extends('admin.master')

@section('title','Quản lý tài khoản')

@section('main')

    <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading">Admin Account list</div>
        <div class="panel-body">
            <form action="" method="POST" class="form-inline" role="form">
                <div class="form-group">
                    <input type="email" class="form-control" name="search" placeholder="Input keyword">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="glyphicon glyphicon-search"></i>
                </button>
                @can('user-create')
                    <a href="" class="btn btn-success" id="user_add">Add new</a>
                @endcan
            </form>
        </div>
        <!-- Table -->
        <table class="table table-bordered" id="my_table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Created at</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($listUser as $user)
                <tr>
                    <td>{{$loop->index+1}}</td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>
                        @if(!empty($user->getRoleNames()))
                            @foreach($user->getRoleNames() as $v)
                                <span class="btn btn-danger">{{ $v }}</span>
                            @endforeach
                        @endif
                    </td>
                    <td>{{$user->created_at->format('d-m-y')}}</td>
                    <td>
                        @can('user-update')
                            <a href="{{ route('user.edit',['id'=>$user->id]) }}" class="btn btn-primary edit" id=""><i
                                    class="glyphicon glyphicon-pencil"></i></a>
                        @endcan
                        @can('user-delete')
                            <a href="" class="btn btn-danger delete" id="{{ $user->id }}"><i
                                    class="glyphicon glyphicon-trash"></i></a>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

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
                        <label for="" class="control-label">Tên người dùng :</label>
                        <input type="text" class="form-control" id="name" name="name">
                        <input type="hidden" id="hidden_id" name="hidden_id" value="">
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Email :</label>
                        <input type="text" class="form-control" id="email" name="email">
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Mật khẩu :</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Nhập lại mật khẩu :</label>
                        <input type="password" class="form-control" id="con_pass" name="con_pass">
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Quyền :</label>
                        <select multiple="multiple" name="role_id[]" id="role_id">
                            @foreach($listRole as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
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
    <script>
        var demo1 = $('select[name="role_id[]"]').bootstrapDualListbox();
        $("#demoform").submit(function () {
            alert($('[name="role_id[]"]').val());
            return false;
        });
        $('#user_add').click(function (event) {
            event.preventDefault();
            $('.modal-title').text('Thêm mới người dùng');
            $('#action').text('Thêm mới');
            $('#action').val('Add');
            $('#form_demo').attr('id', 'form_add');
            $('#name').val('');
            $('#email').val('');
            $('#password').val('');
            $('#myModal').modal('show');
        });
        $('#form_demo').on('submit', function (event) {
            event.preventDefault();
            if ($('#action').val() == 'Add') {
                $.ajax({
                    url: "{{ route('user.store') }}",
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
                            url: "user/" + cat_id,
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
