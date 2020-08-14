@extends('admin/master')
@section('title','Thêm mới sản phẩm')
@section('main')
    <form action="" method="POST" role="form" id="form_demo">
        @csrf
        <span id="form_result"></span>
        <div class="col-md-6">
            <div class="form-group">
                <label for="" class="control-label">Tên danh mục</label>
                <input type="text" class="form-control" id="name" name="name">
            </div>
            <div class="form-group">
                <label for="" class="control-label">Slug :</label>
                <input type="text" class="form-control" id="slug" name="slug">
            </div>
            <div class="form-group">
                <label for="" class="control-label">Danh mục :</label>
                <select name="catalogId" id="catalogId" class="form-control">
                    <option>----Chọn danh mục----</option>
                    <?php showCategoriesAdd($listCat);?>
                </select>
            </div>
            <div class="form-group">
                <label for="" class="control-label">Phần trăm giảm giá :</label>
                <input type="number" name="discount" id="discount" class="form-control">
                <input type="hidden" value="300" name="proView" id="proView">
            </div>
            <div class="form-group">
                <label for="" class="control-label">Độ ưu tiên :</label>
                <select name="priority" id="priority" class="form-control">
                    <option value="1">Nổi bật</option>
                    <option value="2">Bán chạy</option>
                    <option value="3">Mới</option>
                </select>
            </div>
            <div class="form-group">
                <label for="" class="control-label">Trạng thái :</label>
                <input type="radio" value="1" name="status"> Hiển thị
                <input type="radio" value="0" name="status" checked> Ẩn
            </div>
            <div class="form-group">
                <label for="" class="control-label">Ảnh :</label>
                <div class="input-group">
                    <input class="form-control" name="image" id="image" type="text"/>
                    <span class="input-group-btn">
              <a type="button" class="btn btn-default" href="#modal-file" data-toggle="modal">Select</a>
          </span>
                </div>
                <img src="" id="show_img" alt="" width="250px">
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Color</th>
                    <th scope="col">Size</th>
                    <th scope="col">Số lượng nhập</th>
                    <th scope="col">Giá nhập</th>
                    <th scope="col">Giá bán</th>
                </tr>
                </thead>
                <tbody id="tbody">
                </tbody>
            </table>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="" class="control-label">Nội dung :</label>
                <textarea name="description" id="content" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="" class="control-label">Ảnh khác :</label>
                <div class="input-group">
                    <input class="form-control" name="image_list" id="image_list" type="hidden"/>
                    <span class="input-group-btn">
              <a type="button" class="btn btn-default" href="#modal-files" data-toggle="modal">Select</a>
          </span>
                </div>
                <div id="img_list_show">

                </div>
            </div>
        </div>
        <div class="col-md-12">
            <button type="submit" class="btn btn-success" value="Add" id="action">Thêm mới</button>
        </div>
    </form>
    <div id="modal-file" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width:85%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Quản lý file</h4>
                </div>
                <div class="modal-body">
                    <iframe width="100%" height="550" frameborder="0"
                            src="{{ url('file') }}/dialog.php?akey='dojkDpbuTKtuZ8vAZvP8JQ2OCOVtQXxPv1dWA0I'&field_id=image">
                    </iframe>
                </div>
            </div>

        </div>
    </div>
    <div id="modal-files" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width:85%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Quản lý file</h4>
                </div>
                <div class="modal-body">
                    <iframe width="100%" height="550" frameborder="0"
                            src="{{ url('file') }}/dialog.php?akey='dojkDpbuTKtuZ8vAZvP8JQ2OCOVtQXxPv1dWA0I'&field_id=image_list">
                    </iframe>
                </div>
            </div>

        </div>
    </div>
@endsection
<?php
function showCategoriesAdd($categories, $parent_id = 0, $char = '')
{
    foreach ($categories as $key => $cat) {
        if ($cat['parentId'] == $parent_id) {
            echo '<option value="' . $cat['id'] . '">';
            echo $char . $cat['name'];
            echo '</option>';
            unset($categories[$key]);
            showCategoriesAdd($categories, $cat['id'], $char . '|---');
        }
    }
}
?>
@section('js')
    <script src="{{url('/public/admin')}}/js/slug.js"></script>
    <script src="{{url('/public/admin')}}/tinymce/tinymce.min.js"></script>
    <script src="{{url('/public/admin')}}/tinymce/config.js"></script>
    <script>
        $('#modal-file').on('hide.bs.modal', function () {
            var img = $('input#image').val();
            $('#show_img').attr('src', img);
        });
        $('#modal-files').on('hide.bs.modal', function () {
            var imgs = $('input#image_list').val();
            var img_list = $.parseJSON(imgs);
            var html = '';
            for (var i = 0; i < img_list.length; i++) {
                html += '<div class="col-md-3 thumbnail">';
                html += '<img src="' + img_list[i] + '" alt="">';
                html += '</div>';
            }
            $('#img_list_show').html(html);
        });
        $('#form_demo').on('submit', function (event) {
            event.preventDefault();
            if ($('#action').val() == 'Add') {
                var data = new FormData(this);
                data.append('name', $('#name').val());
                data.append('slug', $('#slug').val());
                data.append('discount', $('#discount').val());
                data.append('image', $('#image').val());
                data.append('image_list', $('#image_list').val());
                data.append('catalogId', $('#catalogId').val());
                data.append('description', tinyMCE.activeEditor.getContent());
                data.append('proView', $('#proView').val());
                data.append('priority', $('#priority').val());
                data.append('status', $('input[name="status"]:checked').val());
                data.append('_token', $('input[name=_token]').val());
                $.ajax({
                    url: "{{ route('product.store') }}",
                    method: "POST",
                    data: data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    success: function (data) {
                        var html = '';
                        var err_html = '';
                        if (data.errors) {
                            html += '<div class="alert alert-danger">';
                            for (var i = 0; i < data.errors.length; i++) {
                                html += '<p>' + data.errors[i] + '</p>';

                            }
                            html += '</div>';
                            $('#form_result').html(html);
                        }
                        if (data.success) {
                            alert('' + data.success + '');
                            window.location.href = "http://localhost/test1/admin/product";
                        }
                    }
                });
            }
        });
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
    </script>
@endsection
