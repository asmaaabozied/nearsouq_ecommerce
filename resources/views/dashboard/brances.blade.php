<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" name="csrf-token"
          content="{{ csrf_token() }}">

    <title>Store Registration</title>
    <!-- Include SmartWizard CSS -->
    <link href="https://cdn.jsdelivr.net/npm/smartwizard@5/dist/css/smart_wizard_all.min.css" rel="stylesheet" type="text/css"/>

    {{--noty--}}
    <link rel="stylesheet" href="{{ asset('dashboard_files/plugins/noty/noty.css') }}">
    <script src="{{ asset('dashboard_files/plugins/noty/noty.min.js') }}"></script>

    {{--morris--}}
    <link rel="stylesheet" href="{{ asset('dashboard_files/plugins/morris/morris.css') }}">
    <link rel="shortcut icon" href="{{asset('frontend/assets/img/favicon.png')}}">
    @if(app()->getLocale()=='ar')

    <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{asset('frontend/asset_rtl/css/bootstrap.rtl.min.css')}}">


        <!-- Main CSS -->
        <link rel="stylesheet" href="{{asset('frontend/asset_rtl/css/style.css')}}">


        <!-- Main CSS -->
    @else

    <!-- Bootstrap CSS -->

        <link rel="stylesheet" href="{{asset('frontend/assets/css/style.css')}}">
        <link rel="stylesheet" href="{{asset('frontend/assets/css/bootstrap.min.css')}}">
        <script src="{{asset('frontend/assets/js/html5shiv.min.js')}}"></script>
        <script src="{{asset('frontend/assets/js/respond.min.js')}}"></script>

        <script src="{{ asset('dashboard_files/js/jquery.min.js') }}"></script>
        <script src="{{ asset('dashboard_files/js/select2.min.js') }}"></script>

        {{--noty--}}
        <link rel="stylesheet" href="{{ asset('dashboard_files/plugins/noty/noty.css') }}">
        <script src="{{ asset('dashboard_files/plugins/noty/noty.min.js') }}"></script>

        {{--morris--}}
        <link rel="stylesheet" href="{{ asset('dashboard_files/plugins/morris/morris.css') }}">


    @endif
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    <link rel="stylesheet" href="{{asset('frontend/assets/plugins/select2/css/select2.min.css')}}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{asset('frontend/assets/plugins/fontawesome/css/fontawesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/assets/plugins/fontawesome/css/all.min.css')}}">
    
    
          <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>

        #map {

            width: 100%;

            height: 400px;

        }

        .mapControls {

            margin-top: 10px;

            border: 1px solid transparent;

            border-radius: 2px 0 0 2px;

            box-sizing: border-box;

            -moz-box-sizing: border-box;

            height: 32px;

            outline: none;

            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);

        }

        #searchMapInput {

            background-color: #fff;

            font-family: Roboto;

            font-size: 15px;

            font-weight: 300;

            margin-left: 12px;

            padding: 0 11px 0 13px;

            text-overflow: ellipsis;

            width: 50%;

        }

        #searchMapInput:focus {

            border-color: #4d90fe;

        }

    </style>

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
                        <h1>@lang('site.store_register')</h1>
                        <p class="account-subtitle">@lang('site.register_wellcome')</p>
 @include('partials._errors')
    <form method="post" action="{{route('dashboard.addbracnch')}}" name="ShopRegisterForm" id="ShopRegisterForm"
          enctype='multipart/form-data'
          files="true" autocomplete="off">
        @csrf
        <p>
        @include('flash::message')

            <!-- SmartWizard html -->
        <div id="smartwizard">


            <!--<ul class="nav">-->
            <!--    <li class="nav-item">-->
            <!--        <a class="nav-link" href="#step-1">-->
        <!--            <strong> @lang('site.step_1')</strong> <br>@lang('site.step_1_info')-->
            <!--        </a>-->
            <!--    </li>-->
            <!--    <li class="nav-item">-->
            <!--        <a class="nav-link" href="#step-2">-->
        <!--            <strong>@lang('site.step_2')</strong> <br>@lang('site.step_2_info')-->
            <!--        </a>-->
            <!--    </li>-->
            <!--    <li class="nav-item">-->
            <!--        <a class="nav-link" href="#step-3">-->
        <!--            <strong>@lang('site.step_3')</strong> <br>@lang('site.step_3_info')-->
            <!--        </a>-->
            <!--    </li>-->
            <!--    <li class="nav-item">-->
            <!--        <a class="nav-link " href="#step-4">-->
        <!--            <strong>@lang('site.step_4')</strong> <br>@lang('site.step_4_info')-->
            <!--        </a>-->
            <!--    </li>-->
            <!--</ul>-->
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="#step-1">
                        <strong> @lang('site.step_1')</strong>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#step-2">
                        <strong>@lang('site.step_2')</strong>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#step-3">
                        <strong>@lang('site.step_3')</strong>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="#step-4">
                        <strong>@lang('site.step_4')</strong>
                    </a>
                </li>
            </ul>

            <div class="tab-content">

                <div id="step-1" class="tab-pane" role="tabpanel" aria-labelledby="step-1">

                    <div class="form-group">


                        <label>@lang('site.commerical_number') <span
                                class="text-danger">*</span></label> {{--Commerical Register Number --}}
                        <input type="hidden" value="{{$shops->id ?? ''}}" name="parent_id">
                        <input type="hidden" value="{{auth()->user()->id ?? ''}}" name="shop_id">
                        <input id="commerical_number" name="commerical_number"
                               placeholder="@lang('site.commerical_number')" maxlength="10"
                               value="{{$shops->commerical_number ?? ''}}" class="form-control"
                               size="10"> {{--Commerical Register Number--}}


