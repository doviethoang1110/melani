
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
                                <li class="breadcrumb-item active" aria-current="page">{{ $pro->name }}</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
</div>


@stop()

@section('main')
<?php 
$image_list = json_decode($pro->image_list);
?>
    <main>
        <div class="product-details-wrapper pt-100 pb-14 pt-sm-58">
            <div class="container">
                <div class="row">
                    <div class="col-lg-9">
                        <!-- product details inner end -->
                        <div class="product-details-inner">
                            <div class="row">
                                <div class="col-lg-5">
                                    <div class="product-large-slider mb-20 slider-arrow-style slider-arrow-style__style-2">
                                        
                                        @if (is_array($image_list))
                                            @foreach ($image_list as $item)
                                            <div class="pro-large-img img-zoom" id="img1">
                                                <img src="{{ $item }}" alt="" />
                                            </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="pro-nav slick-padding2 slider-arrow-style slider-arrow-style__style-2">
                                        
                                        @if (is_array($image_list))
                                            @foreach ($image_list as $item)
                                            <div class="pro-nav-thumb"><img src="{{ $item }}" alt="" /></div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="product-details-des pt-md-98 pt-sm-58">
                                        <h3>{{ $pro->name }}</h3>
                                        <div class="ratings">
                                            @for ($i = 0; $i < $total; $i++)
                                            <span class="good"><i class="fa fa-star"></i></span>
                                                
                                            @endfor
                                            <div class="pro-review">
                                                <span><a href="javascript:void(0)">{{ count($listReview) }} Reviews</a></span>
                                            </div>
                                        </div>
                                        <div class="pricebox">
                                            @if ($pro->discount>0)
                                            <span class="regular-price">{{ number_format(($pro->exportPrice)-($pro->exportPrice*$pro->discount)/100) }} VNĐ</span>
                                            <span class="old-price"><del>{{ number_format($pro->exportPrice) }} VNĐ</del></span>
                                            @else
                                            <span class="regular-price">{{ number_format($pro->exportPrice) }} VNĐ</span>
                                            @endif
                                        </div>
                                        <div class="hover-box text-center">
                                        </div>
                                        <p>{{ $pro->description }}</p>
                                        <div class="quantity-cart-box d-flex align-items-center mb-24">
                                            <div class="quantity">
                                                <div class="pro-qty"><input type="number" value="1" id="quantity" min="1" max="5"></div>
                                            </div>
                                            
                                            <input type="hidden" id="cart_id" value="{{ $pro->id }}">
                                            <div class="product-btn product-btn__color">
                                                <a href="javascript:void(0)" id="add_cart"><i class="ion-bag"></i>Add to cart</a>
                                            </div>
                                        </div>
                                        <span style="color:red" id="error1"></span>
                                        @if(!empty($listColor))
                                            @if(count($listColor)>0)
                                            <div class="pro-size mb-24">
                                                    <h5>Color :</h5>
                                                    <select class="nice-select" id="colorId">
                                                        @foreach ($listColor as $color)
                                                        <option value="{{ $color->id }}">{{ $color->name }}</option>
                                                            
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif
                                        @endif
                                        @if(count($listSize)>0)
                                            <div class="pro-size mb-24">
                                                    <h5>size :</h5>
                                                    <select class="nice-select" id="sizeId">
                                                        @foreach ($listSize as $size)
                                                        <option value="{{ $size->id }}">{{ $size->name }}</option>
                                                            
                                                        @endforeach
                                                    </select>
                                                </div>
                                        @endif
                                        <div class="availability mb-20">
                                            <h5>Trạng thái :</h5>
                                            <span id="in_stock"></span>
                                        </div>
                                        <div class="share-icon">
                                            <h5>share:</h5>
                                            <a href="#"><i class="fa fa-facebook"></i></a>
                                            <a href="#"><i class="fa fa-twitter"></i></a>
                                            <a href="#"><i class="fa fa-pinterest"></i></a>
                                            <a href="#"><i class="fa fa-google-plus"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- product details inner end -->
                        <!-- product details reviews start -->
                        <div class="product-details-reviews pt-98 pt-sm-58">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="product-review-info">
                                        <ul class="nav review-tab">
                                            <li>
                                                <a class="active" data-toggle="tab" href="#tab_one">description</a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#tab_two">information</a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#tab_three">reviews</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content reviews-tab">
                                            <div class="tab-pane fade show active" id="tab_one">
                                                <div class="tab-one">
                                                    <p>{!! $pro->description !!}</p>
                                                    
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="tab_two">
                                                <table class="table table-bordered">
                                                    <tbody>
                                                        <tr>
                                                            <td>color</td>
                                                            <td>@if(count($listColor)>1)
                                                                    @foreach ($listColor as $color)
                                                                    {{ $color->name }},
                                                                @endforeach
                                                            @endif</td>
                                                        </tr>
                                                        <tr>
                                                            <td>size</td>
                                                            <td>@if(count($listSize)>1)
                                                            @foreach ($listSize as $size)
                                                                {{ $size->name }},
                                                            @endforeach
                                                            @endif</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="tab-pane fade" id="tab_three">
                                                <h5>{{ count($listReview) }} review for <span>{{ $pro->name }}</span></h5>
                                                @if (count($listReview)>0)
                                                    @foreach ($listReview as $rev)
                                                    <div class="total-reviews">
                                                        <div class="rev-avatar">
                                                            <img src="{{ url('/uploads') }}/{{ $rev->cus->avatar }}" alt="">
                                                        </div>
                                                        <div class="review-box">
                                                            <div class="ratings">
                                                                @for ($i = 0; $i < $rev->rating; $i++)
                                                                    <span class="good"><i class="fa fa-star"></i></span>
                                                                @endfor
                                                            </div>
                                                            <div class="post-author">
                                                                <p><span>{{ $rev->cus->name }} -</span> {{ $rev->created_at->format('d-m-y') }}</p>
                                                            </div>
                                                            <p>{{ $rev->content }}</p>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                @endif
                                                <form action="" class="review-form" method="POST" id="form_review">
                                                    @csrf
                                                    <div class="form-group row">
                                                        <div class="col">
                                                            <label class="col-form-label"><span class="text-danger">*</span> Your Name</label>
                                                            @if (Session::has('customer'))
                                                                <input type="text" class="form-control" name="name" value="{{ Session::get('customer')->name }}">
                                                            @else
                                                                <input type="text" class="form-control" name="name">  
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col">
                                                            <label class="col-form-label"><span class="text-danger">*</span> Your Email</label>
                                                            @if (Session::has('customer'))
                                                            <input type="email" class="form-control" name="email" value="{{ Session::get('customer')->email }}">
                                                            @else
                                                            <input type="email" class="form-control" name="email">
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col">
                                                            <label class="col-form-label"><span class="text-danger">*</span> Your Review</label>
                                                            <textarea class="form-control" name="content" id="content"></textarea>
                                                            <div class="help-block pt-10"><span class="text-danger">Note:</span> HTML is not translated!</div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col">
                                                            <label class="col-form-label"><span class="text-danger">*</span> Rating</label>
                                                            &nbsp;&nbsp;&nbsp; Bad&nbsp;
                                                            <input type="radio" value="1" name="rating">
                                                            &nbsp;
                                                            <input type="radio" value="2" name="rating">
                                                            &nbsp;
                                                            <input type="radio" value="3" name="rating">
                                                            &nbsp;
                                                            <input type="radio" value="4" name="rating">
                                                            &nbsp;
                                                            <input type="radio" value="5" name="rating" checked>
                                                            &nbsp;Good
                                                        </div>
                                                    </div>
                                                    <div class="buttons">
                                                        <input type="hidden" name="productId" value="{{ $pro->id }}">
                                                        <button class="sqr-btn" type="submit" id="btn_review" value="Review">Bình luận</button>
                                                        
                                                    </div>
                                                </form> <!-- end of review-form -->
                                                <span id="review_res"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <!-- product details reviews end --> 
                        <!-- featured product area start -->
                        <div class="page-section pt-100 pt-sm-58">
                            <div class="section-title text-center pb-44">
                                <p>The latest products</p>
                                <h2>related products</h2>
                            </div>
                            <div class="releted-product spt slick-padding slick-arrow-style">
                                @foreach ($listProCat as $pro)
                                    
                                <div class="product-item mb-20">
                                    <div class="product-thumb">
                                        <a href="{{ route('shop_detail',['slug'=>$pro->slug]) }}">
                                            <img src="{{ url('/uploads') }}/{{ $pro->image }}" alt="product image">
                                        </a>
                                        <div class="box-label">
                                            @if ($pro->priority==1)
                                                <div class="product-label new">
                                                    <span>
                                                        hot
                                                    </span>
                                                </div>
                                            @else
                                                    
                                            @endif
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
                                            <span class="regular-price">{{ number_format(($pro->exportPrice)-($pro->exportPrice*$pro->discount)/100) }} VNĐ</span>
                                            <span class="old-price"><del>{{ number_format($pro->exportPrice) }} VNĐ</del></span>
                                        </div>
                                        <div class="hover-box text-center">
                                            <div class="ratings">
                                                @for ($i = 0; $i < $total; $i++)
                                            <span class="good"><i class="fa fa-star"></i></span>
                                                
                                            @endfor
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                
                            </div>
                        </div>
                        <!-- featured product area end -->
                    </div>
                    <div class="col-lg-3">
                        <div class="sidebar-wrapper pt-md-16 pb-md-86 pb-sm-44">
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
                                    <h3>featured</h3>
                                </div>
                                <div class="sidebar-body">
                                    <div class="popular-item-inner popular-item-inner__style-2">
                                        @foreach ($proPopular as $pro)
                                            
                                        <div class="popular-item">
                                            <div class="pop-item-thumb">
                                                <a href="{{ route('shop_detail',['slug'=>$pro->slug]) }}">
                                                    <img src="{{ url('/uploads') }}/{{ $pro->image }}" alt="">
                                                </a>
                                            </div>
                                            <div class="pop-item-des">
                                                <h4><a href="{{ route('shop_detail',['slug'=>$pro->slug]) }}">{{ $pro->name }}</a></h4>
                                                <span class="regular-price">{{ number_format(($pro->exportPrice)-($pro->exportPrice*$pro->discount)/100) }} VNĐ</span>
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
                                    <a href="{{$banner4->links}}">
                                        <img src="{{ url('/uploads') }}/{{ $banner4->image }}" alt="">
                                    </a>
                                </div>
                            </div>
                            <!-- single sidebar end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@stop()
