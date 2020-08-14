@extends('user/master')
@section('main')

<div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-wrap">
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">compare</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb area end -->

    <!-- page main wrapper start -->
    <main>
        <!-- compare main wrapper start -->
        <div class="compare-page-wrapper pt-100 pb-100 pt-sm-58 pb-sm-58">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- Compare Page Content Start -->
                        <div class="compare-page-content-wrap">
                            <div class="compare-table table-responsive">
                                <table class="table table-bordered mb-0">
                                    @if (Session::has('pro') && count(Session::get('pro'))>0)
                                        <?php $items = Session::get('pro');?>
                                    <tbody>
                                    <tr>
                                        <td class="first-column">Product</td>
                                        @foreach ($items as $item)
                                        <td class="product-image-title">
                                            <a href="product-details.html" class="image">
                                                <img class="img-fluid" src="{{ url('uploads') }}/{{ $item['image'] }}" alt="Compare Product">
                                            </a>
                                            <a href="#" class="category">{{ $item['category'] }}</a>
                                            <a href="product-details.html" class="title">{{ $item['name'] }}</a>
                                        </td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td class="first-column">Description</td>
                                        @foreach ($items as $item)
                                            
                                        <td class="pro-desc">
                                            <p>{{ $item['description'] }}</p>
                                        </td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td class="first-column">Attribute</td>
                                        @foreach ($items as $item)
                                            
                                        <td class="pro-color">
                                            <span>Màu : </span>
                                            @foreach ($item['color'] as $c)
                                                @if(is_array($item['color']))
                                                {{ $c }},
                                                @else
                                                <span>không</span>
                                                @endif
                                            @endforeach<br>
                                            <span>Cỡ : </span>
                                            @foreach ($item['size'] as $c)
                                                {{ $c }},
                                            @endforeach
                                        </td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td class="first-column">Rating</td>
                                        @foreach ($items as $item)
                                        <td class="pro-ratting">
                                                @for ($i = 0; $i < $item['review']; $i++)
                                                    
                                                <i class="fa fa-star"></i>
                                                @endfor
                                            </td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td class="first-column">Remove</td>
                                        @csrf
                                        @foreach ($items as $item)
                                            
                                        <td class="pro-remove">
                                            <button id="{{ $item['id'] }}" class="remove_compare"><i class="fa fa-trash-o"></i></button>
                                        </td>
                                        @endforeach
                                    </tr>
                                    </tbody>
                                    @else
                                    <h1>Chưa có sản phẩm để so sánh</h1>
                                    @endif
                                </table>
                            </div>
                        </div>
                        <!-- Compare Page Content End -->
                    </div>
                </div>
            </div>
        </div>
        <!-- compare main wrapper end -->
    </main>

@stop()
@section('js')
    <script>
        $(document).on('click','.remove_compare',function (event){
            event.preventDefault();
            var id = $(this).attr('id');
            var data = new FormData();
            data.append('id',id);
            data.append('_token',$('input[name="_token"]').val());
            $.ajax({
                url:"{{ route('remove.compare') }}",
                method:"POST",
                data:data,
                contentType:false,
                cache:false,
                processData:false,
                dataType:"json",
                success:function(data){
                    if(data.success){
                        toastr["success"](data.success)
                    }
                }
            });
        });
    </script>
@endsection