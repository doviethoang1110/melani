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
                                <li class="breadcrumb-item active" aria-current="page">{{ $blog->title }}</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
</div>


@stop()
@section('main')
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
                                        <li><a href="#">{{ $item->name }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="blog-sidebar">
                                <h4 class="title">recent post</h4>
                                <div class="popular-item-inner popular-item-inner__style-2">
                                    @foreach ($listBlog as $item)
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
                    <div class="col-lg-9 order-1 order-lg-2">
                        <div class="blog-wrapper">
                            <div class="blog-item-single">
                                <article class="blog-post blog-details">
                                    <div class="blog-post-content">
                                        <div class="blog-top">
                                            <div class="post-date-time">
                                                <span>{{ $blog->created_at->format('d-m-y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="blog-content blog-details">
                                        <h4>{{ $blog->title }}</h4>
                                        <p>{!! $blog->description !!}</p>
                                    </div>
                                    <div class="blog-sharing text-center">
                                        <h4>share this post:</h4>
                                        <a href="#"><i class="fa fa-facebook"></i></a>
                                        <a href="#"><i class="fa fa-twitter"></i></a>
                                        <a href="#"><i class="fa fa-pinterest"></i></a>
                                        <a href="#"><i class="fa fa-google-plus"></i></a>
                                    </div>
                                </article>
                            </div>
                        </div>
                        <!-- comment area start -->
                        <div class="comment-section">
                            <h3>{{ count($listCom) }} comments</h3>
                            <ul>
                                <?php showComment($listCom);?>
                                <?php 
                                    function showComment($listCom ,$parentId = 0, $li_class = '')
                                        {
                                            $com_child = array();
                                            foreach ($listCom as $key => $item)
                                            {
                                                if ($item['parentId'] == $parentId)
                                                {
                                                    $com_child[] = $item;
                                                    unset($listCom[$key]);
                                                }
                                            }
                                            if ($com_child)
                                            {
                                                
                                                foreach ($com_child as $key => $itemc)
                                                {
                                                    echo '<li class="'.$li_class.'">';
                                                    echo '<div class="author-avatar">'.'<img src="'.url('uploads').'/'.$itemc->cus->avatar.'">'.'</div>';
                                                    echo '<div class="comment-body">'.
                                                        '<span class="reply-btn"><a href="#" id="'.$itemc['id'].'" class="rep">reply</a></span>'.
                                                        '<h5 class="comment-author">'.$itemc->cus->name.'</h5>'.'<div class="comment-post-date">'.$itemc['created_at']->format('d M,Y').' at '.$itemc['created_at']->format('h:i a').'</div>'.
                                                        '<p>'.$itemc['content'].'</p>'.
                                                        '</div>';
                                                        showComment($listCom, $itemc['id'],'comment-children');
                                                    echo '</li>';
                                                }
                                                
                                        }}
                                    ?>
                            </ul>
                        </div>
                        <!-- comment area end -->
                        <!-- start blog comment box -->
                        <div class="blog-comment-wrapper mb-sm-6">
                            <h3>leave a reply</h3>
                            <form action="#" method="POST" id="form_comment">
                                <div class="comment-post-box">
                                    <div class="row">
                                        <div class="col-12">
                                            <label>comment</label>
                                            <textarea name="commnet" id="comment" placeholder="Write a comment"></textarea>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 mb-sm-20">
                                            <label>Name</label>
                                            @if (Session::has('customer'))
                                            <input type="text" class="coment-field" placeholder="Name" name="name" value="{{ Session::get('customer')->name }}">
                                            @else
                                            <input type="text" class="coment-field" placeholder="Name" name="name">
                                            @endif
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 mb-sm-20">
                                            <label>Email</label>
                                            @if (Session::has('customer'))
                                                
                                            <input type="text" class="coment-field" placeholder="Email" name="email" value="{{ Session::get('customer')->email }}">
                                            @else
                                            <input type="text" class="coment-field" placeholder="Email" name="email">
                                            @endif
                                        </div>
                                        <div class="col-12">
                                            <div class="coment-btn mt-20">
                                                <input type="hidden" name="blogId" value="{{ $blog->id }}">
                                                <input type="hidden" name="action" value="Comment">
                                                <input class="sqr-btn" type="submit" name="submit" value="post comment">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <span id="comment_res"></span>
                        </div>
                        <!-- start blog comment box -->
                    </div>
                </div>
            </div>
        </div>
        <!-- blog main wrapper end -->
    </main>
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Leave a reply</h4>
            </div>
            <div class="modal-body">
                <span id="reply_res"></span>
                <form action="#" method="POST" id="form_reply">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Reply</label>
                                <textarea name="commnet" id="reply" placeholder="Write a comment" class="form-control"></textarea>
                                <input type="hidden" id="hidden_id" name="parentId">
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <label>Name</label>
                                @if (Session::has('customer'))
                                <input type="text" class="form-control name" placeholder="Name" value="{{ Session::get('customer')->name }}">
                                @else
                                <input type="text" class="form-control name" placeholder="Name">
                                @endif
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <label>Email</label>
                                @if (Session::has('customer'))
                                    
                                <input type="text" class="form-control email" placeholder="Email" value="{{ Session::get('customer')->email }}">
                                @else
                                <input type="text" class="form-control email" placeholder="Email">
                                @endif
                            </div>
                            <div class="col-12">
                                <div class="coment-btn mt-20">
                                    <input type="hidden" name="blogId" value="{{ $blog->id }}">
                                    <input type="hidden" class="action" value="Reply">
                                    <input class="sqr-btn" type="submit" name="submit" value="post comment">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
@stop()
@section('js')
    <script>
        $('#form_comment').on('submit',function (event){
            event.preventDefault();
            var data = new FormData();
            data.append('id',$('input[name="blogId"]').val());
            data.append('name',$('input[name="name"]').val());
            data.append('email',$('input[name="email"]').val());
            data.append('comment',$('#comment').val());
            data.append('action',$('input[name="action"]').val());
            data.append('_token',$('input[name="_token"]').val());
            $.ajax({
                url:"{{ route('blog.comment') }}",
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
                        alert(data.error);
                        $('#modal_login').modal('show');
                    }else if(data.success){
                        $('#form_comment')[0].reset();
                        alert(data.success);
                        setTimeout(function(){location.reload();}, 0001);  
                    }
                    $('#comment_res').html(html);
                }
            });
        });
        $(document).on('click','.rep',function (event){
            event.preventDefault();
            $('input[name="parentId"]').val($(this).attr('id'));
            $('#myModal').modal('show');
        });
        $('#form_reply').on('submit',function (event){
            event.preventDefault();
            var data = new FormData();
            data.append('id',$('input[name="blogId"]').val());
            data.append('name',$('.name').val());
            data.append('email',$('.email').val());
            data.append('comment',$('#reply').val());
            data.append('parentId',$('input[name="parentId"]').val());
            data.append('action',$('input[type="hidden"][class="action"]').val());
            data.append('_token',$('input[name="_token"]').val());
            $.ajax({
                url:"{{ route('blog.comment') }}",
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
                        alert(data.error);
                    }else if(data.success){
                        $('#form_comment')[0].reset();
                        alert(data.success);
                        setTimeout(function(){location.reload();}, 0001);  
                    }
                    $('#reply_res').html(html);
                }
            });
        });
    </script>    
@endsection