@section('js')
    <script>
        $('#form_review').on('submit',function (event){
            event.preventDefault();
            var data = new FormData();
            data.append('id',$('input[name="productId"]').val());
            data.append('name',$('input[name="name"]').val());
            data.append('email',$('input[name="email"]').val());
            data.append('content',$('#content').val());
            data.append('rating',$('input[name="rating"]:checked').val());
            data.append('_token',$('input[name="_token"]').val());
            $.ajax({
                url:"{{ route('product.review') }}",
                method:"POST",
                data:data,
                contentType:false,
                cache:false,
                processData:false,
                dataType:"json",
                success:function(data){
                    var html = '';
                    if(data.errors){
                        for (var i = 0; i < data.errors.length; i++) {
                            html += '<div class="alert alert-danger">';
                            html += '<p>'+data.errors[i]+'</p>';
                            html += '</div>';
                        }
                    }else if(data.error){
                        html += '<div class="alert alert-danger">'+data.error+'</div>';
                    }else if(data.success){
                        $('#form_review')[0].reset();
                        alert(data.success);
                    }
                    $('#review_res').html(html);
                }
            });
        });

        $('#add_cart').click(function (event){
            event.preventDefault();
            var data = new FormData();
            data.append('productId',$('#cart_id').val());
            data.append('sizeId',$('#sizeId').val());
            data.append('colorId',$('#colorId').val() ? $('#colorId').val() : '');
            data.append('quantity',$('#quantity').val());
            data.append('_token',$('input[name="_token"]').val());
            $.ajax({
                url:"{{ route('add.cart') }}",
                method:"POST",
                data:data,
                contentType:false,
                cache:false,
                processData:false,
                dataType:"json",
                success:function(data){
                    if(data.success){
                        toastr["success"](data.success)
                    }if(data.error){
                        alert(data.error);
                    }if(data.error1){
                        $('#error1').text(data.error1);
                    }
                }
            });
        });
        $('.nice-select').change(function(event){
            event.preventDefault();
            var data = new FormData();
            data.append('_token',$('input[name="_token"]').val());
            data.append('sizeId',$('#sizeId :selected').val());
            data.append('productId',$('#cart_id').val());
            if($('#colorId :selected').val()==null){
                data.append('colorId','');
            }else{
                data.append('colorId',$('#colorId :selected').val());
            }
            $.ajax({
                url:"{{ route('stock.fetch') }}",
                method:"POST",
                data:data,
                contentType:false,
                cache:false,
                processData:false,
                dataType:"json",
                success:function(html){
                    if(html.data){
                        $('.regular-price').text('Sản phẩm hết hàng');
                        $('#in_stock').text('Còn : '+html.data.importNum+' sản phẩm');
                        if(html.data.discount>0){
                            $('.regular-price').text(html.data.price+' VNĐ');
                            $('.old-price').html('<del>'+html.data.exportPrice+' VNĐ'+'</del>')
                        }else{
                            $('.regular-price').text(html.data.exportPrice+' VNĐ');
                        }
                    }if(html.out){
                        $('.old-price').html('');
                        $('.regular-price').text('Sản phẩm hết hàng');
                        $('#in_stock').text(html.out);
                    }
                }
            });
        });
        $(document).ready(function () { 
            var data = new FormData();
            data.append('_token',$('input[name="_token"]').val());
            data.append('sizeId',$('#sizeId :selected').val());
            data.append('productId',$('#cart_id').val());
            if($('#colorId :selected').val()==null){
                data.append('colorId','');
            }else{
                data.append('colorId',$('#colorId :selected').val());
            }
            $.ajax({
                url:"{{ route('stock.fetch') }}",
                method:"POST",
                data:data,
                contentType:false,
                cache:false,
                processData:false,
                dataType:"json",
                success:function(html){
                    if(html.data){
                        $('#in_stock').text('Còn : '+html.data.importNum+' sản phẩm');
                    }if(html.out){
                        $('#in_stock').text(html.out);
                    }
                }
            });
        });
    </script>
@endsection