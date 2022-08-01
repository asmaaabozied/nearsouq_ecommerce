<!DOCTYPE html>

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

  <ul class="nav nav-tabs user-menu">
            <!-- Flag -->
            <li class="nav-item dropdown has-arrow main-drop">

                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button">
                    <img src="{{asset('frontend/assets/img/flags/sa.png')}}" alt="" height="20"> <span>@lang('site.Arabic')</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">


                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)

                  @if($properties['native']=='English')

                        <a class="dropdown-item" hreflang="{{ $localeCode }}"
                           href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                            
<img src="{{asset('frontend/assets/img/flags/fr.png')}}" alt="" height="16">




                            <!--<img src="{{asset('frontend/assets/img/flags/sa.png')}}" alt="" height="16">-->



                            <span>{{ $properties['native'] }}</span>
                        </a>
                        @else
                        
                        
                                        <a class="dropdown-item" hreflang="{{ $localeCode }}"
                           href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                            




                            <img src="{{asset('frontend/assets/img/flags/sa.png')}}" alt="" height="16">



                            <span>{{ $properties['native'] }}</span>
                        </a>
                        
                        
                        @endif

                    @endforeach

                </div>
            </li>
            <!-- /Flag -->

            <!-- /User Menu -->

        </ul>
        
<div class="main-wrapper login-body">
    <div class="login-wrapper">
        
        <div class="container">

            <img class="img-fluid logo-dark mb-2" src="{{asset('frontend/assets/img/logo.png')}}" alt="Logo">
            <div class="loginbox">

                <div class="login-right">
                    <div class="login-right-wrap">
                        <h1>Login</h1>
                        <p class="account-subtitle">Access to our dashboard</p>
@include('flash::message')
                        <form action="{{ route('login') }}" method="post" class="login-form">
                            {{ csrf_field() }}
                            {{ method_field('post') }}

                            @include('partials._errors')
                            <div class="form-group">
                                <label class="form-control-label">@lang('site.email')</label>
                                <input type="email" class="form-control" name="email">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">@lang('site.password')</label>
                                <div class="pass-group">
                                    <input type="password" class="form-control pass-input" name="password">
                                    <span class="fas fa-eye toggle-password"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="cb1">
                                            <label class="custom-control-label" for="cb1">Remember me</label>
                                        </div>
                                    </div>
                                    <div class="col-6 text-end">
                                        {{--                                        <a class="forgot-link" href="forgot-password.html">Forgot Password ?</a>--}}
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-lg btn-block btn-primary w-100" type="submit">@lang('site.login')</button>
                            <br>
                              </form>
                            <div class="login-or">
                                <span class="or-line">@lang('site.haveshop')
                                <!--<a href="{{route('dashboard.register')}}">@lang('site.register')</a>-->
                                
                                </span>
                                <br>
                                <br>
                                 <button class="btn btn-lg btn-block btn-primary w-100"  onclick="window.location='{{route('dashboard.register')}}';">@lang('site.register')</button>

                            </div>

                      

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>

    
        @if(session()->has('success'))
    swal({
        title: "success",
        text: '{{ session()->get('success') }}',
        icon: "info",
        showCancelButton: true,
        closeOnConfirm: true,
    });
    @endif
</script>

<div class="sidebar-overlay"></div>


</body>
</html>
