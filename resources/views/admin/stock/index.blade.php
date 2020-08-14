@extends('admin.master')

@section('title','Quản lý kho')

@section('main')

    <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading">Danh sách kho</div>
        <div class="panel-body">
            <form action="" method="POST" class="form-inline" role="form">
                <select onchange="location = this.value;" class="form-control">
                    <option value="javascript:void(0)">---Lọc kho---</option>
                    <option value="{{ route('stocks.filter',['val'=>1]) }}">Hàng mới về</option>
                    <option value="{{ route('stocks.filter',['val'=>2]) }}">Hàng tồn</option>
                    <option value="{{ route('stocks.filter',['val'=>3]) }}">Hàng bán chạy</option>
                    <option value="{{ route('stocks.filter',['val'=>4]) }}">Hết hàng</option>
                </select>
            </form>
            @can('stock-create')
                <button class="btn btn-danger" id="hide">Ẩn</button>
            @endcan
        </div>
        <div class="row">
            @can('stock-create')
                <div class="col-md-12">
                    <form action="" id="form_add" hidden method="POST">
                        <span id="attr_res"></span>
                        @csrf
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Color</th>
                                <th scope="col">Size</th>
                                <th scope="col">Số lượng</th>
                                <th scope="col">Giá nhập</th>
                                <th scope="col">Giá bán</th>
                            </tr>
                            </thead>
                            <tbody id="tbody">
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="5"></td>
                                <input type="hidden" value="" name="productId">
                                <td>
                                    <button class="btn btn-success" type="submit" id="save" name="Save">Thêm mới
                                    </button>
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    </form>
                </div>
            @endcan
            @can('stock-update')
                <div class="col-md-12">
                    <form action="" id="form_edit" hidden method="POST">
                        <span id="edit_res"></span>
                        @csrf
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Color</th>
                                <th scope="col">Size</th>
                                <th scope="col">Số lượng</th>
                                <th scope="col">Giá nhập</th>
                                <th scope="col">Giá bán</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><select name="colorId" id="color">
                                        <option value="null">null</option>
                                        @foreach ($listColor as $color)
                                            <option value="{{ $color->id }}">{{ $color->name }}</option>
                                        @endforeach
                                    </select></td>
                                <td><select name="sizeId" id="size">
                                        <option value="null">null</option>
                                        @foreach ($listSize as $size)
                                            <option value="{{ $size->id }}">{{ $size->name }}</option>
                                        @endforeach
                                    </select></td>
                                <td><input type="number" name="importNum" id="importNum" class="form-control">
                                    <input type="hidden" name="hidden_id" value="" id="hidden_id">
                                    <input type="hidden" name="status" value="" id="status">
                                    <input type="hidden" name="productId" value="" id="productId">
                                </td>
                                <td><input type="number" name="importPrice" id="importPrice" class="form-control"></td>
                                <td><input type="number" name="exportPrice" id="exportPrice" class="form-control"></td>
                                <td>
                                    <button class="btn btn-success" type="submit">Edit</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            @endcan
        </div>
        <!-- Table -->
        <table class="table table-bordered" id="my_table">
            <thead>
            <tr>
                <th style="text-align:center">Sản phẩm</th>
                <th>Giá</th>
                <th>Đã bán</th>
                <th>Danh mục</th>
                <th>Trạng thái</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody id="my_body">
            @foreach ($listStock as $stock)
                <tr>
                    <td>
                        <div class="col-md-2">
                            <img src="{{ url('uploads')}}/{{ $stock->pro->image }}" width="50px">
                        </div>
                        <div class="col-md-10">
                            <ul>
                                <li><strong>Tên</strong> : {{$stock->pro->name}}</li>
                                <li><strong>Màu</strong> :
                                    @if (!empty($stock->color->name))
                                        {{ $stock->color->name }}
                                    @else
                                        Không
                                    @endif
                                </li>
                                <li><strong>Cỡ</strong> :
                                    @if (!empty($stock->size->name))
                                        {{ $stock->size->name }}
                                    @else
                                        Không
                                    @endif
                                </li>
                                <li><strong>Giảm giá </strong> :
                                    {{ number_format($stock->pro->discount)}} %
                                </li>
                                <li><strong>Số lượng </strong> :
                                    Còn :{{ $stock->importNum }} sản phẩm
                                </li>
                            </ul>
                        </div>
                    </td>
                    <td><strong>Nhập : </strong>{{ number_format($stock->importPrice) }} VNĐ<br>
                        <strong>Bán : </strong>{{ number_format($stock->exportPrice)}} VNĐ
                    </td>
                    <td><?php $total = 0;
                        foreach ($stock->order as $item) {
                            $total += $item->quantity;
                        }
                        echo $total . ' sản phẩm';
                        ?>
                    </td>
                    <td>
                        {{ $stock->pro->cat->name }}
                    </td>
                    <td>
                        @if ($stock->importNum==0)
                            <span class="badge badge-success">Hết hàng</span>
                        @elseif($stock->importNum>0 && $stock->importNum<=5)
                            <span class="badge badge-success">Hàng tồn</span>
                        @elseif($total>5)
                            <span class="badge badge-success">Bán chạy</span>
                        @elseif($stock->importNum>5)
                            <span class="badge badge-success">Hàng mới về</span>
                        @endif
                    </td>
                    <td>
                        @can('stock-create')
                            <a href="" class="btn btn-success add" id="{{ $stock->pro->id }}"><i
                                    class="glyphicon glyphicon-plus"></i></a>
                        @endcan
                        @can('stock-update')
                            <a href="" class="btn btn-primary edit" id="{{ $stock->id }}"><i
                                    class="glyphicon glyphicon-pencil"></i></a>
                        @endcan
                        @can('stock-delete')
                            <a href="" class="btn btn-danger delete" id="{{ $stock->id }}"><i
                                    class="glyphicon glyphicon-trash"></i></a>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="clearfix">{{ $listStock->links() }}</div>
    </div>

