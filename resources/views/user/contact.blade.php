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
                                <li class="breadcrumb-item active" aria-current="page">Contact</li>
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
    <!-- contact wrapper area start -->
    <div class="contact-top-area pt-100 pb-98 pt-sm-58 pb-sm-58">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title text-center pb-44">
                        <p>contact us</p>
                        <h2>connect with us</h2>
                    </div>
                </div>
            </div>
            <!-- section title end -->
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="contact-single-info mb-30 text-center">
                        <div class="contact-icon">
                            <i class="fa fa-map-marker"></i>
                        </div>
                        <h3>address street</h3>
                        <p>Address : No 40 Baria Sreet
                            <br>NewYork City, United States.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="contact-single-info mb-30 text-center">
                        <div class="contact-icon">
                            <i class="fa fa-phone"></i>
                        </div>
                        <h3>number phone</h3>
                        <p>Phone 1: 0(1234) 567 89012
                            <br>Phone 2: 0(987) 567 890</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="contact-single-info mb-30 text-center">
                        <div class="contact-icon">
                            <i class="fa fa-fax"></i>
                        </div>
                        <h3>number fax</h3>
                        <p>Fax 1: 0(1234) 567 89012
                            <br>Fax 2: 0(987) 567 890</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="contact-single-info mb-30 text-center">
                        <div class="contact-icon">
                            <i class="fa fa-envelope"></i>
                        </div>
                        <h3>address email</h3>
                        <p>info@demo.com
                            <br>yourname@domain.com</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-10 col-lg-12 m-auto">
                    <div class="contact-message pt-60 pt-sm-20">
                        <span id="message"></span>
                        <form action="" method="POST" class="contact-form">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <input name="name" placeholder="Name *" type="text">
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <input name="email" placeholder="Email *" type="text">
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <input name="subject" placeholder="Subject *" type="text">
                                </div>
                                <div class="col-12">
                                    <div class="contact2-textarea text-center">
                                        <textarea placeholder="Message *" name="message" class="form-control2"></textarea>
                                    </div>
                                    <div class="contact-btn text-center">
                                        <button class="check-btn sqr-btn" type="submit">Send Message</button>
                                    </div>
                                </div>
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- contact wrapper area end -->
</main>

@stop()
@section('js')
    <script>
        $('.contact-form').on('submit',function(event){
            event.preventDefault();
            var data = new FormData(this);
            data.append('_token',$('input[name="_token"]').val());
            $.ajax({
                url:"{{ route('send.email') }}",
                method:"POST",
                data:data,
                contentType:false,
                cache:false,
                processData:false,
                dataType:"json",
                success:function(data){
                    if(data.errors){
                        var html = '';
                        html += '<div class="alert alert-danger">';
                        for (var i = 0; i < data.errors.length; i++) {
                            html += '<p>'+data.errors[i]+'</p>';
                        }
                        html += '</div>';
                        $('#message').html(html);
                    }if(data.success){
                        $('.contact-form')[0].reset();
                        swal({
                            title: data.success,
                            icon: "success",
                            buttons: "Done",
                            dangerMode: false,
                            })
                            $('#message').html('');
                    }
                }
            });
        });
    </script>
@endsection