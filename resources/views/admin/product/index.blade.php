@extends('admin.master')

@section('title','Quản lý sản phẩm')

@section('main')

    <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading">Danh sách sản phẩm</div>
        <div class="panel-body">
            <form action="" method="POST" class="form-inline" role="form">
                <div class="form-group">
                    <input type="text" class="form-control" id="search" placeholder="Product">
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="glyphicon glyphicon-search"></i>
                </button>
                @can('product-create')
                    <a href="{{ route('product.create') }}" class="btn btn-success">Add new</a>
                @endcan
            </form>
        </div>
        <!-- Table -->
        <table class="table table-bordered" id="my_table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Danh mục</th>
                <th>Created at</th>
                <th>Độ ưu tiên</th>
                <th>Trạng thái</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($listPro as $pro)
                <tr>
                    <td>{{$pro->name}}</td>
                    <td>{{$pro->cat->name}}</td>
                    <td>{{$pro->created_at->format('d-m-y')}}</td>
                    <td>
                        @switch($pro->priority)
                            @case(1)
                            <span class="badge badge-success">Nổi bật</span>
                            @break
                            @case(2)
                            <span class="badge badge-warning">Bán chạy</span>
                            @break
                            @case(3)
                            <span class="badge badge-danger">Mới</span>
                            @break
                            @default

                        @endswitch
                    </td>
                    <td>
                        @can('product-update')
                            <input data-id="{{$pro->id}}" class="toggle-class" type="checkbox" data-onstyle="success"
                                   data-offstyle="danger" data-toggle="toggle" data-on="Hiển thị"
                                   data-off="Ẩn" {{ $pro->status ? 'checked' : '' }}></td>
                    @endcan
                    <td>
                        <a href="{{ route('product.show',['id'=>$pro->id]) }}" class="btn btn-success view" id=""><i
                                class="glyphicon glyphicon-eye-open"></i></a>
                        @can('product-update')
                            <a href="{{ route('product.edit',['id'=>$pro->id]) }}" class="btn btn-primary edit" id=""><i
                                    class="glyphicon glyphicon-pencil"></i></a>
                        @endcan
                        @can('product-delete')
                            <a href="" class="btn btn-danger delete" id="{{ $pro->id }}"><i
                                    class="glyphicon glyphicon-trash"></i></a>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="clearfix">{{ $listPro->links() }}</div>
    </div>

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
                            url: "product/" + cat_id,
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
                url: "{{ route('product.toggle') }}",
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