<br>
      <!--<div class="g-recaptcha" data-sitekey="6Lc_IGkgAAAAANyi_LNndbDtol_BqcIMsfobwEbZ" required></div>-->
      <!--<br>-->
                        <div class="status_view"></div>

                        <button id="verification_btn" type="button" class="btn btn-default" value=""/>
                        @lang('site.verification') </button>
                        <br>

                         <label>@lang('site.vat') </label>
                            <input id="vat" name="vat"
                               value="{{$shops->vat ?? ''}}"
                               class="form-control"
                              >

                         <br>


                        <label>@lang('site.vat_no') </label>
                        <input id="vat_no" name="vat_no"
                               value="{{$shops->vat_no ?? ''}}"
                               class="form-control"
                              >

                        <br>


                        @if (auth()->user()->hasRole('Super Admin'))
                            <label>@lang('site.commission') </label>
                            <input  name="commission"
                                    value="{{old('commission')}}"
                                    class="form-control"
                            >

                            <br>

                        @endif
                        <label>@lang('site.ShopArabicName') <span class="text-danger">*</span></label>

                        <input id="name_ar" name="name_ar"
                               value="{{$shops->name_ar ?? ''}}"
                               class="form-control"
                               placeholder="@lang('site.ShopArabicName')"
                               required>
                        <br>
                        <label>@lang('site.ShopEnglishName') <span
                                class="text-danger">*</span></label> {{--Shop  English Name--}}
                        <input id="name_en" name="name_en" value="{{$shops->name_en ?? ''}}"
                               placeholder="@lang('site.ShopEnglishName')"
                               class="form-control"
                               required> {{--Shop   Name--}}

                        <br>

                        <label>@lang('site.brand_name_ar') <span
                                class="text-danger">*</span></label> {{--brand_name_ar--}}
                        <input id="brand_name_ar" name="brand_name_ar" value="{{$shops->brand_name_ar ?? ''}}"
                               placeholder="@lang('site.brand_name_ar')"
                               class="form-control"
                               required>

                        <br>


                        <label>@lang('site.brand_name_en') <span
                                class="text-danger">*</span></label> {{--brand_name_en--}}
                        <input id="brand_name_en" name="brand_name_en" value="{{$shops->brand_name_en ?? ''}}"
                               placeholder="@lang('site.brand_name_en')"
                               class="form-control"
                               required>

                        <br>


                        <label for="sel1">@lang('site.categories'): <span class="text-danger">*</span></label>
                        <!--multiple-->
                        <select class="form-control" id="category_id" name="category_id"
                                data-placeholder="@lang('site.categories')">
                                  <option selected disabled>@lang('site.select')</option>
                                <option value="0"  >@lang('site.nodataselect')</option>

                        @foreach(App\Models\category::all() as $cat)
                                <option value="{{$cat->id}}">{{$cat->name ?? ''}}</option>
                            @endforeach
                        </select>


                        <br>
                        @if(auth()->user())
                        @if (auth()->user()->hasRole('Super Admin'))

                            <label for="sel1">@lang('site.shops'): <span class="text-danger">*</span></label>

                            <select class="form-control" id="parent_id" name="parent_id"
                                    data-placeholder="@lang('site.shops')">
                                  <option selected disabled>@lang('site.select')</option>
                                <option value="0"  >@lang('site.nodataselect')</option>

                                @foreach(App\Models\Shop::whereNull('parent_id')->get() as $shop)
                                    <option value="{{$shop->id}}">{{$shop->name ?? ''}}</option>
                                @endforeach
                            </select>

                            <br>



                    @endif
                    @endif
                        <!--   desc_ar -->
                        <label for="desc_ar">@lang('site.description')<span class="text-danger">*</span></label>
                        <textarea class="form-control" value="{{old('desc_ar')}}" rows="5" id="desc_ar" name="desc_ar"
                                  placeholder="@lang('site.description') "> {{$shops->desc_ar ?? ''}}</textarea>


                        <!--   desc_ar -->
                        <label for="desc_en">@lang('site.desc_en')<span class="text-danger">*</span></label>
                        <textarea class="form-control" value="{{old('desc_en')}}" rows="5" id="desc_en" name="desc_en"
                        ></textarea>
                    </div>

                </div>
                <div id="step-2" class="tab-pane" role="tabpanel" aria-labelledby="step-2">

                    <div class="form-group">

                        <label>@lang('site.phone') <span class="text-danger">*</span></label> {{-- telephone--}}
                        <input class="form-control" id="telephone" name="telephone" value="{{auth()->user()->phone ?? ''}}"
                               placeholder="@lang('site.phone')"
                               maxlength="10" size="10"> {{--Phone Number--}}

                        <label>@lang('site.mobilephone') <span
                                class="text-danger">*</span></label> {{--Mobile Number--}}
                        <input class="form-control" id="mobilephone" name="mobilephone"
                               placeholder="@lang('site.mobilephone')" value="{{$shop->mobilephone ?? ''}}"
                               size="10"> {{--Mobile Number--}}


                        <label>@lang('site.email') <span class="text-danger">*</span></label> {{--Email Address--}}
                        <input class="form-control" id="email" type="email" name="email" value="{{old('email')}}"
                               placeholder="trans('site.email')"
                               required> {{--Email Address--}}

                        <label>@lang('site.password') <span class="text-danger">*</span></label> {{--Password--}}
                        <input class="form-control" id="password" name="password" type="password"
                               placeholder="@lang('site.password')" required> {{--Password--}}


                        <label>@lang('site.Confirm Password') <span class="text-danger">*</span></label> {{----}}
                        <input class="form-control" id="re_password" name="re_password" type="password"
                               placeholder="@lang('site.Confirm Password')" required> {{--Confirm Password--}}


                    </div>
                </div>
                <div id="step-3" class="tab-pane" role="tabpanel" aria-labelledby="step-3">
                    <div class="form-group">


                        <label>@lang('site.address') <span class="text-danger">*</span></label> {{--Address --}}
                        <input class="form-control" id="address" name="address" value="{{old('address')}}"
                               placeholder="@lang('site.address')">


                        <div class="radio">
                            <label><input type="radio" name="addressType" id="insideMall" value="inside_mall"
                                          class="form-check-input @error('addressType') is-invalid @enderror">@lang('site.inside_mall')
                            </label>
                        </div>
                        <div class="radio">
                            <label><input type="radio" name="addressType" id="outsideMall" value="outside_mall"
                                          class="form-check-input @error('addressType') is-invalid @enderror">@lang('site.outside_mall')
                            </label>
                        </div>


                        <div id="mapdiv">

                            <input id="searchMapInput" class="mapControls controls" type="text" placeholder="">
                            <div id="map"></div>
                            <ul id="geoData">
                                <li>@lang('site.latitude'): <span id="lat-span"></span>
                                    <input value="" id="lat" name="latitude" class="latitude">
                                </li>

                                <li>@lang('site.longitude'): <span id="lon-span"></span>
                                    <input value="" id="lng" name="longitude" class="longitude"></li>
                            </ul>

                        </div>

                        <div id="malldiv">
                            <select class="form-select" style="width:100% !important;" name="mall_id" id="mall_id">
                                              <option selected disabled>@lang('site.select')</option>
                                <option value="0" >@lang('site.nodataselect')</option>
                                @foreach(App\Models\Mall::get() as $mall)
                                    <option value="{{$mall->id}}">{{$mall->name}} </option>
                                @endforeach

                            </select>
                        </div>


                    </div>


                </div>
                <div id="step-4" class="tab-pane" role="tabpanel" aria-labelledby="step-4">


                    <div class="form-group">




                    <span class="btn  fileinput-button">
                        <label> @lang('site.commercial_register') </label>
                        <input class="form-control" type="file" name="commerical_img" id="commerical_file"
                               accept="image/jpeg, image/png, image/gif,"
                               required><br/>
                    </span>


                        <span class="btn  fileinput-button">
                        <label> @lang('site.tax_file') </label>
                        <input class="form-control" type="file" name="vat_img" id="tax_file"
                               accept="image/jpeg, image/png, image/gif,"><br/>

                    </span>

              <span class="btn  fileinput-button">



        <label>@lang('site.mainimage')</label>
        <input type="file" name="image" class="form-control"
               value="{{ old('image') }}">

                </span>

                        <br>
                        <input class="form-check-input" type="checkbox" value="" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">
                            <a href="{{url('dashboard/pages/2/edit')}}" target="_blank">@lang('site.termcondition')</a>
                        </label>


                    </div>
                    <br>



                </div>
            </div>
        </div>
    </form>


                        <!-- /Form -->

                        <div class="login-or">
                            <span class="or-line"></span>
                            <span class="span-or">or</span>
                        </div>
                        <!-- Social Login -->
                        <!--<div class="social-login">-->
                        <!--    <span>Register with</span>-->
                        <!--    <a href="#" class="facebook"><i class="fab fa-facebook-f"></i></a><a href="#" class="google"><i class="fab fa-google"></i></a>-->
                        <!--</div>-->
                        <!-- /Social Login -->
								<div class="text-center dont-have">@lang('site.Already have an account?') <a href="{{route('login')}}">@lang('site.login')</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /Main Wrapper -->

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.5.0.min.js"
        integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>


