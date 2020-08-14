@extends('admin.master')

@section('title','Quản lý banner')

@section('main')

    <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading">Banner list</div>
        <div class="panel-body">
            <form action="" method="POST" class="form-inline" role="form">
                <div class="form-group">
                    <input type="email" class="form-control" name="search" placeholder="Input keyword">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="glyphicon glyphicon-search"></i>
                </button>
                @can('banner-create')
                    <a href="" class="btn btn-success" id="add_banner">Add new</a>
                @endcan
            </form>
        </div>
        <table class="table table-bordered" id="my_table">
            <thead>
            <th>Tiêu đề</th>
            <th>Nội dung</th>
            <th>Loại</th>
            <th>Created_At</th>
            <th>Status</th>
            <th>Action</th>
            </thead>
            <tbody>
            @foreach ($listBanner as $item)
                <tr>
                    <td>
                        @switch($item->type)
                            @case(1)
                            {{ $item->title }}
                            @break
                            @case(2)
                            Không
                            @break
                            @case(3)
                            Không
                            @break
                            @case(4)
                            Không
                            @break
                        @endswitch
                    </td>
                    <td>
                        @switch($item->type)
                            @case(1)
                            {{ $item->content }}
                            @break
                            @case(2)
                            Không
                            @break
                            @case(3)
                            Không
                            @break
                            @case(4)
                            Không
                            @break
                        @endswitch
                    </td>
                    <td>
                        @switch($item->type)
                            @case(1)
                            Banner đại diện
                            @break
                            @case(2)
                            Banner quảng cáo to
                            @break
                            @case(3)
                            Banner quảng cáo nhỏ
                            @break
                            @case(4)
                            Banner SEO
                            @break
                        @endswitch
                    </td>
                    <td>{{$item->created_at->format('d-m-y')}}</td>
                    <td>
                        @can('banner-update')
                            <input data-id="{{$item->id}}" class="toggle-class" type="checkbox" data-onstyle="success"
                                   data-offstyle="danger" data-toggle="toggle" data-on="Hiển thị"
                                   data-off="Ẩn" {{ $item->status ? 'checked' : '' }}>
                        @endcan
                    </td>
                    <td>
                        @can('banner-update')
                            <a href="" class="btn btn-primary edit" id="{{ $item->id }}"><i
                                    class="glyphicon glyphicon-pencil"></i></a>
                        @endcan
                        @can('banner-delete')
                            <a href="" class="btn btn-danger delete" id="{{ $item->id }}"><i
                                    class="glyphicon glyphicon-trash"></i></a>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="clearfix"></div>
        {{ $listBanner->links() }}
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
                        <form action="" method="POST" role="form" id="form_demo" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group title">
                                <label for="" class="control-label">Tiêu đề</label>
                                <input type="text" class="form-control" id="title" name="title">
                            </div>
                            <div class="form-group contents">
                                <label for="" class="control-label">Nội dung :</label>
                                <input type="text" class="form-control" id="content" name="content">
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Loại banner :</label>
                                <select name="type" id="type">
                                    <option value="1">Banner đại diện</option>
                                    <option value="2">Banner quảng cáo to</option>
                                    <option value="3">Banner quảng cáo nhỏ</option>
                                    <option value="4">Banner SEO</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Đường dẫn :</label>
                                <input type="text" class="form-control" id="links" name="links">
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Trạng thái :</label>
                                <input type="radio" value="1" name="status" class="status" id=""> Hiển thị
                                <input type="radio" value="0" name="status" class="status" id=""> Ẩn
                            </div>
                            <div class="form-group">
                                <label for="" class="control-label">Ảnh :</label>
                                <input type="file" name="image">
                                <div id="image_show"></div>
                                <input type="hidden" name="img" value="" id="img">
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
                $('.toggle-class').change(function (event) {
                    event.preventDefault();
                    var data = new FormData();
                    var id = $(this).data('id');
                    data.append('status', $(this).prop('checked') == true ? 1 : 0);
                    data.append('id', id);
                    data.append('_token', $("meta[name='csrf-token']").attr("content"));
                    $.ajax({
                        url: "{{ route('banner.toggle') }}",
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
                $('#add_banner').click(function (event) {
                    event.preventDefault();
                    $('.modal-title').text('Thêm mới banner');
                    $('#type').find('option[value="1"]').attr('selected', true);
                    $('.status[value="1"]').attr('checked', true);
                    $('#type').val(1);
                    $('.title').html('<div class="form-group title">' + '<label for="" class="control-label">Tiêu đề</label>' + '<input type="text" class="form-control" id="title" name="title">' + '</div>');
                    $('.contents').html('<div class="form-group contents">' + '<label for="" class="control-label">Nội dung :</label>' + '<input type="text" class="form-control" id="content" name="content">' + '</div>');
                    $('#hidden_id').val();
                    $('#links').val('');
                    $('#image_show').html('');
                    $('#action').text('Thêm mới');
                    $('#action').val('Add');
                    $('#form_demo').attr('id', 'form_add');
                    $('#myModal').modal('show');
                });
                $('#form_demo').on('submit', function (event) {
                    event.preventDefault();
                    if ($('#action').val() == 'Add') {
                        $.ajax({
                            url: "{{ route('banner.store') }}",
                            method: "POST",
                            data: new FormData(this),
                            contentType: false,
                            cache: false,
                            processData: false,
                            dataType: "json",
                            success: function (data) {
                                if (data.errors) {
                                    var html = '';
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
                            url: "banner/" + id_edit,
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
                $('#type').change(function (event) {
                    event.preventDefault();
                    if ($('#type :selected').val() != 1) {
                        $('.title').html('');
                        $('.contents').html('');
                    } else {
                        $('.title').html('<div class="form-group title">' + '<label for="" class="control-label">Tiêu đề</label>' + '<input type="text" class="form-control" id="title" name="title">' + '</div>');
                        $('.contents').html('<div class="form-group contents">' + '<label for="" class="control-label">Nội dung :</label>' + '<input type="text" class="form-control" id="content" name="content">' + '</div>');
                    }
                });
                $(document).on('click', '.edit', function (event) {
                    event.preventDefault();
                    var id = $(this).attr('id');
                    $('#form_result').html('');
                    $.ajax({
                        url: "banner/" + id + "/edit",
                        dataType: "json",
                        success: function (html) {
                            if (html.data.title == '') {
                                $('.title').html('');
                                $('.contents').html('');
                            } else {
                                $('.title').html('<div class="form-group title">' + '<label for="" class="control-label">Tiêu đề</label>' + '<input type="text" class="form-control" id="title" name="title">' + '</div>');
                                $('.contents').html('<div class="form-group contents">' + '<label for="" class="control-label">Nội dung :</label>' + '<input type="text" class="form-control" id="content" name="content">' + '</div>');
                                $('#title').val(html.data.title);
                                $('#content').val(html.data.content);
                            }
                            $('#type').val(html.data.type);
                            $('.form-group').find(':radio[name=status][value="' + html.data.status + '"]').prop('checked', true);
                            $('#hidden_id').val(html.data.id);
                            $('#image_show').html('<img src="{{url("uploads")}}/' + html.data.image + '" width="200px"/>');
                            $('.modal-title').text('Cập nhật sản phẩm');
                            $('#form_demo').attr('id', 'form_edit');
                            $('#form_add').attr('id', 'form_edit');
                            $('#action').text('Cập nhật');
                            $('#img').val(html.data.image);
                            $('#links').val(html.data.links);
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
                                    url: "banner/" + cat_id,
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
