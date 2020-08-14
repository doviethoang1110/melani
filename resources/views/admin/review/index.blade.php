@extends('admin.master')

@section('title','Quản lý đánh giá')

@section('main')

    <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading">Danh sách đánh giá</div>
        <table class="table table-bordered" id="my_table">
            <thead>
            @can('review-delete')
                <th><a href="" class="btn btn-danger" id="multidelete"><i class="glyphicon glyphicon-remove"></i></a>
                </th>
            @endcan
            <th>Tên người đánh giá</th>
            <th>Sản phẩm</th>
            <th>Số sao</th>
            <th>Nội dung</th>
            <th>Ngày tạo</th>
            <th>Trạng thái</th>
            <th>Action</th>
            </thead>
            <tbody>
            @foreach ($listRev as $rev)
                <tr>
                    @can('review-delete')
                        <td><input type="checkbox" name="reviewId[]" value="{{ $rev->id }}" class="multidelete"></td>
                    @endcan
                    <td>{{ $rev->cus->name }}</td>
                    <td>{{ $rev->pro->name }}</td>
                    <td>
                        @for ($i = 0; $i < $rev->rating; $i++)
                            <span><i class="fa fa-star"></i></span>
                        @endfor
                    </td>
                    <td>{{ $rev->content }}</td>
                    <td>{{$rev->created_at->format('d-m-y')}}</td>
                    <td>
                        @can('review-update')
                            <input data-id="{{$rev->id}}" class="toggle-class" type="checkbox" data-onstyle="success"
                                   data-offstyle="danger" data-toggle="toggle" data-on="Hiển thị"
                                   data-off="Ẩn" {{ $rev->status ? 'checked' : '' }}></td>
                    @endcan
                    <td>
                        @can('review-delete')
                            <a href="" class="btn btn-danger delete" id="{{ $rev->id }}"><i
                                    class="glyphicon glyphicon-trash"></i></a>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="clearfix"></div>
        {{ $listRev->links() }}
        @stop()
        @section('js')
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
                                    url: "review/" + cat_id,
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
                                url: "{{ route('review.multi') }}",
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
                $('.toggle-class').change(function (event) {
                    event.preventDefault();
                    var data = new FormData();
                    var id = $(this).data('id');
                    data.append('status', $(this).prop('checked') == true ? 1 : 0);
                    data.append('id', id);
                    data.append('_token', $("meta[name='csrf-token']").attr("content"));
                    $.ajax({
                        url: "{{ route('review.toggle') }}",
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
