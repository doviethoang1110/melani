@extends('admin.master')

@section('title','Quản lý bài viết')

@section('main')

    <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading">Category list</div>
        <div class="panel-body">
            <form action="" method="POST" class="form-inline" role="form">
                @can('blog-create')
                    <a href="{{ route('blog.create') }}" class="btn btn-success">Add new</a>
                @endcan
            </form>
        </div>
        <table class="table table-bordered" id="my_table">
            <thead>
            <th>Name</th>
            <th>Slug</th>
            <th>Danh mục</th>
            <th>Created_At</th>
            <th>Status</th>
            <th>Action</th>
            </thead>
            <tbody>
            @foreach ($listBlog as $item)
                <tr>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->slug }}</td>
                    <td>{{ $item->blog->name }}</td>
                    <td>{{$item->created_at->format('d-m-y')}}</td>
                    <td>
                        @can('blog-update')
                            <input data-id="{{$item->id}}" class="toggle-class" type="checkbox" data-onstyle="success"
                                   data-offstyle="danger" data-toggle="toggle" data-on="Hiển thị"
                                   data-off="Ẩn" {{ $item->status ? 'checked' : '' }}>
                        @endcan
                    </td>
                    <td>
                        @can('blog-list')
                            <a href="{{ route('blog.show',['id'=>$item->id]) }}" class="btn btn-success view"><i
                                    class="glyphicon glyphicon-eye-open"></i></a>
                        @endcan
                        @can('blog-update')
                            <a href="{{ route('blog.edit',['id'=>$item->id]) }}" class="btn btn-primary edit"><i
                                    class="glyphicon glyphicon-pencil"></i></a>
                        @endcan
                        @can('blog-delete')
                            <a href="" class="btn btn-danger delete" id="{{ $item->id }}"><i
                                    class="glyphicon glyphicon-trash"></i></a>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="clearfix"></div>
        {{ $listBlog->links() }}
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
                                    url: "blog/" + cat_id,
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
                        url: "{{ route('blog.toggle') }}",
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
