@extends('admin/master')
@section('title','Cập nhật bài viết')
@section('main')
    <form action="" method="POST" role="form" id="form_demo">
        @csrf
        <span id="form_result"></span>
        <div class="col-md-5">
            <div class="form-group">
                <label for="" class="control-label">Tiêu đề bài viết</label>
                <input type="text" class="form-control" id="name" name="title" value="{{ $blog->title }}">
                <input type="hidden" value="{{ $blog->id }}" id="blogId">
            </div>
            <div class="form-group">
                <label for="" class="control-label">Slug :</label>
                <input type="text" class="form-control" id="slug" name="slug" value="{{ $blog->slug }}">
            </div>
            <div class="form-group">
                <label for="" class="control-label">Danh mục :</label>
                <select name="catalogId" id="catalogId">
                    <option>----Chọn danh mục----</option>
                    @foreach ($listCatB as $item)
                        <?php $selected = $blog->catalogId == $item->id ? 'selected' : '';?>
                        <option value="{{ $item->id }}" {{ $selected }}>{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="" class="control-label">Giới thiệu :</label>
                <textarea name="notes" cols="50" rows="10" class="form-control" id="notes">{{ $blog->notes }}</textarea>
            </div>
            <div class="form-group">
                <label for="" class="control-label">Trạng thái :</label>
                <input type="radio" value="1" name="status" {{ $checked = $blog->status==1 ? 'checked' : '' }}> Hiển thị
                <input type="radio" value="0" name="status" {{ $checked = $blog->status==0 ? 'checked' : '' }}> Ẩn
            </div>
        </div>

        <div class="col-md-7">
            <div class="form-group">
                <label for="" class="control-label">Nội dung :</label>
                <textarea name="description" id="content" class="form-control">{{ $blog->description }}</textarea>
            </div>
            <div class="form-group">
                <label for="" class="control-label">Ảnh :</label>
                <div class="input-group">
                    <input class="form-control" name="image" id="image_list" type="hidden" value="{{ $blog->image }}"/>
                    <span class="input-group-btn">
                    <a type="button" class="btn btn-default" href="#modal-files" data-toggle="modal">Select</a>
                </span>
                </div>
                <?php
                $image_list = json_decode($blog->image);
                ?>
                <div id="img_list_show">
                    @if (is_array($image_list))
                        @foreach ($image_list as $item)
                            <div class="col-md-3 thumbnail">
                                <img src="{{ $item }}" alt="">
                            </div>
                        @endforeach
                    @endif
                </div>

            </div>
        </div>
        <div class="col-md-12">
            <button type="submit" class="btn btn-success" value="Edit" id="action">Cập nhật</button>
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
            if ($('#action').val() == 'Edit') {
                var data = new FormData(this);
                var id = $('#blogId').val();
                data['_token'] = $('input[name=_token]').val();
                data.append('id', id);
                data.append('_method', 'PUT');
                $.ajax({
                    url: "{{ route('blog.update',"+id+") }}",
                    method: "POST",
                    data: data,
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    success: function (data) {
                        var html = '';
                        if (data.errors) {
                            html += '<div class="alert alert-danger">';
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
                                location.href = "http://localhost/test1/admin/blog";
                            });
                        }
                        $('#form_result').html(html);
                    }
                });
            }
        });

    </script>
@endsection
