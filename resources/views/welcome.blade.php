@extends('user.master')
@section('head')

    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-wrap">
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">404</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>


@stop()
@section('main')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <img src="https://cdn.pixabay.com/photo/2016/04/24/13/24/error-1349562_960_720.png" alt="">
            </div>
        </div>
    </div>
@endsection
