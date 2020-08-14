@extends('admin/master')
@section('title','Chi tiết sản phẩm')
@section('main')
    <?php
    $image_list = json_decode($blog->image);
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <h3>Ảnh đại diện</h3>
                @foreach ($image_list as $item)
                    <div class="col-md-4">
                        <img src="{{ $item }}" alt="" width="100px">
                    </div>
                @endforeach
            </div>
            <div class="col-md-9">
                <p><strong>Title : </strong>{{ $blog->title }}</p>
                <p><strong>Notes : </strong>{{ $blog->notes }}</p>
                <p><strong>Trạng thái : </strong>{{ $blog->status ==1? 'Hiển thị' :'Ẩn' }}</p>
                <p><strong>Nội dung : </strong>{!! $blog->description !!}</p>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script>

    </script>
@endsection
