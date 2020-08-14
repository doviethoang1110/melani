@extends('admin/master')
@section('title','Chi tiết sản phẩm')
@section('main')
    <?php
    $image_list = json_decode($pro->image_list);
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <img src="{{ url('uploads') }}/{{ $pro->image }}" alt="" width="100%">

            </div>
            <div class="col-md-7">
                <p><strong>Name : </strong>{{ $pro->name }}</p>
                <p><strong>Discount : </strong>{{ $pro->discount }} %</p>
                <p><strong>Độ ưu tiên : </strong>
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
                </p>
                <p><strong>Lượt xem : </strong>{{ $pro->proView }}</p>
                <p><strong>Danh mục : </strong>{{ $pro->cat->name }}</p>
                <p><strong>Trạng thái : </strong>{{ $pro->status ==1? 'Hiển thị' :'Ẩn' }}</p>
                @if (is_array($image_list))
                    @foreach ($image_list as $item)
                        <div class="col-md-3">
                            <img src="{{$item}}" alt="" width="150px">
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="col-md-12">
                @can('stock-create')
                    <a href="javascript:void(0)" class="btn btn-success" id="add_attr">Thêm mới thuộc tính</a>
                    <a href="javascript:void(0)" class="btn btn-danger" id="hide">Ẩn</a>
                @endcan
            </div>
            <div class="clearfix"></div>
            <hr>
            <span id="attr_res"></span>
            <form action="" id="form_add" hidden>
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
                        <td colspan="5">@csrf</td>
                        <input type="hidden" value="{{ $pro->id }}" name="productId">
                        <td>
                            <button class="btn btn-success" type="submit" id="save" name="Save">Thêm mới</button>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </form>
            @can('stock-list')
                <h3>Chi tiết kho</h3>
                <div class="col-md-12">
                    <table class="table" id="myTable">
                        <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Color</th>
                            <th scope="col">Size</th>
                            <th scope="col">Số lượng</th>
                            <th scope="col">Giá nhập</th>
                            <th scope="col">Giá bán</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($listStock as $stock)
                            <tr>
                                <td>{{ $stock->pro->name }}</td>
                                @if (!empty($stock->color->name))
                                    <td>{{ $stock->color->name }}</td>
                                @else
                                    <td>Không</td>
                                @endif
                                @if (!empty($stock->size->name))
                                    <td>{{ $stock->size->name }}</td>
                                @else
                                    <td>Không</td>
                                @endif
                                <td>{{ $stock->importNum }}</td>
                                <td>{{ $stock->importPrice }}</td>
                                <td>{{ $stock->exportPrice }}</td>
                                <td>
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
                </div>
            @endcan
        </div>
    </div>
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
                            <label for="" class="control-label">Tên sản phẩm</label>
                            <input type="text" class="form-control" id="name" name="name" readonly>
                            <input type="hidden" name="productId" id="productId">
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Màu :</label>
                            <select name="colorId" id="color" class="form-control">
                                <option value="">Không</option>
                                @foreach ($listColor as $color)
                                    <option value="{{ $color->id }}">{{ $color->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Cỡ:</label>
                            <select name="sizeId" id="size" class="form-control">
                                @foreach ($listSize as $size)
                                    <option value="{{ $size->id }}">{{ $size->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Giá nhập</label>
                            <input type="number" class="form-control" id="importPrice" name="importPrice">
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Giá bán</label>
                            <input type="number" class="form-control" id="exportPrice" name="exportPrice">
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Số lượng nhập</label>
                            <input type="number" class="form-control" id="importNum" name="importNum">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                            <input type="hidden" name="hidden_id" id="hidden_id">
                            <input type="hidden" name="status" id="hidden_status">
                            <button type="submit" class="btn btn-success" value="" id="action"></button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $('#myTable').DataTable();
        $('#hide').hide();
        $('#add_attr').click(function (event) {
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
            $('#form_add').show();
            $('#hide').show();
        });
        $('#hide').click(function (event) {
            event.preventDefault();
            $('#form_add').hide();
            $('#hide').hide();
        });
        $(document).on('click', '.edit', function (event) {
            event.preventDefault();
            var id = $(this).attr('id');
            var data = new FormData();
            data.append('id', id);
            data.append('_token', $('input[name="_token"]').val());
            $('#form_result').html('');
            $.ajax({
                url: "{{ route('stock.edit') }}",
                method: "POST",
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json",
                success: function (html) {
                    $('#productId').val(html.data.productId);
                    $('#name').val(html.data.name);
                    $('#color').val(html.data.colorId);
                    $('#size').val(html.data.sizeId);
                    $('#importNum').val(html.data.importNum);
                    $('#importPrice').val(html.data.importPrice);
                    $('#exportPrice').val(html.data.exportPrice);
                    $('#hidden_status').val(html.data.status);
                    $('#hidden_id').val(html.data.id);
                    $('.modal-title').text('Cập nhật kho');
                    $('#action').text('Cập nhật');
                    $('#action').attr('value', 'Edit');
                    $('#myModal').modal('show');
                }
            });
        });
        $('#form_edit').on('submit', function (event) {
            event.preventDefault();
            var id_edit = $('#hidden_id').val();
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
                    $('#form_result').html(html);
                }
            });
        });
        $('#form_add').on('submit', function (event) {
            event.preventDefault();
            var data = new FormData(this);
            data.append('productId', $('input[name=productId]').val())
            data['_token'] = $('input[name=_token]').val();
            $.ajax({
                url: "{{ route('stock.insert') }}",
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
