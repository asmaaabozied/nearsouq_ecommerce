<!DOCTYPE html >
<html >
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> NearSouq </title>
    <meta name="description" content="Nearsouq is an e-commerce application that aims to enable customers to access local and international stores located in markets and malls">
    <meta name="author" content="Nawat AlRabt" />
    <link rel="icon" href="favicon.png">
    <!-- Font Icons -->
    <link rel="stylesheet" href="{{asset('website_files/css/fontawesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('website_files/css/themify-icons.css')}}">
    <link rel="stylesheet" href="{{asset('website_files/css/flaticon.css')}}">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{asset('website_files/css/bootstrap.min.css')}}">
    <!-- Animation -->
    <link rel="stylesheet" href="{{asset('website_files/css/animate.min.css')}}">
    <!-- Owl Carousel -->
    <link rel="stylesheet" href="{{asset('website_files/css/owl.carousel.min.css')}}">
    <!-- Light Case -->
    <link rel="stylesheet" href="{{asset('website_files/css/lightcase.min.css')}}" type="text/css">
    <!-- Template style -->
    @if(app()->getLocale() == 'ar')
    <link rel="stylesheet" href="{{asset('website_files/css/stylertl.css')}}">
    @else
    <link rel="stylesheet" href="{{asset('website_files/css/style.css')}}">
    @endif
    <!--[if lt IE 9]>
          <script src="js/html5shiv.min.js"></script>
          <script src="js/respond.min.js"></script>
        <![endif]-->
