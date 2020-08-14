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
                                <li class="breadcrumb-item active" aria-current="page">
                                    @if (isset($cat))
                                        {{ $cat->name }}
                                    @else
                                        Tất cả sản phẩm
                                    @endif
                                </li>
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
        <div class="shop-main-wrapper pt-100 pb-100 pt-sm-58 pb-sm-58">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 col-lg-4 order-2 order-lg-1">
                        <div class="sidebar-wrapper mt-md-100 mt-sm-48">
                            <!-- single sidebar start -->
                            <div class="sidebar-single">
                                <div class="sidebar-title">
                                    <h3>shop</h3>
                                </div>
                                <div class="sidebar-body">
                                        <?php 
                                        showCategories($listCat);
                                        ?>
                                        <?php 
                                        function showCategories($categories ,$parentId = 0, $ul_class = 'sidebar-category')
                                            {
                                                $cate_child = array();
                                                foreach ($categories as $key => $item)
                                                {
                                                    if ($item['parentId'] == $parentId)
                                                    {
                                                        $cate_child[] = $item;
                                                        unset($categories[$key]);
                                                    }
                                                }
                                                if ($cate_child)
                                                {
                                                    
                                                    echo '<ul class="'.$ul_class.'">';
                                                    foreach ($cate_child as $key => $itemc)
                                                    {
                                                        echo '<li>'.'<a href="'.route('pro_cat',["slug"=>$itemc->slug]).'">'.$itemc['name'].'</a>';
                                                            
                                                        showCategories($categories, $itemc['id'],'children');
                                                        echo '</li>';
                                                    }
                                                    echo '</ul>';
                                                }
                                        
                                            }
                                        ?>
                                </div>
                            </div>
                            <div class="sidebar-single">
                                <div class="sidebar-title">
                                    <h3>popular product</h3>
                                </div>
                                <div class="sidebar-body">
                                    <div class="popular-item-inner">
                                        @foreach ($proPopular as $pro)
                                            
                                        <div class="popular-item">
                                            <div class="pop-item-thumb">
                                                <a href="{{ route('shop_detail',["slug"=>$pro->slug]) }}">
                                                    <img src="{{ url('uploads') }}/{{ $pro->image }}" alt="">
                                                </a>
                                            </div>
                                            <div class="pop-item-des">
                                                <h4><a href="{{ route('shop_detail',["slug"=>$pro->slug]) }}">{{ $pro->name }}</a></h4>
                                                @if ($pro->discount>0)
                                                <span class="pop-price">{{ number_format(($pro->exportPrice)-($pro->exportPrice*$pro->discount)/100) }} VNĐ</span>
                                                @else
                                                <span class="pop-price">{{ number_format($pro->exportPrice) }} VNĐ</span>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <!-- single sidebar end -->

                            <!-- single sidebar start -->
                            <div class="sidebar-single">
                                <div class="advertising-thumb img-full fix">
                                    <a href="{{ $banner4->links }}">
                                        <img src="{{ url('uploads') }}/{{ $banner4->image }}" alt="">
                                    </a>
                                </div>
                            </div>
                            <!-- single sidebar end -->
                        </div>
                    </div>
                    <!-- product view wrapper area start -->
                    <div class="col-xl-9 col-lg-8 order-1 order-lg-2">
                        <div class="shop-product-wrapper">
                            <!-- shop product top wrap start -->
                            <div class="shop-top-bar">
                                <div class="row">
                                    <div class="col-lg-7 col-md-6">
                                        <div class="top-bar-left">
                                            <div class="product-view-mode">
                                                <a class="active" href="#" data-target="grid"><i class="fa fa-th"></i></a>
                                                <a href="#" data-target="list"><i class="fa fa-list"></i></a>
                                            </div>
                                            <a href="{{ route('compare') }}" class="btn btn-success">So sánh</a>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-md-6">
                                        <div class="top-bar-right">
                                            <div class="product-short">
                                                <p>Sort By : </p>
                                                <select class="nice-select" name="sortby" onchange="location = this.value;">
                                                    <option value="">Relevance</option>
                                                    <option value="{{ route('sortBy',["field"=>'name',"attr"=>'asc']) }}">Name (A - Z)</option>
                                                    <option value="{{ route('sortBy',["field"=>'name',"attr"=>'desc']) }}">Name (Z - A)</option>
                                                    <option value="{{ route('sortBy',["field"=>'exportPrice',"attr"=>'asc']) }}">Price (Low &gt; High)</option>
                                                    <option value="{{ route('sortBy',["field"=>'exportPrice',"attr"=>'desc']) }}">Price (High &gt; Low)</option>
                                                    <option value="{{ route('sortBy',["field"=>'proView',"attr"=>'desc']) }}">Lượt xem</option>
                                                    <option value="{{ route('sortBy',["field"=>'priority',"attr"=>'desc']) }}">Nổi bật</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- shop product top wrap start -->
                            <!-- product view mode wrapper start -->
                            <div class="shop-product-wrap grid row">
                                @foreach ($listPro as $pro)
                                <div class="col-xl-4 col-lg-6 col-md-4 col-sm-6">
                                    <!-- product grid item start -->
                                    <div class="product-item mb-20">
                                        <div class="product-thumb">
                                            <a href="{{ route('shop_detail',["slug"=>$pro->slug]) }}">
                                                <img src="{{ url('/uploads') }}/{{ $pro->image }}" alt="product image" id="pro_img">
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
                                                <h3><a href="{{ route('shop_detail',["slug"=>$pro->slug]) }}">{{ $pro->name }}</a></h3>
                                            </div>
                                            <div class="price-box">
                                                @if ($pro->discount>0)
                                                <span class="regular-price">{{ number_format(($pro->exportPrice)-($pro->exportPrice*$pro->discount)/100) }} VNĐ</span>
                                                <span class="old-price"><del>{{ number_format($pro->exportPrice) }} VNĐ</del></span>
                                                @else
                                                <span class="regular-price">{{ number_format($pro->exportPrice) }} VNĐ</span>
                                                @endif
                                            </div>
                                            <div class="hover-box text-center">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- product grid item end -->
                                    <!-- product list item start -->
                                    <div class="product-list-item mb-20">
                                        <div class="product-thumb">
                                            <a href="{{ route('shop_detail',["slug"=>$pro->slug]) }}">
                                                <img src="{{ url('/uploads') }}/{{ $pro->image }}" alt="product image">
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
                                        <div class="product-list-content">
                                            <h3><a href="{{ route('shop_detail',["slug"=>$pro->slug]) }}">{{ $pro->name }}</a></h3>
                                            <div class="price-box">
                                                @if ($pro->discount>0)
                                                <span class="regular-price">{{ number_format(($pro->exportPrice)-($pro->exportPrice*$pro->discount)/100) }} VNĐ</span>
                                                <span class="old-price"><del>{{ number_format($pro->exportPrice) }} VNĐ</del></span>
                                                @else
                                                <span class="regular-price">{{ number_format($pro->exportPrice) }} VNĐ</span>
                                                @endif
                                            </div>
                                            <p>{{ $pro->description }}</p>
                                        </div>
                                    </div>
                                    <!-- product list item end -->
                                </div>
                                    
                                @endforeach
                                
                            </div>
                            <!-- product view mode wrapper start -->
                        </div>
                        <!-- start pagination area -->
                        <div class="paginatoin-area text-center mt-18">
                             <div class="clearfix">{{ $listPro->links() }}</div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </main>


@stop()
@section('js')
    
@endsection