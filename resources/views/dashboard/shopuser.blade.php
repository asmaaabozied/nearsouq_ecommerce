<!DOCTYPE html>
<style>
    .image{
        display:inline;
        vertical-align:middle;

        height:50px;
        width:50px;
    }
    input[type="radio"] {
        display: inline;
        margin-bottom: 0px; }
</style>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>@lang('site.nearsouq')</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset('frontend/assets/img/favicon.png')}}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('frontend/assets/css/bootstrap.min.css')}}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{asset('frontend/assets/plugins/fontawesome/css/fontawesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/assets/plugins/fontawesome/css/all.min.css')}}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{asset('frontend/assets/css/style.css')}}">

<!--[if lt IE 9]>
    <script src="{{asset('frontend/assets/js/html5shiv.min.js')}}"></script>
    <script src="{{asset('frontend/assets/js/respond.min.js')}}"></script>
    <![endif]-->
</head>
<body>

<!-- Main Wrapper -->
<div class="main-wrapper login-body">
    <div class="login-wrapper">
        <div class="container">

            <img class="img-fluid logo-dark mb-2" src="{{asset('frontend/assets/img/logo.png')}}" alt="Logo" onclick="window.location.href='/dashboard';">
            <div class="loginbox">

                <div class="login-right">
                    <div class="login-right-wrap">
                        <h1>@lang('site.chooseshop')</h1>
                        <p class="account-subtitle">@lang('site.Access to our dashboard')</p>

                        <form action="{{route('dashboard.saveshopuser')}}"  method="post">
                            {{ csrf_field() }}
                            {{ method_field('post') }}


                            @include('partials._errors')

                            @foreach(auth()->user()->shops as $shop)
                                <div class="row">
                                    <div class="col-sm-2"><img src="{{asset('uploads/shops/profiles/'.$shop->commerical_img) }}" class="image"></div>

                                    <div class="col-sm-10">
                                        <div class="form-group">
                                            <label class="form-control-label">
                                                <input type="radio"  value="{{ $shop->id }}"  name="shop_id" required> {{ $shop->name }}
                                                <br>
                                                {{ $shop->address}}
                                            </label>
                                        </div>
                                    </div>
                                </div>



                            @endforeach


                            <button class="btn btn-lg btn-block btn-primary w-100" type="submit"> @lang('site.next') </button>
                            <div class="login-or">
                                <span class="or-line"></span>

                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Main Wrapper -->

<!-- jQuery -->
<script src="{{asset('frontend/assets/js/jquery-3.6.0.min.js')}}"></script>

<!-- Bootstrap Core JS -->
<script src="{{asset('frontend/assets/js/popper.min.js')}}"></script>
<script src="{{asset('frontend/assets/js/bootstrap.min.js')}}"></script>

<!-- Feather Icon JS -->
<script src="{{asset('frontend/assets/js/feather.min.js')}}"></script>

<!-- Custom JS -->
<script src="{{asset('frontend/assets/js/script.js')}}"></script>
<div class="sidebar-overlay"></div>


</body>
</html>
