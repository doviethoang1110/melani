@extends('user/master')


@section('main')
<div class="hero-area">
        <div class="hero-slider-active slider-arrow-style slick-dot-style hero-dot">
            @foreach ($banner1 as $item)
                
            <div class="hero-single-slide hero-1 d-flex align-items-center" style="background-image: url(uploads/{{ $item->image }});">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="slider-content">
                                <h1>{{ $item->title }}</h1>
                                <h3>{{ $item->content }}</h3>
                                <a href="{{ $item->links }}" class="slider-btn">view collection</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <!-- slider area end -->

    <!-- banner statistics 01 start -->
    <div class="banner-statistic-one bg-gray pt-100 pb-100 pt-sm-58 pb-sm-58">
        <div class="container">
            <div class="row">
                <div class="col1 col-sm-6">
                    <div class="img-container img-full fix">
                        <a href="{{ $banner2->links }}">
                            <img src="{{ url('uploads') }}/{{ $banner2->image }}" alt="banner image">
                        </a>
                    </div>
                </div>
                <div class="col2 col-sm-6">
                    <div class="img-container img-full fix">
                        <a href="{{ $banner4->links }}">
                            <img src="{{ url('uploads') }}/{{ $banner4->image }}" alt="banner image">
                        </a>
                    </div>
                </div>
                <div class="col3 col">
                    @foreach ($banner3 as $item)
                        
                    <div class="img-container img-full fix mb-30">
                        <a href="{{ $item->links }}">
                            <img src="{{ url('uploads') }}/{{ $item->image }}" alt="banner image">
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- banner statistics 01 end -->

    <!-- featured product area start -->
    <div class="page-section pt-100 pb-14 pt-sm-60 pb-sm-0">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title text-center pb-44">
                        <p>The latest featured products</p>
                        <h2>Featured products</h2>
                    </div>
                </div>
            </div>
            <div class="row product-carousel-one spt slick-arrow-style" data-row="2">
                @foreach ($listProViewTop as $pro)
                    
                <div class="col">
                    <div class="product-item mb-20">
                        <div class="product-thumb">
                            <a href="{{ route('shop_detail',['slug'=>$pro->slug]) }}">
                                <img src="{{ url('uploads') }}/{{ $pro->image }}" alt="product image">
                            </a>
                            <div class="box-label">
                                @switch($pro->priority)
                                    @case(1)
                                    <div class="product-label badge badge-primary">
                                        <span>
                                            Nổi bật
                                        </span>
                                    </div> 
                                        @break
                                    @case(2)
                                    <div class="product-label badge badge-warning">
                                        <span>
                                            Bán chạy
                                        </span>
                                    </div> 
                                        @break
                                    @case(3)
                                    <div class="product-label new">
                                        <span>
                                            Mới
                                        </span>
                                    </div> 
                                    @break
                                    @default
                                        
                                @endswitch
                                <div class="product-label discount">
                                    @if ($pro->discount>0)
                                        
                                    <span>-{{ $pro->discount }}%</span>
                                    @else
                                    <span></span>
                                    @endif
                                </div>
                            </div>
                            <div class="product-action-link">
                                <a href="#" data-toggle="tooltip" data-placement="left" title="Compare" class="compare" id="{{ $pro->id }}"><i
                                    class="ion-ios-loop"></i></a>
                                <a href="#" id="{{ $pro->id }}" class="add_wishList"><span
                                    data-toggle="tooltip" data-placement="left" title="Wishlist"><i
                                    class="ion-ios-shuffle"></i></span></a>
                                @if (Session::has('customer'))
                                    <input type="hidden" name="customerId" value="{{ Session::get('customer')->id }}">
                                @else
                                    <input type="hidden" name="customerId" value="">
                                @endif
                                @csrf
                            </div>
                        </div>
                        <div class="product-description text-center">
                            <div class="product-name">
                                <h3><a href="{{ route('shop_detail',['slug'=>$pro->slug]) }}">{{ $pro->name }}</a></h3>
                            </div>
                            <div class="price-box">
                                @if ($pro->discount>0)
                                <span class="regular-price">{{ number_format(($pro->exportPrice)-($pro->exportPrice*$pro->discount)/100) }} VNĐ</span>
                                <span class="old-price"><del>{{ number_format($pro->exportPrice) }} VNĐ</del></span>
                                @else
                                <span class="regular-price">{{ number_format($pro->exportPrice) }} VNĐ</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                
            </div>
        </div>
    </div>
    <!-- featured product area end -->

    <!-- block container start -->
    <div class="page-section  bg-gray-light">
        <div class="container-fluid p-0">
            <div class="row no-gutters align-items-center">
                <div class="col-lg-6 col-md-6">
                    <div class="block-container-banner img-full fix">
                        <a href="#">
                            <img src="public/img/banner/block-container.jpg" alt="">
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="bloc-container-inner text-center pt-sm-54 pb-sm-60">
                        <h2>New Fragrances</h2>
                        <p>Diverse variety of perfumes from top brands at the discount prices</p>
                        <a href="{{ route('shop') }}">SHOP PERFUMES</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- block container end -->

    <!-- banner feature start -->
    <div class="banner-feature-area bg-navy-blue pt-62 pb-60 pt-sm-56 pb-sm-20">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-4">
                    <div class="banner-single-feature text-center mb-sm-30">
                        <i class="ion-paper-airplane"></i>
                        <h4>FREE SHIPPING & DELIVERY</h4>
                        <p>We’re one of the furniture online retailers, who offer free of charge delivery</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="banner-single-feature text-center mb-sm-30">
                        <i class="ion-ios-time-outline"></i>
                        <h4>365-DAY HOME TRIAL</h4>
                        <p>Our unique return policy will allow you to return furniture for almost a year</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="banner-single-feature text-center mb-sm-30">
                        <i class="ion-trophy"></i>
                        <h4>LIFETIME WARRANTY</h4>
                        <p>Purchasing furniture with us comes with warranty, than anyone else offers!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- banner feature end -->

    <!-- feature category area start -->
    <div class="feature-category-area pt-96 pb-14 pt-md-114 pt-sm-54 pb-sm-0">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="tab-menu-one mb-58">
                        <ul class="nav justify-content-center">
                            <li>
                                <a class="active" data-toggle="tab" href="#tab_one">onsale</a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#tab_two">bestseller</a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#tab_three">featured</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content text-center">
                        <div class="tab-pane fade show active" id="tab_one">
                            <div class="row feature-category-carousel slick-arrow-style spt">
                                @foreach ($listProSale as $pro)
                                    
                                <div class="col">
                                    <div class="product-item mb-20">
                                        <div class="product-thumb">
                                            <a href="{{ route('shop_detail',['slug'=>$pro->slug]) }}">
                                                <img src="{{ url('uploads') }}/{{ $pro->image }}" alt="product image">
                                            </a>
                                            <div class="box-label">
                                                @switch($pro->priority)
                                                @case(1)
                                                <div class="product-label badge badge-primary">
                                                    <span>
                                                        Nổi bật
                                                    </span>
                                                </div> 
                                                    @break
                                                @case(2)
                                                <div class="product-label badge badge-warning">
                                                    <span>
                                                        Bán chạy
                                                    </span>
                                                </div> 
                                                    @break
                                                @case(3)
                                                <div class="product-label new">
                                                    <span>
                                                        Mới
                                                    </span>
                                                </div> 
                                                @break
                                                @default
                                                    
                                            @endswitch
                                                <div class="product-label discount">
                                                    @if ($pro->discount>0)
                                                        
                                                    <span>-{{ $pro->discount }}%</span>
                                                    @else
                                                    <span></span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="product-action-link">
                                                <a href="#" data-toggle="tooltip" data-placement="left" title="Compare" class="compare" id="{{ $pro->id }}"><i
                                                    class="ion-ios-loop"></i></a>
                                                <a href="#" id="{{ $pro->id }}" class="add_wishList"><span
                                                    data-toggle="tooltip" data-placement="left" title="Wishlist"><i
                                                    class="ion-ios-shuffle"></i></span></a>
                                                @if (Session::has('customer'))
                                                    <input type="hidden" name="customerId" value="{{ Session::get('customer')->id }}">
                                                @else
                                                    <input type="hidden" name="customerId" value="">
                                                @endif
                                                @csrf
                                            </div>
                                        </div>
                                        <div class="product-description text-center">
                                            <div class="product-name">
                                                <h3><a href="{{ route('shop_detail',['slug'=>$pro->slug]) }}">{{ $pro->name }}</a></h3>
                                            </div>
                                            <div class="price-box">
                                                @if ($pro->discount>0)
                                                <span class="regular-price">{{ number_format(($pro->exportPrice)-($pro->exportPrice*$pro->discount)/100) }} VNĐ</span>
                                                <span class="old-price"><del>{{ number_format($pro->exportPrice) }} VNĐ</del></span>
                                                @else
                                                <span class="regular-price">{{ number_format($pro->exportPrice) }} VNĐ</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab_two">
                            <div class="row feature-category-carousel slick-arrow-style spt">
                                @foreach ($listProBuy as $pro)
                                    
                                <div class="col">
                                    <div class="product-item mb-20">
                                        <div class="product-thumb">
                                            <a href="{{ route('shop_detail',['slug'=>$pro->slug]) }}">
                                                <img src="{{ url('uploads') }}/{{ $pro->image }}" alt="product image">
                                            </a>
                                            <div class="box-label">
                                                @switch($pro->priority)
                                                @case(1)
                                                <div class="product-label badge badge-primary">
                                                    <span>
                                                        Nổi bật
                                                    </span>
                                                </div> 
                                                    @break
                                                @case(2)
                                                <div class="product-label badge badge-warning">
                                                    <span>
                                                        Bán chạy
                                                    </span>
                                                </div> 
                                                    @break
                                                @case(3)
                                                <div class="product-label new">
                                                    <span>
                                                        Mới
                                                    </span>
                                                </div> 
                                                @break
                                                @default
                                                    
                                            @endswitch
                                            <div class="product-label discount">
                                                @if ($pro->discount>0)
                                                    
                                                <span>-{{ $pro->discount }}%</span>
                                                @else
                                                <span></span>
                                                @endif
                                            </div>
                                            </div>
                                            <div class="product-action-link">
                                                <a href="#" data-toggle="tooltip" data-placement="left" title="Compare" class="compare" id="{{ $pro->id }}"><i
                                                    class="ion-ios-loop"></i></a>
                                                <a href="#" id="{{ $pro->id }}" class="add_wishList"><span
                                                    data-toggle="tooltip" data-placement="left" title="Wishlist"><i
                                                    class="ion-ios-shuffle"></i></span></a>
                                                @if (Session::has('customer'))
                                                    <input type="hidden" name="customerId" value="{{ Session::get('customer')->id }}">
                                                @else
                                                    <input type="hidden" name="customerId" value="">
                                                @endif
                                                @csrf
                                            </div>
                                        </div>
                                        <div class="product-description text-center">
                                            <div class="product-name">
                                                <h3><a href="{{ route('shop_detail',['slug'=>$pro->slug]) }}">{{ $pro->name }}</a></h3>
                                            </div>
                                            <div class="price-box">
                                                @if ($pro->discount>0)
                                                <span class="regular-price">{{ number_format(($pro->exportPrice)-($pro->exportPrice*$pro->discount)/100) }} VNĐ</span>
                                                <span class="old-price"><del>{{ number_format($pro->exportPrice) }} VNĐ</del></span>
                                                @else
                                                <span class="regular-price">{{ number_format($pro->exportPrice) }} VNĐ</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab_three">
                            <div class="row feature-category-carousel slick-arrow-style spt">
                                @foreach ($listProView as $pro)
                                    
                                <div class="col">
                                    <div class="product-item mb-20">
                                        <div class="product-thumb">
                                            <a href="{{ route('shop_detail',['slug'=>$pro->slug]) }}">
                                                <img src="{{ url('uploads')}}/{{ $pro->image }}" alt="product image">
                                            </a>
                                            <div class="box-label">
                                                @switch($pro->priority)
                                                @case(1)
                                                <div class="product-label badge badge-primary">
                                                    <span>
                                                        Nổi bật
                                                    </span>
                                                </div> 
                                                    @break
                                                @case(2)
                                                <div class="product-label badge badge-warning">
                                                    <span>
                                                        Bán chạy
                                                    </span>
                                                </div> 
                                                    @break
                                                @case(3)
                                                <div class="product-label new">
                                                    <span>
                                                        Mới
                                                    </span>
                                                </div> 
                                                @break
                                                @default
                                                    
                                            @endswitch
                                                <div class="product-label discount">
                                                    @if ($pro->discount>0)
                                                        
                                                    <span>-{{ $pro->discount }}%</span>
                                                    @else
                                                    <span></span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="product-action-link">
                                                <a href="#" data-toggle="tooltip" data-placement="left" title="Compare" class="compare" id="{{ $pro->id }}"><i
                                                    class="ion-ios-loop"></i></a>
                                                <a href="#" id="{{ $pro->id }}" class="add_wishList"><span
                                                    data-toggle="tooltip" data-placement="left" title="Wishlist"><i
                                                    class="ion-ios-shuffle"></i></span></a>
                                                @if (Session::has('customer'))
                                                    <input type="hidden" name="customerId" value="{{ Session::get('customer')->id }}">
                                                @else
                                                    <input type="hidden" name="customerId" value="">
                                                @endif
                                                @csrf
                                            </div>
                                        </div>
                                        <div class="product-description text-center">
                                            <div class="product-name">
                                                <h3><a href="{{ route('shop_detail',['slug'=>$pro->slug]) }}">{{ $pro->name }}</a></h3>
                                            </div>
                                            <div class="price-box">
                                                @if ($pro->discount>0)
                                                <span class="regular-price">{{ number_format(($pro->exportPrice)-($pro->exportPrice*$pro->discount)/100) }} VNĐ</span>
                                                <span class="old-price"><del>{{ number_format($pro->exportPrice) }} VNĐ</del></span>
                                                @else
                                                <span class="regular-price">{{ number_format($pro->exportPrice) }} VNĐ</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- feature category area end -->

    <!-- banner statistics 02 end -->

    <!-- latest blog area start -->
    <div class="latest-blog-area pt-100 pb-90 pt-sm-58 pb-sm-50">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title text-center pb-44">
                        <p>New our blogs</p>
                        <h2>From the blog</h2>
                    </div>
                </div>
            </div>
            <div class="blog-carousel-active row slick-arrow-style">
                @foreach ($listBlog as $item)
                    
                <div class="col">
                    <div class="blog-item">
                        <article class="blog-post">
                            <div class="blog-post-content">
                                <div class="blog-top">
                                    <div class="post-date-time">
                                        <span>{{ $item->created_at->format('d M, Y') }}</span>
                                    </div>
                                </div>
                                <?php 
                                        $image_list = json_decode($item->image);
                                    ?>
                                <div class="blog-thumb img-full">
                                    <a href="{{ route('blog.detail',['slug'=>$item->slug]) }}">
                                        @if (is_array($image_list))
                                        <img src="{{ $image_list[0] }}" alt="">
                                        @endif
                                    </a>
                                </div>
                            </div>
                            <div class="blog-content">
                                <h4><a href="{{ route('blog.detail',['slug'=>$item->slug]) }}">{{ $item->title }}</a></h4>
                                <p>
                                    {{ $item->notes }}
                                </p>
                            </div>
                        </article>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@stop()