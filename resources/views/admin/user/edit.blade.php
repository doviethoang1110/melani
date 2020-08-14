@extends('admin.master')

@section('title','Cập nhật tài khoản')

@section('main')
    <div class="container">
        <div class="row">
            <h2>Thông tin tài khoản</h2>
            <table class="table table-bordered">
                <tr>
                    <th>Name :</th>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <th>Email :</th>
                    <td>{{ $user->email }}</td>
                </tr>
                <tr>
                    <th>Password :</th>
                    <td>{{ $user->password }}</td>
                </tr>
                <tr>
                    <th>Remmember Token :</th>
                    <td>
                        @if(!isset($user->remmember_token))
                            {{ $user->remember_token }}
                        @else
                            NULL
                        @endif
                    </td>
                </tr>
            </table>
            <hr>
            @can('user-update')
                <form action="" method="POST" role="form" id="form_demo" style="width: 600px;margin:0 auto">
                    <span id="form_result"></span>
                    @csrf
                    <div class="form-group">
                        <label for="" class="control-label">Tên người dùng :</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
                        <input type="hidden" id="hidden_id" name="hidden_id" value="{{ $user->id }}">
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Email :</label>
                        <input type="text" class="form-control" id="email" name="email" value="{{ $user->email }}">
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Mật khẩu :</label>
                        <input type="password" class="form-control" id="password" name="password"
                               value="{{ $user->password }}">
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Nhập lại mật khẩu :</label>
                        <input type="password" class="form-control" id="con_pass" name="con_pass"
                               value="{{ $user->password }}">
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Quyền :</label>
                        <select multiple="multiple" name="role_id[]" id="role_id">
                            @foreach($listRole as $role)
                                <option
                                    value="{{ $role->id }}" {{ $selected = $roles->contains($role->id) ? 'selected': '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" value="Add" id="action">Cập nhật</button>
                    </div>
                </form>
            @endcan
        </div>
    </div>
@stop()
@section('js')
    <script>
        var demo1 = $('select[name="role_id[]"]').bootstrapDualListbox();
        $("#demoform").submit(function () {
            alert($('[name="role_id[]"]').val());
            return false;
        });
        $('#form_demo').on('submit', function (event) {
            event.preventDefault();
            var data = new FormData(this);
            data['_token'] = $('input[name=_token]').val();
            $.ajax({
                url: "{{ route('user.update_post') }}",
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
        });
    </script>
@endsection
