<!DOCTYPE html >
<html >
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> NearSouq </title>
    <meta name="description" content="Nearsouq is an e-commerce application that aims to enable customers to access local and international stores located in markets and malls ">
    <meta name="author" content="Nawat AlRabt />
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

                        <li class="nav-item"><a href="#" data-scroll-nav="1">@lang('website.features')</a></li>
                        <li class="nav-item"><a href="#" data-scroll-nav="2">@lang('website.how_it_work')</a></li>
                        <li class="nav-item"><a href="#" data-scroll-nav="3">@lang('website.screenshots') </a></li>
                        <!--<li class="nav-item"><a href="#" data-scroll-nav="4">الأسعار</a></li>-->
                        <!--<li class="nav-item"><a href="#" data-scroll-nav="7">الأراء</a></li>-->
                        <li class="nav-item"><a href="#" data-scroll-nav="6">@lang('website.faqs') </a></li>
                        <li class="nav-item"><a href="#" data-scroll-nav="8"> @lang('website.contact')</a></li>
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

    <!-- Start Home Area -->
    <section class="start_home demo1">
        <div class="banner_top">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 start-home-content">
                        <h1>
                            @lang('website.top_title')
                        </h1>
                        <p> @lang('website.top_des') </p>
                        <div class="app-button">
                            <div class="apple-button">
                                <a href="https://apps.apple.com/us/app/nearsouq/id1575508321?platform=iphone">
                                    <div class="slider-button-icon">
                                        <i class="fab fa-apple" aria-hidden="true"></i>
                                    </div>
                                    <div class="slider-button-title">
                                        <p>from App store</p>
                                        <h3>app store</h3>
                                    </div>
                                </a>
                            </div>
                            <div class="google-button">
                                <a href="https://play.google.com/store/apps/details?id=com.nearsouq.customer">
                                    <div class="slider-button-icon">
                                        <i class="fab fa-google-play" aria-hidden="true"></i>
                                    </div>
                                    <div class="slider-button-title">
                                        <p>From Play Store</p>
                                        <h3>Google Play</h3>
                                    </div>
                                </a>
                            </div>
                            <div class="google-button">
                                <a href=" https://appgallery.huawei.com/app/C104824451">
                                    <div class="slider-button-icon">
                                        <img src="{{asset('website_files/images/icons/Huawei.png')}}" height="25px" width="25px" arira-hidden="true">
                                    </div>
                                    <div class="slider-button-title">
                                        <p>From Huawei Store</p>
                                        <h3>App Gallery</h3>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 start-home-img">
                        <img class="img-fluid" src="{{asset('website_files/images/1.png')}}" alt="Img" />
                    </div>
                </div>
            </div>
        </div>
        <div class="shape-bottom">
            <img src="{{asset('website_files/images/shapes/shape-1.svg')}}" alt="shape" class="bottom-shape img-fluid">
        </div>
    </section>
    <!-- End Home Area -->

    <!-- Start Features Section -->
    <section id="AppFeatures" class="section-block features-style1" data-scroll-index="1">
        <div class="circls-features active">
            <div class="circle-1"></div>
            <div class="circle-2"></div>
            <div class="circle-3"></div>
            <div class="circle-4"></div>
            <div class="circle-x"></div>
        </div>
        <div class="container">
            <div class="section-header">
                <h2>@lang('website.core_features')</h2>
                <p>
                    @lang('website.core_features_des')
                </p>
            </div>
            <div class="row">
                <!-- Start Features Item -->
                <div class="col-md-4">
                    <div class="feature-block">
                        <span class="feature-icon icon-1">
                            <i class="ti-wand"></i>
                        </span>
                        <p>@lang('website.core_features_1')</p>
                    </div>
                </div>
                <!-- End Features Item -->

                <!-- Start Features Item -->
                <div class="col-md-4">
                    <div class="feature-block">
                        <span class="feature-icon icon-2">
                            <i class="ti-gift"></i>
                        </span>
                        <p>@lang('website.core_features_2')</p>
                    </div>
                </div>
                <!-- End Features Item -->

                <!-- Start Features Item -->
                <div class="col-md-4">
                    <div class="feature-block">
                        <span class="feature-icon icon-3">
                            <i class="ti-announcement"></i>
                        </span>
                        <p> @lang('website.core_features_3')</p>
                    </div>
                </div>
                <!-- End Features Item -->

                <!-- Start Features Item -->
                <div class="col-md-4">
                    <div class="feature-block">
                        <span class="feature-icon icon-4">
                            <i class="ti-headphone-alt"></i>
                        </span>
                        <p>@lang('website.core_features_4')</p>
                    </div>
                </div>
                <!-- End Features Item -->

                <!-- Start Features Item -->
                <div class="col-md-4">
                    <div class="feature-block">
                        <span class="feature-icon icon-5">
                            <i class="ti-bar-chart"></i>
                        </span>
                        <p>@lang('website.core_features_5')</p>
                    </div>
                </div>
                <!-- End Features Item -->

                <!-- Start Features Item -->
                <div class="col-md-4">
                    <div class="feature-block">
                        <span class="feature-icon icon-6">
                            <i class="ti-pencil-alt"></i>
                        </span>
                        <p>@lang('website.core_features_6')</p>
                    </div>
                </div>
                <!-- End Features Item -->
            </div>
        </div>
    </section>
    <!-- End Features Section -->

    <!-- Start How it works Section -->
    <section id="how-it-work" class="section-block" data-scroll-index="2">
        <div class="container">
            <div class="section-header">
                <h2>@lang('website.how_do_i_shop_in_nearsouq?')</h2>
                <p>
                    @lang('website.two_ways')
                </p>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="img-box">
                        <img src="{{asset('website_files/images/3.png')}}" alt="Img" />
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Start Block 1 -->
                    <div class="description-block">
                        <div class="inner-box">
                            <div class="step_num"><img src="{{asset('website_files/images/step1.png')}}" alt="Img" /></div>
                            <h3>@lang('website.first_method')</h3>
                            <p> @lang('website.first_method_content')</p>
                        </div>
                    </div>
                    <!-- End Block 1 -->

                    <!-- Start Block 2 -->
                    <div class="description-block">
                        <div class="inner-box">
                            <div class="step_num"><img src="{{asset('website_files/images/step2.png')}}" alt="Img" /></div>
                            <h3>@lang('website.second_method')</h3>
                            <p> @lang('website.second_method_content')</p>
                        </div>
                    </div>
                    <!-- End Block 2 -->

                </div>
            </div>
        </div>
    </section>
    <!-- End How it works Section -->

    <!-- Start App Screenshots Section -->
    <section id="appScreenshots" class="section-block" data-scroll-index="3">
        <div class="shape-top"></div>
        <div class="container">
            <div class="section-header">
                <h2>@lang('website.application_images')</h2>
            </div>

            <div class="list_screen_slide owl-carousel">
                <!-- Start screen item-->
                <div class="item">
                    <a href="{{asset('website_files/images/screen/1.jpeg')}}" data-rel="lightcase:gal">
                        <img src="{{asset('website_files/images/screen/1.jpeg')}}" alt="Img">
                    </a>
                </div>
                <!-- End screen item-->

                <!-- Start screen item-->
                <div class="item">
                    <a href="{{asset('website_files/images/screen/2.jpeg')}}" data-rel="lightcase:gal">
                        <img src="{{asset('website_files/images/screen/2.jpeg')}}" alt="Img">
                    </a>
                </div>
                <!-- End screen item-->

                <!-- Start screen item-->
                <div class="item">
                    <a href="{{asset('website_files/images/screen/3.jpeg')}}" data-rel="lightcase:gal">
                        <img src="{{asset('website_files/images/screen/3.jpeg')}}" alt="Img">
                    </a>
                </div>
                <!-- End screen item-->

                <!-- Start screen item-->
                <div class="item">
                    <a href="{{asset('website_files/images/screen/1.jpeg')}}" data-rel="lightcase:gal">
                        <img src="{{asset('website_files/images/screen/4.jpeg')}}" alt="Img">
                    </a>
                </div>
                <!-- End screen item-->

                <!-- Start screen item-->
                <div class="item">
                    <a href="{{asset('website_files/images/screen/5.jpeg')}}" data-rel="lightcase:gal">
                        <img src="{{asset('website_files/images/screen/5.jpeg')}}" alt="Img">
                    </a>
                </div>
                <!-- End screen item-->

                <!-- Start screen item-->
                <div class="item">
                    <a href="{{asset('website_files/images/screen/6.jpeg')}}" data-rel="lightcase:gal">
                        <img src="{{asset('website_files/images/screen/6.jpeg')}}" alt="Img">
                    </a>
                </div>
                <!-- End screen item-->

                <!-- Start screen item-->
                <div class="item">
                    <a href="{{asset('website_files/images/screen/7.jpeg')}}" data-rel="lightcase:gal">
                        <img src="{{asset('website_files/images/screen/7.jpeg')}}" alt="Img">
                    </a>
                </div>
                <!-- End screen item-->

                <!-- Start screen item-->
                <div class="item">
                    <a href="{{asset('website_files/images/screen/4.jpeg')}}" data-rel="lightcase:gal">
                        <img src="{{asset('website_files/images/screen/4.jpeg')}}" alt="Img">
                    </a>
                </div>
                <!-- End screen item-->

                <!-- Start screen item-->
                <div class="item">
                    <a href="{{asset('website_files/images/screen/2.jpeg')}}" data-rel="lightcase:gal">
                        <img src="{{asset('website_files/images/screen/2.jpeg')}}" alt="Img">
                    </a>
                </div>
                <!-- End screen item-->
            </div>
        </div>
    </section>
    <!-- End App Screenshots Section -->

    <!-- Start Pricing Section 
    <section id="pricing" class="section-block" data-scroll-index="4">
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <div class="section-header-style2">
                        <h2>لا تكاليف إضافية.<br>ادفع مقابل ما تستخدمه.</h2>
                        <p>اختر الخدمة الأنسب لاحتياجاتك بسعر معقول.</p>
                    </div>
                    <ul class="nav pricing-btns-group">
                        <li><a class="active btn" data-toggle="tab" href="#monthly">بالشهر</a></li>
                        <li><a class="btn" data-toggle="tab" href="#yearly">بالسنة <span class="btn-badge">10% تخفيض</span></a></li>
                    </ul>
                </div>
                <div class="col-md-7">
                    <div class="tab-content">
                        <div id="monthly" class="tab-pane fade in active show">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="pricing-card">
                                        <header class="card-header">
                                            <h4>الخطة الفردية</h4>
                                            <span class="card-header-price">
                                                <span class="simbole">$</span>
                                                <span class="price-num">22</span>
                                                <span class="price-date">/شهرياً</span>
                                            </span>
                                            <div class="shape-bottom">
                                                <img src="{{asset('website_files/images/shapes/price-shape.svg')}}" alt="shape" class="bottom-shape img-fluid">
                                            </div>
                                        </header>
                                        <div class="card-body">
                                            <ul>
                                                <li>
                                                    <span class="fas fa-check"></span>
                                                    دعم مجاني
                                                </li>
                                                <li>
                                                    <span class="fas fa-check"></span>
                                                    400+ صفحة
                                                </li>
                                                <li>
                                                    <span class="fas fa-check"></span>
                                                    100+ صفحات داخلية
                                                </li>
                                                <li>
                                                    <span class="fas fa-check"></span>
                                                    20+ صفحة رئيسية
                                                </li>
                                            </ul>
                                            <button type="button" class="btn btn-sm btn-block">ابدأ الآن</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="pricing-card top-35">
                                        <header class="card-header">
                                            <h4>خطط الشركات</h4>
                                            <span class="card-header-price">
                                                <span class="simbole">$</span>
                                                <span class="price-num">99</span>
                                                <span class="price-date">/شهرياً</span>
                                            </span>
                                            <div class="shape-bottom">
                                                <img src="{{asset('website_files/images/shapes/price-shape.svg')}}" alt="shape" class="bottom-shape img-fluid">
                                            </div>
                                        </header>
                                        <div class="card-body">
                                            <ul>
                                                <li>
                                                    <span class="fas fa-check"></span>
                                                    دعم مجاني
                                                </li>
                                                <li>
                                                    <span class="fas fa-check"></span>
                                                    400+ صفحة
                                                </li>
                                                <li>
                                                    <span class="fas fa-check"></span>
                                                    100+ صفحات داخلية
                                                </li>
                                                <li>
                                                    <span class="fas fa-check"></span>
                                                    20+ صفحة رئيسية
                                                </li>
                                                <li>
                                                    <span class="fas fa-check"></span>
                                                    نطاق مجاني
                                                </li>
                                                <li>
                                                    <span class="fas fa-check"></span>
                                                    موقع تجارة الكترونية
                                                </li>
                                            </ul>
                                            <button type="button" class="btn btn-sm btn-block">ابدأ الآن</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="yearly" class="tab-pane fade">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="pricing-card">
                                        <header class="card-header">
                                            <h4>الخطة الفردية</h4>
                                            <span class="card-header-price">
                                                <span class="simbole">$</span>
                                                <span class="price-num">122</span>
                                                <span class="price-date">/سنوياً</span>
                                            </span>
                                            <div class="shape-bottom">
                                                <img src="{{asset('website_files/images/shapes/price-shape.svg')}}" alt="shape" class="bottom-shape img-fluid">
                                            </div>
                                        </header>
                                        <div class="card-body">
                                            <ul>
                                                <li>
                                                    <span class="fas fa-check"></span>
                                                    دعم مجاني
                                                </li>
                                                <li>
                                                    <span class="fas fa-check"></span>
                                                    400+ صفحة
                                                </li>
                                                <li>
                                                    <span class="fas fa-check"></span>
                                                    100+ صفحات داخلية
                                                </li>
                                                <li>
                                                    <span class="fas fa-check"></span>
                                                    20+ صفحة رئيسية
                                                </li>
                                            </ul>
                                            <button type="button" class="btn btn-sm btn-block">ابدأ الآن</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="pricing-card top-35">
                                        <header class="card-header">
                                            <h4>خطط الشركات</h4>
                                            <span class="card-header-price">
                                                <span class="simbole">$</span>
                                                <span class="price-num">299</span>
                                                <span class="price-date">/سنوياً</span>
                                            </span>
                                            <div class="shape-bottom">
                                                <img src="{{asset('website_files/images/shapes/price-shape.svg')}}" alt="shape" class="bottom-shape img-fluid">
                                            </div>
                                        </header>
                                        <div class="card-body">
                                            <ul>
                                                <li>
                                                    <span class="fas fa-check"></span>
                                                    دعم مجاني
                                                </li>
                                                <li>
                                                    <span class="fas fa-check"></span>
                                                    400+ صفحة
                                                </li>
                                                <li>
                                                    <span class="fas fa-check"></span>
                                                    100+ صفحات داخلية
                                                </li>
                                                <li>
                                                    <span class="fas fa-check"></span>
                                                    20+ صفحة رئيسية
                                                </li>
                                                <li>
                                                    <span class="fas fa-check"></span>
                                                    نطاق مجاني
                                                </li>
                                                <li>
                                                    <span class="fas fa-check"></span>
                                                    موقع تجارة الكترونية
                                                </li>
                                            </ul>
                                            <button type="button" class="btn btn-sm btn-block">ابدأ الآن</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
  End Pricing Section -->

    <!-- Start Reviews Section 
        <section id="reviews" class="section-block" data-scroll-index="7">
            <div class="shape-top"></div>
            <div class="container">
                <div class="row">
                    <div class="col-md-5">
                        <div class="section-header-style2">
                            <h2>ماذا يقول عملاؤنا!</h2>
                            <p>
                                لا تصدق كلماتنا فقط. <br>
                                تحقق من هذه الشهادات من عملائنا الراضين!
                            </p>
                            <div class="review_nav">
                                <span class="ti-angle-right button_prev"></span>
                                <span class="ti-angle-left button_next"></span>
                            </div>

                            <div class="btn-read-more"><a class="btn theme-btn" href="reviews.html">مشاهدة كل الأراء</a></div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="review-details-content">
                            <div class="owl-carousel review_details" id="review_details-1">
                                <div class="item">
                                    <p>"لوريم ايبسوم دولار سيت أميت ,كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور أنكايديديونتيوت لابوري ات دولار ماجنا أليكيوا !"</p>
                                    <h5>سارة احمد</h5>
                                    <h6>مصممة مواقع</h6>
                                </div>

                                <div class="item">
                                    <p>"لوريم ايبسوم دولار سيت أميت ,كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور أنكايديديونتيوت لابوري ات دولار ماجنا أليكيوا !"</p>
                                    <h5>احمد رائد</h5>
                                    <h6>مسوق الكتروني</h6>
                                </div>

                                <div class="item">
                                    <p>"لوريم ايبسوم دولار سيت أميت ,كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور أنكايديديونتيوت لابوري ات دولار ماجنا أليكيوا !"</p>
                                    <h5>محمود رجب</h5>
                                    <h6>مطور مواقع</h6>
                                </div>

                                <div class="item">
                                    <p>"لوريم ايبسوم دولار سيت أميت ,كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور أنكايديديونتيوت لابوري ات دولار ماجنا أليكيوا !"</p>
                                    <h5>ماركو فليب</h5>
                                    <h6>مبرمج</h6>
                                </div>

                                <div class="item">
                                    <p>"لوريم ايبسوم دولار سيت أميت ,كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور أنكايديديونتيوت لابوري ات دولار ماجنا أليكيوا !"</p>
                                    <h5>لارين متولي</h5>
                                    <h6>مصمم مواقع</h6>
                                </div>
                            </div>
                        </div>

                        <div class="review-photo-list">
                            <div class="owl-carousel review_photo" id="review_photo-1">
                                <div class="item">
                                    <div class="review_photo_block">
                                        <img src="{{asset('website_files/images/reviews/author-1.jpg')}}" alt="Img">
                                    </div>
                                </div>

                                <div class="item">
                                    <div class="review_photo_block">
                                        <img src="{{asset('website_files/images/reviews/author-2.jpg')}}" alt="Img">
                                    </div>
                                </div>

                                <div class="item">
                                    <div class="review_photo_block">
                                        <img src="{{asset('website_files/images/reviews/author-3.jpg')}}" alt="Img">
                                    </div>
                                </div>

                                <div class="item">
                                    <div class="review_photo_block">
                                        <img src="{{asset('website_files/images/reviews/author-4.jpg')}}" alt="Img">
                                    </div>
                                </div>

                                <div class="item">
                                    <div class="review_photo_block">
                                        <img src="{{asset('website_files/images/reviews/author-1.jpg')}}" alt="Img">
                                    </div>
                                </div>

                                <div class="item">
                                    <div class="review_photo_block">
                                        <img src="{{asset('website_files/images/reviews/author-3.jpg')}}" alt="Img">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="owl-carousel list-clients">
                    <div class="clients-item">
                        <a href="#" title="">
                            <img src="{{asset('website_files/images/clients/1.png')}}" alt="Img" />
                        </a>
                    </div>
                    <div class="clients-item">
                        <a href="#" title="">
                            <img src="{{asset('website_files/images/clients/2.png')}}" alt="Img" />
                        </a>
                    </div>
                    <div class="clients-item">
                        <a href="#" title="">
                            <img src="{{asset('website_files/images/clients/3.png')}}" alt="Img" />
                        </a>
                    </div>
                    <div class="clients-item">
                        <a href="#" title="">
                            <img src="{{asset('website_files/images/clients/4.png')}}" alt="Img" />
                        </a>
                    </div>
                    <div class="clients-item">
                        <a href="#" title="">
                            <img src="{{asset('website_files/images/clients/5.png')}}" alt="Img" />
                        </a>
                    </div>
                    <div class="clients-item">
                        <a href="#" title="">
                            <img src="{{asset('website_files/images/clients/6.png')}}" alt="Img" />
                        </a>
                    </div>
                </div>
            </div>
            <div class="review-shape-bottom"></div>
            <div class="shape-bottom"></div>
        </section>
         End Reviews Section -->

    <!-- Start Faqs Section -->
    <section id="faqs" class="section-block" data-scroll-index="6">
        <div class="container">
            <div class="section-header">
                <h2>@lang('website.repeated_questions')</h2>

            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="img-box">
                        <img src="{{asset('website_files/images/2.png')}}" class="img-fluid" alt="Img" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="accordion" id="accordionExample">
                        <!-- Start Faq item -->
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h2 class="mb-0">
                                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        @lang('website.question1')
                                    </button>
                                </h2>
                            </div>

                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                <div class="card-body">
                                    @lang('website.question1_content')
                                </div>
                            </div>
                        </div>
                        <!-- End Faq item -->

                        <!-- Start Faq item -->
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                                <h2 class="mb-0">
                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        @lang('website.question2')
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                <div class="card-body">
                                    @lang('website.question2_content')
                                </div>
                            </div>
                        </div>
                        <!-- End Faq item -->

                        <!-- Start Faq item -->
                        <div class="card">
                            <div class="card-header" id="headingThree">
                                <h2 class="mb-0">
                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        @lang('website.question3')
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                <div class="card-body">
                                    @lang('website.question3_content')
                                </div>
                            </div>
                        </div>
                        <!-- End Faq item -->

                        <!-- Start Faq item -->
                        <div class="card">
                            <div class="card-header" id="headingFour">
                                <h2 class="mb-0">
                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        @lang('website.question4')
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseFour" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                <div class="card-body">
                                   @lang('website.question4_content')
                                </div>
                            </div>
                        </div>
                        <!-- End Faq item -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Faqs Section -->

    <!-- Start Blog Section 
    <section id="blog" class="section-block" data-scroll-index="5">
        <div class="container">
            <div class="section-header">
                <h2>اقرأ آخر أخبارنا</h2>
                <p>
                    لورير سيت م ايبسوم دولار سيت أميت ,كونسيكتيتور أدايبا يسكينج <br>
                    لوريم ايبسوم دولار سيت أميت .
                </p>
            </div>
            <div class="owl-carousel blog-slider">
                <div class="blog-item">
                    <div class="blog-article style-1">
                        <div class="article-img">
                            <img src="{{asset('website_files/images/blog/img1.jpg')}}" class="img-fluid" alt="Img">
                        </div>
                        <article class="article-content">
                            <h4><a href="single-post.html">لوريم ايبسوم دولار سيت أميت ,كونسيكتيتور أدايبا يسكينج أليايت</a></h4>
                            <div class="post-author">
                                <div class="img-block"><img src="{{asset('website_files/images/blog/author-1.jpg')}}" class="img-fluid" alt="Img" /></div>
                                <h5>مارينا فليب</h5>
                            </div>
                        </article>
                    </div>
                </div>
                <div class="blog-item">
                    <div class="blog-article style-2">
                        <article class="article-content">
                            <h4><a href="single-post.html">" لوريم ايبسوم دولار سيت أميت ,كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور أنكايديديونتيوت لابوري ات دولار ماجنا أليكيوا . يوت انيم أد مينيم فينايم,كيواس نوستريد أكسير سيتاشن يللأمكو لابورأس. "</a></h4>
                            <div class="post-author">
                                <div class="img-block"><img src="{{asset('website_files/images/blog/author-2.jpg')}}" class="img-fluid" alt="Img" /></div>
                                <h5>محمد متولى</h5>
                            </div>
                            <a href="single-post.html" class="btn theme-btn">قراءة المزيد</a>
                        </article>
                    </div>
                </div>
                <div class="blog-item">
                    <div class="blog-article style-1">
                        <div class="article-img">
                            <img src="{{asset('website_files/images/blog/img2.jpg')}}" class="img-fluid" alt="Img">
                        </div>
                        <article class="article-content">
                            <h4><a href="single-post.html">لوريم ايبسوم دولار سيت أميت ,كونسيكتيتور أدايبا يسكينج أليايت</a></h4>
                            <div class="post-author">
                                <div class="img-block"><img src="{{asset('website_files/images/blog/author-3.jpg')}}" class="img-fluid" alt="Img" /></div>
                                <h5>سارة هشام</h5>
                            </div>
                        </article>
                    </div>
                </div>
                <div class="blog-item">
                    <div class="blog-article style-2">
                        <article class="article-content">
                            <h4><a href="single-post.html">" لوريم ايبسوم دولار سيت أميت ,كونسيكتيتور أدايبا يسكينج أليايت,سيت دو أيوسمود تيمبور أنكايديديونتيوت لابوري ات دولار ماجنا أليكيوا . يوت انيم أد مينيم فينايم,كيواس نوستريد أكسير سيتاشن يللأمكو لابورأس. "</a></h4>
                            <div class="post-author">
                                <div class="img-block"><img src="{{asset('website_files/images/blog/author-4.jpg')}}" class="img-fluid" alt="Img" /></div>
                                <h5>سمير محمود</h5>
                            </div>
                            <a href="single-post.html" class="btn theme-btn">قراءة المزيد</a>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </section>
 End Blog Section -->

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
                                    <span> info@nearsouq.com </span>
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
