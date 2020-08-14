@extends('admin.master')

@section('title','Quản lý bình luận')

@section('main')

    <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading">Danh sách bình luận</div>
        <table class="table table-bordered" id="my_table">
            <thead>
            <th><a href="" class="btn btn-danger" id="multidelete"><i class="glyphicon glyphicon-remove"></i></a></th>
            <th>Tên người đánh giá</th>
            <th>Bài viết</th>
            <th>Nội dung</th>
            <th>Ngày tạo</th>
            <th>Trạng thái</th>
            <th>Action</th>
            </thead>
            <tbody>
            @foreach ($listCom as $rev)
                <tr>
                    <td><input type="checkbox" name="commentId[]" value="{{ $rev->id }}" class="multidelete"></td>
                    <td>{{ $rev->cus->name }}</td>
                    <td>{{ $rev->blog->title }}</td>
                    <td>{{ $rev->content }}</td>
                    <td>{{$rev->created_at->format('d-m-y')}}</td>
                    <td>
                        @can('comment-update')
                            <input data-id="{{$rev->id}}" class="toggle-class" type="checkbox" data-onstyle="success"
                                   data-offstyle="danger" data-toggle="toggle" data-on="Hiển thị"
                                   data-off="Ẩn" {{ $rev->status ? 'checked' : '' }}>
                        @endcan
                    </td>
                    <td>
                        @can('comment-delete')
                            <a href="" class="btn btn-danger delete" id="{{ $rev->id }}"><i
                                    class="glyphicon glyphicon-trash"></i></a>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="clearfix"></div>
        {{ $listCom->links() }}
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
            <script>
                $('.toggle-class').change(function (event) {
                    event.preventDefault();
                    var data = new FormData();
                    var id = $(this).data('id');
                    data.append('status', $(this).prop('checked') == true ? 1 : 0);
                    data.append('id', id);
                    data.append('_token', $("meta[name='csrf-token']").attr("content"));
                    $.ajax({
                        url: "{{ route('comment.toggle') }}",
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
                                    url: "comment/" + cat_id,
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
                                url: "{{ route('comment.multi') }}",
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