<script type="text/javascript">
    var commerical_number_checked = false;

    $(document).ready(function () {

        // Toolbar extra buttons
        var btnFinish = $('<button></button>').text('Finish')
            .addClass('btn btn-info')
            .on('click', function () {
                //  alert('Finish Clicked');


                var commerical_file = $('#commerical_file').val();

                if (commerical_file.length == '') {
                    var n = new Noty({
                        text: "@lang('site.Please enter a commercial registration photo')",
                        type: "error",
                        killer: true,
                        buttons: [

                            Noty.button("@lang('site.ok')", 'btn btn-primary mr-2', function () {
                                n.close();
                            })
                        ]
                    });
                    n.show();

                    // alert("Please enter a commercial registration photo");
                    return false;
                }

                if ($('input[name="terms"]:checked').length == 0) {

                    var n = new Noty({
                        text: "@lang('site.Please agree to the terms and conditions')",
                        type: "error",
                        killer: true,
                        buttons: [

                            Noty.button("@lang('site.ok')", 'btn btn-primary mr-2', function () {
                                n.close();
                            })
                        ]
                    });
                    n.show();
                    // alert("Please agree to the terms and conditions");
                    return false;
                }


                //-------------------insert data to database ------------------------------
                var frm = $('#ShopRegisterForm');
                // console.log(frm.serialize());
                // return false;
                {{--$.ajax({--}}
                {{--        headers: {--}}
                {{--            'X-CSRF-TOKEN': "{{ csrf_token() }}"--}}
                {{--        },--}}
                {{--        type: 'POST',--}}
                {{--        url: "{{route('dashboard.parentShopRegisters')}}",--}}

                {{--        data: frm.serialize(),--}}
                {{--        success: function (response) {--}}
                {{--            // console.log(response);--}}
                {{--            if (response.error == "1") {--}}
                {{--                window.location.reload(true);--}}
                {{--                // alert("error");--}}

                {{--                var n = new Noty({--}}
                {{--                    text: "@lang('site.error')",--}}
                {{--                    type: "error",--}}
                {{--                    killer: true,--}}
                {{--                    buttons: [--}}

                {{--                        Noty.button("@lang('site.ok')", 'btn btn-primary mr-2', function () {--}}
                {{--                            n.close();--}}
                {{--                        })--}}
                {{--                    ]--}}
                {{--                });--}}
                {{--                n.show();--}}

                {{--            } else {--}}
                {{--                var response = JSON.parse(response);--}}
                {{--                // selecting values from response Object--}}
                {{--                var status = response.status;--}}


                {{--                if (status == "saved") {--}}
                {{--                    var n = new Noty({--}}
                {{--                        text: "@lang('site.success')",--}}
                {{--                        type: "error",--}}
                {{--                        killer: true,--}}
                {{--                        buttons: [--}}

                {{--                            Noty.button("@lang('site.ok')", 'btn btn-primary mr-2', function () {--}}
                {{--                                n.close();--}}
                {{--                            })--}}
                {{--                        ]--}}
                {{--                    });--}}
                {{--                    n.show();--}}

                {{--                    // alert("successfully registered");--}}

                {{--                } else {--}}
                {{--                    var n = new Noty({--}}
                {{--                        text: "@lang('site.Registration failed')",--}}
                {{--                        type: "error",--}}
                {{--                        killer: true,--}}
                {{--                        buttons: [--}}

                {{--                            Noty.button("@lang('site.ok')", 'btn btn-primary mr-2', function () {--}}
                {{--                                n.close();--}}
                {{--                            })--}}
                {{--                        ]--}}
                {{--                    });--}}
                {{--                    n.show();--}}

                {{--                    // alert("Registration failed");--}}

                {{--                }--}}

                {{--            }--}}
                {{--        },--}}
                {{--        error: function (e) {--}}
                {{--            console.log(e);--}}
                {{--        }--}}
                {{--    }--}}
                {{--);--}}


            });
        var btnCancel = $('<button></button>').text('Cancel')
            .addClass('btn btn-danger')
            .on('click', function () {
                $('#smartwizard').smartWizard("reset");
            });

        // Step show event
        $("#smartwizard").on("showStep", function (e, anchorObject, stepNumber, stepDirection, stepPosition) {
            $("#prev-btn").removeClass('disabled');
            $("#next-btn").removeClass('disabled');
            if (stepPosition === 'first') {
                $("#prev-btn").addClass('disabled');
            } else if (stepPosition === 'last') {
                $("#next-btn").addClass('disabled');
            } else {
                $("#prev-btn").removeClass('disabled');
                $("#next-btn").removeClass('disabled');
            }
        });

        // Smart Wizard
        $('#smartwizard').smartWizard({
            selected: 0,
            theme: 'progress', // default, arrows, dots, progress
            // darkMode: true,
            transition: {
                animation: 'slide-horizontal', // Effect on navigation, none/fade/slide-horizontal/slide-vertical/slide-swing
            },
            toolbarSettings: {
                toolbarPosition: 'bottom', // both bottom
                toolbarExtraButtons: [btnFinish, btnCancel]
            }
        });

        // // External Button Events
        // $("#reset-btn").on("click", function() {
        //     // Reset wizard
        //     $('#smartwizard').smartWizard("reset");
        //     return true;
        // });

        // $("#prev-btn").on("click", function() {
        //     // Navigate previous
        //     $('#smartwizard').smartWizard("prev");
        //     return true;
        // });

        // $("#next-btn").on("click", function() {
        //     // Navigate next
        //     $('#smartwizard').smartWizard("next");
        //     return true;
        // });


        // Demo Button Events
        $("#got_to_step").on("change", function () {
            // Go to step
            var step_index = $(this).val() - 1;
            $('#smartwizard').smartWizard("goToStep", step_index);
            return true;
        });


        // Initialize the showStep event
        $("#smartwizard").on("leaveStep", function (e, anchorObject, stepIndex, stepDirection) {
            if (stepIndex == 0) {


                var commerical_number = $('#commerical_number').val();
                var vat = $('#vat').val();
                var name_ar = $('#name_ar').val();
                var name_en = $('#name_en').val();
                var brand_name_ar = $('#brand_name_ar').val();
                var brand_name_en = $('#brand_name_en').val();
                var category_id = $('#category_id').val();
                var desc_ar = $('#desc_ar').val();

                // alert(commerical_number_checked);
                //-----this for test only
                commerical_number_checked = true;

                if (commerical_number_checked == false) {
                    alert("Please check the commercial register");
                    return false;
                }
                if (vat.length == '' || name_ar.length == '' ||
                    name_en.length == '' || brand_name_ar.length == '' ||
                    brand_name_en.length == '' || category_id == null ||
                    desc_ar.length == '' || commerical_number.length == ''
                ) {
                    // alert("Please fill in the mandatory fields");

                    var n = new Noty({
                        text: "@lang('site.Please fill in the mandatory fields')",
                        type: "error",
                        killer: true,
                        buttons: [

                            Noty.button("@lang('site.ok')", 'btn btn-primary mr-2', function () {
                                n.close();
                            })
                        ]
                    });
                    n.show();

                    return false;
                }

            } else if (stepIndex == 1) {
                var telephone = $('#telephone').val();
                var mobilephone = $('#mobilephone').val();
                var email = $('#email').val();
                var password = $('#password').val();
                var re_password = $('#re_password').val();


                if (telephone.length == '' || mobilephone.length == '' ||
                    email.length == '' || password.length == '' ||
                    re_password.length == ''
                ) {
                    // alert("Please fill in the mandatory fields");


                    var n = new Noty({
                        text: "@lang('site.Please fill in the mandatory fields')",
                        type: "error",
                        killer: true,
                        buttons: [

                            Noty.button("@lang('site.ok')", 'btn btn-primary mr-2', function () {
                                n.close();
                            })
                        ]
                    });
                    n.show();
                    return false;
                }


                if (password != re_password) {
                    // alert("Passwords do not match");
                    var n = new Noty({
                        text: "@lang('site.Passwords do not match')",
                        type: "error",
                        killer: true,
                        buttons: [

                            Noty.button("@lang('site.ok')", 'btn btn-primary mr-2', function () {
                                n.close();
                            })
                        ]
                    });
                    n.show();
                    return false;
                }
            } else if (stepIndex == 2) {

                if ($('input[name="addressType"]:checked').length == 0) {
                    // alert("Please choose a store location");

                    var n = new Noty({
                        text: "@lang('site.Please choose a store location')",
                        type: "error",
                        killer: true,
                        buttons: [

                            Noty.button("@lang('site.ok')", 'btn btn-primary mr-2', function () {
                                n.close();
                            })
                        ]
                    });
                    n.show();

                    return false;
                }

                var address = $('#address').val();
                if (address.length == '') {


                    var n = new Noty({
                        text: "@lang('site.Please enter a address description')",
                        type: "error",
                        killer: true,
                        buttons: [

                            Noty.button("@lang('site.ok')", 'btn btn-primary mr-2', function () {
                                n.close();
                            })
                        ]
                    });
                    n.show();
                    return false;
                }


                var addressType = $("input[name='addressType']:checked").val();
                var mall_id = $('#mall_id').val();
                var lat = $('#lat').val();
                var lng = $('#lng').val();

                if (addressType == "inside_mall") {
                    if (mall_id == null) {
                        // alert("Please choose the mall");
                        var n = new Noty({
                            text: "@lang('site.Please choose the mall')",
                            type: "error",
                            killer: true,
                            buttons: [

                                Noty.button("@lang('site.ok')", 'btn btn-primary mr-2', function () {
                                    n.close();
                                })
                            ]
                        });
                        n.show();
                        return false;
                    }

                } else if (addressType == "outside_mall") {
                    if (lat.length == '' || lng.length == '') {
                        // alert("Please select a location from the map");
                        var n = new Noty({
                            text: "@lang('site.Please select a location from the map')",
                            type: "error",
                            killer: true,
                            buttons: [

                                Noty.button("@lang('site.ok')", 'btn btn-primary mr-2', function () {
                                    n.close();
                                })
                            ]
                        });
                        n.show();
                        return false;
                    }
                }


            } else if (stepIndex == 3) {
            }


        });


    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    @if( session()->has('success'))
    swal({
        title: "success",
        text: '{{ session()->get('success') }}',
        icon: "info",
        showCancelButton: true,
        closeOnConfirm: true,
    });
    {{session()->forget('success')}}
    @endif
</script>


<script type="text/javascript">
    $(document).ready(function () {
        $("#commerical_number").change(function () {//to reset status_view and commerical_number status
            $('.status_view').append('');

        });


        // $("#mapdiv").hide();
        $("#malldiv").hide();
        $("input[name$='addressType']").click(function () {

            // var test = ($(this).val());
            var radioValue = $("input[name='addressType']:checked").val();
            if (radioValue == "inside_mall") {
                $("#mapdiv").hide();
                $("#malldiv").show();
            } else if (radioValue == "outside_mall") {
                $("#mapdiv").show();
                $("#malldiv").hide();
            }

        });

        $('#verification_btn').on('click', function () {
            var commerical_number = document.getElementById("commerical_number").value;
//  var recaptcha_response = document.getElementById("g-recaptcha-response").value;
 
//       if (recaptcha_response == "") {
//                 var n = new Noty({

//                     text: "@lang('site.Please enter recaptcha_response')",
//                     type: "error",
//                     killer: true,
//                     buttons: [

//                         Noty.button("@lang('site.ok')", 'btn btn-primary mr-2', function () {
//                             n.close();
//                         })
//                     ]
//                 });
//                 n.show();
//                 // alert("return Please enter commerical_number");
//                 return;
//             }
            if (commerical_number == "") {
                var n = new Noty({

                    text: "@lang('site.Please enter commerical_number')",
                    type: "error",
                    killer: true,
                    buttons: [

                        Noty.button("@lang('site.ok')", 'btn btn-primary mr-2', function () {
                            n.close();
                        })
                    ]
                });
                n.show();
                // alert("return Please enter commerical_number");
                return;
            }
            $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    type: 'POST',
                    url: "{{route('dashboard.checkCommerical')}}",

                    data: {
                        commerical_number: commerical_number
                    },
                    success: function (response) {
                        // console.log(response);
                        if (response.error == "1") {
                            window.location.reload(true);
                            // alert("error");

                            var n = new Noty({

                                text: "@lang('site.error')",
                                type: "error",
                                killer: true,
                                buttons: [

                                    Noty.button("@lang('site.ok')", 'btn btn-primary mr-2', function () {
                                        n.close();
                                    })
                                ]
                            });
                            n.show();

                        } else {
                            var response = JSON.parse(response);
                            // selecting values from response Object
                            var status = response.status;
                            var crName = response.crName;
                            var expiryDate = response.expiryDate;
                            var description = response.description;
                            var location = response.location;

                            // console.log(crName);
                            // alert(crName);
                            // console.log(expiryDate);
                            // console.log(description);
                            if (status == "active") {
                                commerical_number_checked = true;
                                $("#name_ar").val(crName);
                                $("#address").val(location);
                                $("#desc_ar").val(description);
                                $('.status_view').append('<image src="http://nearsouq.com/public/img/valid.png" height="80" width="80">');

                            } else {
                                commerical_number_checked = false;
                                $('.status_view').append('<image src="http://nearsouq.com/public/img/not_valid.png" height="80" width="80">');

                            }

                        }
                    },
                    error: function (e) {
                        console.log(e);
                    }
                }
            );
        });

    });


    // Restricts input for the given textbox to the given inputFilter.
    function setInputFilter(textbox, inputFilter) {
        ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function (event) {
            textbox.addEventListener(event, function () {
                if (inputFilter(this.value)) {
                    this.oldValue = this.value;
                    this.oldSelectionStart = this.selectionStart;
                    this.oldSelectionEnd = this.selectionEnd;
                } else if (this.hasOwnProperty("oldValue")) {
                    this.value = this.oldValue;
                    this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                } else {
                    this.value = "";
                }
            });
        });
    }


    // Install input filters.
    setInputFilter(document.getElementById("commerical_number"), function (value) {
        return /^-?\d*$/.test(value);
    });
    setInputFilter(document.getElementById("vat"), function (value) {
        return /^-?\d*$/.test(value);
    });
    setInputFilter(document.getElementById("telephone"), function (value) {
        return /^-?\d*$/.test(value);
    });
    setInputFilter(document.getElementById("mobilephone"), function (value) {
        return /^-?\d*$/.test(value);
    });


    //==============================show map function =============================


    function initMap() {

        var mapOptions, map, marker, searchBox, city,
            infoWindow = '',
            addressEl = document.querySelector('#searchMapInput'),
            latEl = document.querySelector('.latitude'),
            longEl = document.querySelector('.longitude'),
            element = document.getElementById('map');

        mapOptions = {
            // How far the maps zooms in.
            zoom: 8,
            // Current Lat and Long position of the pin/
            center: new google.maps.LatLng(23.885942, 45.079162),
            // center : {
            // 	lat: -34.397,
            // 	lng: 150.644
            // },
            disableDefaultUI: false, // Disables the controls like zoom control on the map if set to true
            scrollWheel: true, // If set to false disables the scrolling on the map.
            draggable: true, // If set to false , you cannot move the map around.
            // mapTypeId: google.maps.MapTypeId.HYBRID, // If set to HYBRID its between sat and ROADMAP, Can be set to SATELLITE as well.
            // maxZoom: 11, // Wont allow you to zoom more than this
            // minZoom: 9  // Wont allow you to go more up.

        };

        /**
         * Creates the map using google function google.maps.Map() by passing the id of canvas and
         * mapOptions object that we just created above as its parameters.
         *
         */
        // Create an object map with the constructor function Map()
        map = new google.maps.Map(element, mapOptions); // Till this like of code it loads up the map.

        /**
         * Creates the marker on the map
         *
         */
        marker = new google.maps.Marker({
            position: mapOptions.center,
            map: map,
            // icon: 'http://pngimages.net/sites/default/files/google-maps-png-image-70164.png',
            draggable: true
        });

        /**
         * Creates a search box
         */
        searchBox = new google.maps.places.SearchBox(addressEl);

        /**
         * When the place is changed on search box, it takes the marker to the searched location.
         */
        google.maps.event.addListener(searchBox, 'places_changed', function () {
            var places = searchBox.getPlaces(),
                bounds = new google.maps.LatLngBounds(),
                i, place, lat, long, resultArray,
                addresss = places[0].formatted_address;

            for (i = 0; place = places[i]; i++) {
                bounds.extend(place.geometry.location);
                marker.setPosition(place.geometry.location);  // Set marker position new.
            }

            map.fitBounds(bounds);  // Fit to the bound
            map.setZoom(15); // This function sets the zoom to 15, meaning zooms to level 15.
            // console.log( map.getZoom() );

            lat = marker.getPosition().lat();
            long = marker.getPosition().lng();
            latEl.value = lat;
            longEl.value = long;

            resultArray = places[0].address_components;

            // Get the city and set the city input value to the one selected
// 		for( var i = 0; i < resultArray.length; i++ ) {
// 			if ( resultArray[ i ].types[0] && 'administrative_area_level_2' === resultArray[ i ].types[0] ) {
// 				citi = resultArray[ i ].long_name;
// 				city.value = citi;
// 			}
// 		}

            // Closes the previous info window if it already exists
            if (infoWindow) {
                infoWindow.close();
            }
            /**
             * Creates the info Window at the top of the marker
             */
            infoWindow = new google.maps.InfoWindow({
                content: addresss
            });

            infoWindow.open(map, marker);
        });


        /**
         * Finds the new position of the marker when the marker is dragged.
         */
        google.maps.event.addListener(marker, "dragend", function (event) {
            var lat, long, address, resultArray, citi;

            console.log('i am dragged');
            lat = marker.getPosition().lat();
            long = marker.getPosition().lng();

            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({latLng: marker.getPosition()}, function (result, status) {
                if ('OK' === status) {  // This line can also be written like if ( status == google.maps.GeocoderStatus.OK ) {
                    address = result[0].formatted_address;
                    resultArray = result[0].address_components;

                    // Get the city and set the city input value to the one selected
                    // for( var i = 0; i < resultArray.length; i++ ) {
                    // 	if ( resultArray[ i ].types[0] && 'administrative_area_level_2' === resultArray[ i ].types[0] ) {
                    // 		citi = resultArray[ i ].long_name;
                    // 		console.log( citi );
                    // 		city.value = citi;
                    // 	}
                    // }
                    addressEl.value = address;
                    latEl.value = lat;
                    longEl.value = long;

                } else {
                    console.log('Geocode was not successful for the following reason: ' + status);
                }

                // Closes the previous info window if it already exists
                if (infoWindow) {
                    infoWindow.close();
                }

                /**
                 * Creates the info Window at the top of the marker
                 */
                infoWindow = new google.maps.InfoWindow({
                    content: address
                });

                infoWindow.open(map, marker);
            });
        });


    }


</script>

<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDa3Ma_OClkpGeMBt1hqTSsFhLAOe_5xPQ&language=ar&dir=rtl&libraries=places&callback=initMap"
    async defer></script>


<!-- Include SmartWizard JavaScript source -->
<script src="{{asset('frontend/assets/js/jquery.smartWizard.js')}}"></script>


</body>
</html>