</head>
<body>
    <!-- preloader -->
    <div id="preloader">
        <div id="preloader-circle">
            <span></span>
            <span></span>
        </div>
    </div>
    <!-- /preloader -->

    <!--Start Header Area-->
    <header class="header-area" id="header-area">
        <nav class="navbar navbar-expand-md fixed-top">
            <div class="container">
                <div class="site-logo"><a class="navbar-brand" href="index.html"><img src="{{asset('frontend/assets/img/logo_white.png')}}" class="img-fluid" alt="Img" /></a></div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"><i class="ti-menu"></i></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav">
                        <!--<li class="nav-item dropdown">
                                <a class="dropdown-toggle" href="#" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">الرئيسية</a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="index.html">الصفحة الرئيسية - 01</a>
                                    <a class="dropdown-item" href="index-2.html">الصفحة الرئيسية - 02</a>
                                    <a class="dropdown-item" href="index-3.html">الصفحة الرئيسية - 03</a>
                                    <a class="dropdown-item" href="index-4.html">الصفحة الرئيسية - 04</a>
                                    <a class="dropdown-item" href="index-5.html">الصفحة الرئيسية - 05</a>
                                    <a class="dropdown-item" href="index-6.html">الصفحة الرئيسية - 06</a>
                                    <a class="dropdown-item" href="index-7.html">الصفحة الرئيسية - 07</a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="dropdown-toggle" href="#" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">الصفحات</a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                                    <a class="dropdown-item" href="about-us.html">من نحن</a>
                                    <a class="dropdown-item" href="contact-us.html">اتصل بنا</a>
                                    <a class="dropdown-item" href="faqs.html">الأسئلة الشائعة</a>
                                    <a class="dropdown-item" href="reviews.html">اراء العملاء</a>
                                    <a class="dropdown-item" href="signin.html">تسجيل دخول</a>
                                    <a class="dropdown-item" href="signup.html">تسجيل مستخدم</a>
                                    <a class="dropdown-item" href="recover-account.html">استعادة كلمة المرور</a>
                                    <a class="dropdown-item" href="coming-soon.html">الظهور قريبا</a>
                                    <a class="dropdown-item" href="error-404.html">صفحة الخطأ 404</a>
                                </div>
                            </li>-->

                        <li class="nav-item"><a href="#aboutus" data-scroll-nav="1">@lang('website.aboutus')</a></li>
                        <li class="nav-item"><a href="#termcondition" data-scroll-nav="2">@lang('website.termcondition')</a></li>
                        <li class="nav-item"><a href="#privacy" data-scroll-nav="3">@lang('website.privacy') </a></li>
                        <!--<li class="nav-item"><a href="#" data-scroll-nav="4">الأسعار</a></li>-->
                        <!--<li class="nav-item"><a href="#" data-scroll-nav="7">الأراء</a></li>-->
                        <li class="nav-item"><a href="#contact_us" data-scroll-nav="5">@lang('website.contact_us') </a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" id="menu-buttonsss" data-bs-toggle="dropdown" role="button">
                                <img src="{{asset('frontend/assets/img/flags/sa.png')}}" alt="" height="25px" style="height:25px !important"> <span>@lang('site.Arabic')</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" id="mob-menu">
                                @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                @if($properties['native']=='English')
                                <a class="dropdown-item" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                    <img src="{{asset('frontend/assets/img/flags/fr.png')}}" alt="" height="25px" style="height:25px !important">
                                    <span>{{ $properties['native'] }}</span>
                                </a>
                                @else
                                <a class="dropdown-item" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                    <img src="{{asset('frontend/assets/img/flags/sa.png')}}" alt="" height="16" style="height:25px !important">
                                    <span>{{ $properties['native'] }}</span>
                                </a>
                                @endif
                                @endforeach
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <!-- End Header Area-->
<div class="page-header">
            <div class="container">
                <div class="title-box">
                    <h1 class="title"> {{$pages[0]->name}} </h1>
                    <div class="breadcrumb">                        
                    </div>  
                </div>
            </div>   
            
        </div>
        @foreach($pages as $page)
        <div data-scroll-index="{{$page->id}}" id="{{$page->slug}}">
        <h4 style="text-align:center;padding:3% 3%;margin:3% 3%">{!!$page->description!!}</h4>
        </div>
        @endforeach
    <!-- Start Contact Section -->
    <section id="contact" class="section-block" data-scroll-index="8">
        <div class="bubbles-animate">
            <div class="bubble b_one"></div>
            <div class="bubble b_two"></div>
            <div class="bubble b_three"></div>
            <div class="bubble b_four"></div>
            <div class="bubble b_five"></div>
            <div class="bubble b_six"></div>
        </div>
        <div class="container">
            <div class="row">
                <!-- Start Contact Information -->
                <div class="col-md-5">
                    <div class="section-header-style2">
                        <h2> @lang('website.contact_header1') </h2>
                    </div>
                    <div class="contact-details">
                        <!-- Start Contact Block -->
                        <div class="contact-block">
                            <h4>@lang('website.our_office_address')</h4>
                            <div class="contact-block-side">
                                <i class="flaticon-route"></i>
                                <p>
                                    <span>@lang('website.street_name') </span>
                                    <span>@lang('website.country_city')</span>
                                </p>
                            </div>
                        </div>
                        <!-- End Contact Block -->

                        <!-- Start Contact Block -->
                        <div class="contact-block">
                            <h4>@lang('website.working_hours')</h4>
                            <div class="contact-block-side">
                                <i class="flaticon-stopwatch-4"></i>
                                <p>
                                    <span>@lang('website.working_days') </span>
                                    <span>@lang('website.working_hours_content')</span>
                                </p>
                            </div>
                        </div>
                        <!-- End Contact Block -->

                        <!-- Start Contact Block -->
                        <div class="contact-block">
                            <h4>@lang('website.phone')</h4>
                            <div class="contact-block-side">
                                <i class="flaticon-smartphone-7"></i>
                                <p>
                                    <span>551603645</span>
                                </p>
                            </div>
                        </div>
                        <!-- End Contact Block -->

                        <!-- Start Contact Block -->
                        <div class="contact-block">
                            <h4>@lang('website.email')</h4>
                            <div class="contact-block-side">
                                <i class="flaticon-paper-plane-1"></i>
                                <p>
                                    <span> support@nearsouq.com </span>
                                </p>
                            </div>
                        </div>
                        <!-- End Contact Block -->
                    </div>
                </div>
                <!-- End Contact Information -->

                <!-- Start Contact form Area -->
                <div class="col-md-7">
                    <div class="contact-shape">
                        <img src="{{asset('website_files/images/shapes/contact-form.png')}}" class="img-fluid" alt="Img" />
                    </div>
                    <div class="contact-form-block">
                        <div class="section-header-style2">
                            <h2>@lang('website.contact_header_2')</h2>
                        </div>
                        <form method="get" action="/sendMessage" class="contact-form" >
                        {{ csrf_field() }}
                            <input type="text" class="form-control" name="name" placeholder="@lang('website.name')" required />
                            <input type="email" class="form-control" name="email" placeholder="@lang('website.email')" required/>
                            <input type="tel" class="form-control" name="phone" placeholder="@lang('website.phone')" required/>
                            <textarea class="form-control" name="message" placeholder="@lang('website.message')" required></textarea>
                            <button class="btn theme-btn">@lang('website.send_message')</button>
                        </form>
                    </div>

                </div>
                <!-- End Contact form Area -->
            </div>
        </div>
    </section>
    <!-- End Contact Section -->

    <!-- Start Footer Area -->
    <footer style="padding-bottom:3%;">
        <div class="shape-top"></div>
        <div class="container">
            <!-- End Footer Top  Area -->
            <div class="top-footer">
                <div class="row">
                    <!-- Start Column 1 -->
                    <div class="col-md-4">
                        <div class="footer-logo">
                            <img src="{{asset('frontend/assets/img/logo.png')}}" class="img-fluid" alt="Img" />
                        </div>
                        
                        <div class="footer-social-links">
                            <a href="#"><i class="ti-facebook"></i></a>
                            <a href="#"><i class="ti-twitter-alt"></i></a>
                            <a href="#"><i class="ti-instagram"></i></a>
                            <a href="#"><i class="ti-pinterest"></i></a>
                        </div>
                    </div>
                    <!-- End Column 1 -->

                    <!-- Start Column 2 
                    <div class="col-md-2">
                        <h4 class="footer-title">روابط مهمه</h4>
                        <ul class="footer-links">
                            <li><a href="index.html">الرئيسية</a></li>
                            <li><a href="about-us.html">من نحن</a></li>
                            <li><a href="contact-us.html">اتصل بنا</a></li>
                            <li><a href="reviews.html">الأراء</a></li>
                            <li><a href="faqs.html">الأسئلة المتكررة</a></li>
                            <li><a href="blog-1.html">المقالات</a></li>
                        </ul>
                    </div>
                    <!-- End Column 2 -->

                    <!-- Start Column 3 -->
                    <div class="col-md-4">
                        <h4 class="footer-title">@lang('website.user_intersts')</h4>
                        <ul class="footer-links">
                           <!-- <li><a href="{{url('terms')}}">@lang('website.termsconditions')</a></li>-->
                            <li><a href="{{url('terms')}}">@lang('website.privacy_policy')</a></li>
                            <li><a href="{{url('terms')}}">@lang('website.return_policy')</a></li>
                        </ul>
                    </div>
                    <!-- End Column 3 -->

                    <!-- Start Column 4 -->
                    <div class="col-md-4">
                        <h4 class="footer-title"> @lang('website.termsconditions') </h4>
                        <p>
                            @lang('website.termsconditionscontent')
                        </p>
                        <!--<form class="newsletter-form">
                            <input type="email" placeholder="Enter Your Email" />
                            <button class="btn theme-btn">اشترك</button>
                        </form>-->
                    </div>
                    <!-- End Column 4 -->
                    
                </div>
            </div>
            <!-- End Footer Top  Area -->
        </div>
    </footer>
    <!-- End Footer Area -->

    <!-- Start To Top Button -->
    <div id="back-to-top">
        <a class="top" id="top" href="#header-area"> <i class="ti-angle-up"></i> </a>
    </div>
    <!-- End To Top Button -->

    <!-- Start JS FILES -->
    <!-- JQuery -->
    <script src="{{asset('website_files/js/jquery.min.js')}}"></script>
    <script src="{{asset('website_files/js/popper.min.js')}}"></script>
    <!-- Bootstrap -->
    <script src="{{asset('website_files/js/bootstrap.min.js')}}"></script>
    <!-- Wow Animation -->
    <script src="{{asset('website_files/js/wow.min.js')}}"></script>
    <!-- Owl Coursel -->
    <script src="{{asset('website_files/js/owl.carousel.min.js')}}"></script>
    <!-- Images LightCase -->
    <script src="{{asset('website_files/js/lightcase.min.js')}}"></script>
    <!-- scrollIt -->
    <script src="{{asset('website_files/js/scrollIt.min.js')}}"></script>
    <!-- Main Script -->
    <script src="{{asset('website_files/js/script.js')}}"></script>
</body>
</html>