@stop()
@section('js')
    <script>
        $('#hide').hide();
        $(document).on('click', '.add', function (event) {
            event.preventDefault();
            var count = 1;
            var i = 1;
            insert_multi(count, i);

            function insert_multi(num, i) {
                var html = '<tr class="tr">';
                html += '<td>' + '<select name="colorId[]"><option value="' + null + '">null</option>@foreach($listColor as $color)<option value="'+{{ $color->id }}+
                '">' + '{{ $color->name }}' + '</option>@endforeach</selected>' + '</td>';
                html += '<td>' + '<select name="sizeId[]">@foreach($listSize as $size)<option value="'+{{ $size->id }}+
                '">' + '{{ $size->name }}' + '</option>@endforeach</selected>' + '</td>';
                html += '<td><input type="number" name="importNum[]" class="form-control"></td>';
                html += '<td><input type="number" name="importPrice[]" class="form-control"></td>';
                html += '<td><input type="number" name="exportPrice[]" class="form-control"></td>';
                if (num > 1) {
                    html += '<td><button class="btn btn-danger remove" name="remove" type="button">Remove</button></td></tr>';
                    $('#tbody').append(html);
                } else {
                    html += '<td><button class="btn btn-primary" name="add" id="add" type="button">Add</button></td></tr>';
                    $('#tbody').html(html);
                }
            }

            $('#add').click(function () {
                count++;
                i++;
                insert_multi(count, i);
            });
            $('#tbody').on('click', '.remove', function () {
                $(this).parent().parent().remove();
            });
            var id = $(this).attr('id');
            $('input[name=productId]').val(id);
            $('#hide').show();
            $('#form_add').show();
        });
        $('#hide').click(function (event) {
            event.preventDefault();
            $('#hide').hide();
            $('#form_edit').hide();
            $('#form_add').hide();
        });
        $('#form_add').submit(function (event) {
            event.preventDefault();
            var data = new FormData(this);
            data['_token'] = $('input[name=_token]').val();
            $.ajax({
                url: "{{ route('stocks.store') }}",
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
                        $('#attr_res').html(html);
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
                }
            });
        });
        $(document).on('click', '.edit', function (event) {
            event.preventDefault();
            var data = new FormData();
            var id = $(this).attr('id');
            $('#hidden_id').val($(this).attr('id'));
            data.append('id', id);
            data.append('_token', $('input[name="_token"]').val());
            $.ajax({
                url: "{{ route('stock.edit')}}",
                method: "POST",
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json",
                success: function (html) {
                    $('#color').val(html.data.colorId);
                    $('#size').val(html.data.sizeId);
                    $('#importNum').val(html.data.importNum);
                    $('#importPrice').val(html.data.importPrice);
                    $('#exportPrice').val(html.data.exportPrice);
                    $('#productId').val(html.data.productId);
                    $('#status').val(html.data.status);
                }
            });
            $('#hide').show();
            $('#form_edit').show();
        });
        $('#form_edit').submit(function (event) {
            event.preventDefault();
            var data = new FormData(this);
            data['_token'] = $('input[name=_token]').val();
            $.ajax({
                url: "{{ route('stock.update') }}",
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
                    $('#edit_res').html(html);
                }
            });
        });
        $(document).on('click', '.delete', function (event) {
            event.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var id = $(this).attr('id');
            var data = new FormData();
            data.append('id', id);
            data['_token'] = $('input[name=_token]').val();
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
                            url: "{{ route('stock.delete') }}",
                            method: "POST",
                            data: data,
                            contentType: false,
                            cache: false,
                            processData: false,
                            dataType: "json",
                            success: function (data) {
                                if (data.errors) {
                                    swal({
                                        title: data.errors,
                                        icon: "warning",
                                        buttons: "Done",
                                        dangerMode: true,
                                    })
                                }
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
                            }
                        });
                    } else {
                        swal("Dữ liệu chưa bị xoá");
                    }
                });
        });
    </script>
@endsection
