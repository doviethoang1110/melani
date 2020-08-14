@extends('admin.master')

@section('title','Quản lý danh mục bài viết')

@section('main')

    <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading">Category list</div>
        <div class="panel-body">
            <form action="" method="POST" class="form-inline" role="form">
                @can('catBlog-create')
                    <a href="" class="btn btn-success" id="cat_add">Add new</a>
                @endcan
            </form>
        </div>
        <table class="table table-bordered" id="my_table">
            <thead>
            <th>Name</th>
            <th>Slug</th>
            <th>Created_At</th>
            <th>Status</th>
            <th>Action</th>
            </thead>
            <tbody>
            @foreach ($listCatB as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->slug }}</td>
                    <td>{{$item->created_at->format('d-m-y')}}</td>
                    <td>
                        @can('catBlog-update')
                            <input data-id="{{$item->id}}" class="toggle-class" type="checkbox" data-onstyle="success"
                                   data-offstyle="danger" data-toggle="toggle" data-on="Hiển thị"
                                   data-off="Ẩn" {{ $item->status ? 'checked' : '' }}>
                        @endcan
                    </td>

                    <td>
                        @can('catBlog-update')
                            <a href="" class="btn btn-primary edit" id="{{ $item->id }}"><i
                                    class="glyphicon glyphicon-pencil"></i></a>
                        @endcan
                        @can('catBlog-delete')
                            <a href="" class="btn btn-danger delete" id="{{ $item->id }}"><i
                                    class="glyphicon glyphicon-trash"></i></a>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="clearfix"></div>
        {{ $listCatB->links() }}
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
                        <form action="" method="POST" role="form" id="form_demo">
                            @csrf
                            <div class="form-group">
                                <label for="" class="control-label">Tên danh mục</label>
                                <input type="text" class="form-control" id="name" name="name">
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Slug :</label>
                                <input type="text" class="form-control" id="slug" name="slug">
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Trạng thái :</label>
                                <input type="radio" value="1" name="status" class="status" id=""> Hiển thị
                                <input type="radio" value="0" name="status" class="status" id=""> Ẩn
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
            <script src="{{url('/public/admin')}}/js/slug.js"></script>
            <script>
                $('#cat_add').click(function (event) {
                    event.preventDefault();
                    $('.modal-title').text('Thêm mới danh mục bài viết');
                    $('#action').text('Thêm mới');
                    $('input[name="status"][value="1"]').attr('checked', true);
                    $('#form_demo').attr('id', 'form_add');
                    $('#action').val('Add');
                    $('#myModal').modal('show');
                });
                $(document).on('click', '.edit', function (event) {
                    event.preventDefault();
                    var id = $(this).attr('id');
                    $('#form_result').html('');
                    $.ajax({
                        url: "categoryBlog/" + id + "/edit",
                        dataType: "json",
                        success: function (html) {
                            $('#name').val(html.data.name);
                            $('#slug').val(html.data.slug);
                            $('.form-group').find(':radio[name=status][value="' + html.data.status + '"]').prop('checked', true);
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
                $('#form_demo').on('submit', function (event) {
                    event.preventDefault();
                    if ($('#action').val() == 'Add') {
                        $.ajax({
                            url: "{{ route('categoryBlog.store') }}",
                            method: "POST",
                            data: new FormData(this),
                            contentType: false,
                            cache: false,
                            processData: false,
                            dataType: "json",
                            success: function (data) {
                                var html = '';
                                if (data.errors) {
                                    html = '<div class="alert alert-danger">';
                                    for (var count = 0; count < data.errors.length; count++) {
                                        html += '<p>' + data.errors[count] + '</p>';
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
                            url: "categoryBlog/" + id_edit,
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
                                    url: "categoryBlog/" + cat_id,
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
                $('.toggle-class').change(function (event) {
                    event.preventDefault();
                    var data = new FormData();
                    var id = $(this).data('id');
                    data.append('status', $(this).prop('checked') == true ? 1 : 0);
                    data.append('id', id);
                    data.append('_token', $("meta[name='csrf-token']").attr("content"));
                    $.ajax({
                        url: "{{ route('catBlog.toggle') }}",
                        method: "POST",
                        data: data,
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType: "json",
                        success: function (data) {
                            if (data.success) {
                                location.reload();
                            }
                        }
                    });
                });
            </script>
@endsection
