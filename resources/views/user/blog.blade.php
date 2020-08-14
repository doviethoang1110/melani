@extends('user/master')
@section('head')

<div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-wrap">
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Danh sách bài viết</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
</div>


@stop()

@section('main')

    <!-- breadcrumb area end -->

    <!-- page main wrapper start -->
    <main>
        <!-- blog main wrapper start -->
        <div class="blog-main-wrapper pt-100 pb-100 pt-sm-58 pb-sm-58">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 order-2 order-lg-1">
                        <div class="blog-sidebar-wrapper mt-md-100 mt-sm-58">
                            <div class="blog-sidebar">
                                <h4 class="title">categories</h4>
                                <ul class="blog-archive">
                                    @foreach ($listCatB as $item)
                                    <li><a href="{{ route('blog.detail',['slug'=>$item->slug]) }}">{{ $item->name }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="blog-sidebar">
                                <h4 class="title">recent post</h4>
                                <div class="popular-item-inner popular-item-inner__style-2">
                                    @foreach ($listBlog1 as $item)
                                    <?php 
                                        $image_list = json_decode($item->image);
                                    ?>
                                    <div class="popular-item">
                                        <div class="pop-item-thumb">
                                            <a href="blog-details.html">
                                                
                                                @if (is_array($image_list))
                                                    <div class="blog-single-slide">
                                                        <img src="{{ $image_list[0] }}" alt="">
                                                    </div>
                                                @endif
                                            </a>
                                        </div>
                                        <div class="pop-item-des">
                                            <h4><a href="{{ route('blog.detail',['slug'=>$item->slug]) }}">{{ $item->title }}</a></h4>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 order-1 order-lg-1">
                        <div class="blog-wrapper">
                            <div class="row">
                                @foreach ($listBlog as $item)
                                    
                                <div class="col-lg-12">
                                    <div class="blog-item-single">
                                        <article class="blog-post">
                                            <div class="blog-post-content">
                                                <div class="blog-top">
                                                    <div class="post-date-time">
                                                        <span>{{ $item->created_at->format('d-m-y') }}</span>
                                                    </div>
                                                </div>
                                                <div class="blog-thumb">
                                                    <?php 
                                                        $image_list = json_decode($item->image);
                                                    ?>
                                                    <div class="blog-gallery-slider slider-arrow-style slider-arrow-style__style-2">
                                                        @if (is_array($image_list))
                                                            @foreach ($image_list as $img)
                                                            <div class="blog-single-slide">
                                                                <img src="{{ $img }}" alt="">
                                                            </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="blog-content">
                                                <h4><a href="{{ route('blog.detail',['slug'=>$item->slug]) }}">{{ $item->title }}</a></h4>
                                                <p>
                                                    {{ $item->notes }}
                                                </p>
                                                <a href="{{ route('blog.detail',['slug'=>$item->slug]) }}" class="read-more">Read More...</a>
                                            </div>
                                        </article>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- start pagination area -->
                        <div class="clearfix">{{ $listBlog->links() }}</div>
                        <!-- end pagination area -->
                    </div>
                </div>
            </div>
        </div>
        <!-- blog main wrapper end -->
    </main>


@stop()