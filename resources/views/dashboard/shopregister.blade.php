
<!--   style Mapling    -->
<style>

    #ShopRegisterForm {
        text-align: center;
        position: relative;
        margin-top: 20px
    }

    #ShopRegisterForm fieldset {
        background: white;
        border: 0 none;
        border-radius: 0.5rem;
        box-sizing: border-box;
        width: 100%;
        margin: 0;
        padding-bottom: 20px;
        position: relative
    }

    .form-card {
        text-align: left
    }

    #ShopRegisterForm fieldset:not(:first-of-type) {
        display: none
    }

    #ShopRegisterForm input,
    #ShopRegisterForm textarea {
        padding: 8px 15px 8px 15px;
        border: 1px solid #ccc;
        border-radius: 0px;
        margin-bottom: 25px;
        margin-top: 2px;
        width: 100%;
        box-sizing: border-box;
        font-family: montserrat;
        color: #2C3E50;
        background-color: #ECEFF1;
        font-size: 16px;
        letter-spacing: 1px
    }

    #ShopRegisterForm input:focus,
    #ShopRegisterForm textarea:focus {
        -moz-box-shadow: none !important;
        -webkit-box-shadow: none !important;
        box-shadow: none !important;
        border: 1px solid #3F736F;
        outline-width: 0
    }

    #ShopRegisterForm .action-button {
        width: 100px;
        background: #3F736F;
        font-weight: bold;
        color: white;
        border: 0 none;
        border-radius: 0px;
        cursor: pointer;
        padding: 6px 5px;
        margin: 1px 5px 10px 0px;
        float: right
    }

    #ShopRegisterForm .action-button:hover,
    #ShopRegisterForm .action-button:focus {
        background-color: #311B92
    }

    #ShopRegisterForm .action-button-previous {
        width: 100px;
        background: #616161;
        font-weight: bold;
        color: white;
        border: 0 none;
        border-radius: 0px;
        cursor: pointer;
        padding: 6px 5px;
        margin: 1px 5px 10px 0px;
        float: right;
    }

    #ShopRegisterForm .action-button-previous:hover,
    #ShopRegisterForm .action-button-previous:focus {
        background-color: #000000
    }

    .card {
        z-index: 0;
        border: none;
        position: relative
    }

    .fs-title {
        font-size: 25px;
        color: #3F736F;
        margin-bottom: 15px;
        font-weight: normal;
        text-align: left
    }

    .purple-text {
        color: #3F736F;
        font-weight: normal
    }

    .steps {
        font-size: 25px;
        color: gray;
        margin-bottom: 10px;
        font-weight: normal;
        text-align: right
    }

    .fieldlabels {
        color: gray;
        text-align: left
    }

    #progressbar {
        margin-bottom: 30px;
        overflow: hidden;
        color: lightgrey
    }

    #progressbar .active {
        color: #3F736F
    }

    #progressbar li {
        list-style-type: none;
        font-size: 15px;
        width: 33.3%;
        float: left;
        position: relative;
        font-weight: 400
    }

    #progressbar #account:before {
        font-family: FontAwesome;
        content: "\f13e"
    }

    #progressbar #personal:before {
        font-family: FontAwesome;
        content: "\f007"
    }

    #progressbar #payment:before {
        font-family: FontAwesome;
        content: "\f030"
    }

    #progressbar #confirm:before {
        font-family: FontAwesome;
        content: "\f00c"
    }

    #progressbar li:before {
        width: 50px;
        height: 50px;
        line-height: 45px;
        display: block;
        font-size: 20px;
        color: #ffffff;
        background: lightgray;
        border-radius: 50%;
        margin: 0 auto 10px auto;
        padding: 2px
    }

    #progressbar li:after {
        content: '';
        width: 100%;
        height: 2px;
        background: lightgray;
        position: absolute;
        left: 0;
        top: 25px;
        z-index: -1
    }

    #progressbar li.active:before,
    #progressbar li.active:after {
        background: #3F736F
    }

    .progress {
        height: 20px
    }

    .progress-bar {
        background-color: #3F736F
    }

    .fit-image {
        width: 100%;
        object-fit: cover
    }


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

    .form-check-label {
        margin-bottom: 22px;
    }

    .form-check.form-check-inline {
        margin-top: 10px;
    }

    .form-select {
        border: none;
    }
</style>
<!-- End Styling -->

<header>
    <div class="header-top pt-10 pt-lg-10 pt-md-10">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-center text-sm-left">
                    <div class="lang-currency-dropdown">
                        <ul>


                            <li><a href="#">اختر اللغة <i class="fa fa-chevron-down"></i></a>
                                <ul>





                                        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                            <li>
                                            <a class="dropdown-item" hreflang="{{ $localeCode }}"
                                               href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">

                                                <img src="{{asset('frontend/assets/img/flags/fr.png')}}" alt="" height="16">
                                                <span>{{ $properties['native'] }}</span>
                                            </a>
                                            </li>
                                        @endforeach
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12  text-center text-sm-right">
                    <div class="header-top-menu">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--<link href="css/locomotive-scroll.css" rel="stylesheet">-->

</header>
<meta name="google-signin-client_id" content="416149507323-2pdcpo2ibhsbctq4kde7aucmj40cc503.apps.googleusercontent.com">
<script src="https://apis.google.com/js/platform.js" async defer></script>
<div id="fb-root"></div>
<script async defer crossorigin="anonymous"
        src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&autoLogAppEvents=1&version=v9.0&appId=498073981169124"
        nonce="YFK4XINP"></script>

<style src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css"></style>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>
<link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6 col-xs-12">
            <div class="card col-12">

                <h2 id="heading text-center justify-content-center">@lang('site.Ceate New Account')</h2>

                <form method="post" action="{{url('save-shop')}}" id="ShopRegisterForm" enctype='multipart/form-data'
                      files="true" autocomplete="off">
                @csrf
                <!-- progressbar -->

               <ul id="progressbar">
                        <li class="active" id="account"><strong>@lang('site.profile')</strong></li>
                        <li id="personal"><strong>@lang('site.personal')</strong></li>
                        <li id="payment"><strong>@lang('site.payment')</strong></li>

                    </ul>
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <br> <!-- fieldsets -->
                    <fieldset>
                        <div class="form-card">


                            <div class="col-md-12 col-12 mb-20">

                                <div class="form-check form-check-inline" style="width:auto !important;">
                                    <input class="form-check-input @error('addressType') is-invalid @enderror"  type="radio" name="addressType" id="insideMall"
                                           value="1" style="width:auto !important;">
                                    <label class="form-check-label" for="insideMall"
                                           style="width:auto !important;"></label>
                                </div>
                                <div class="form-check form-check-inline" style="width:auto !important;">
                                    <input class="form-check-input @error('addressType') is-invalid @enderror" type="radio" name="addressType" id="outsideMall"
                                           value="0" style="width:auto !important;">
                                    <label class="form-check-label" for="outsideMall"
                                           style="width:auto !important;"></label>
                                </div>

                            </div>
                            <div class="col-md-12 col-12 mb-20" id="mapdiv">

                                <input id="searchMapInput" class="mapControls controls" type="text"
                                       placeholder="">

                                <div id="map"></div>

                                <ul id="geoData">

                                    <li>@lang('site.latitude'): <span id="lat-span"></span></span> <input value="" id="lat"
                                                                                                       name="lat"
                                                                                                       class="latitude">
                                    </li>

                                    <li>@lang('site.longitude'): <span id="lon-span"></span> <input value="" id="lng"
                                                                                                name="lng"
                                                                                                class="longitude"></li>

                                </ul>

                                <script>

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

                            </div>

                            <div class="col-md-12 col-12 mb-20" id="malldiv">
                                <select class="form-select" style="width:100% !important;" name="mall_id">
                                    <option value="NULL" selected>Select Mall</option>
                                    @foreach(App\Models\Mall::get() as $mall)
                                        <option value="{{$mall->id}}">{{$mall->name}} </option>
                                    @endforeach

                                </select>
                            </div>


                            <div class="col-md-12 col-12 mb-20">
                                <label>@lang('site.email') <span
                                            class="text-danger">*</span></label> {{--Email Address--}}
                                <input class="mb-0" id="email" name="email" value="{{old('email')}}"
                                       placeholder="trans('site.email')" class="@error('email') is-invalid @enderror"
                                       required> {{--Email Address--}}
                            </div>
                            @error('email')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror


                            <div class="col-md-12 col-12 mb-20">
                                <label>@lang('site.commerical_number') <span
                                            class="text-danger">*</span></label> {{--Commerical Register Number --}}
                                <div style="display=inline">
                                    <input class="mb-0" id="commerical_number" name="commerical_number"
                                           placeholder="@lang('site.commerical_number')" maxlength="10"
                                           value="{{old('commerical_number')}}"
                                           size="10"> {{--Commerical Register Number--}}
                                    <div class="status_view"></div>
                                </div>
                                <button id="verification_btn" type="button" value=""/>
                                @lang('site.verification') </buton>


                            </div>
                            <div class="col-md-12 col-12 mb-20">
                                <label>@lang('site.phone')</label> {{--Tax --}}
                                <input class="mb-0" id="tax" name="vat_no"
                                       value="{{old('tax')}}"
                                       placeholder="@lang('site.phone')"> {{--Mobile Number--}}
                            </div>

                            <div class="col-12 mb-20">
                                <label>@lang('site.password') <span class="text-danger">*</span></label> {{--Password--}}
                                <input class="mb-0" id="password" name="password" type="password"
                                       placeholder="@lang('site.password')" required> {{--Password--}}
                            </div>
                            <div class="col-12 mb-20">
                                <label>@lang('site.Confirm Password') <span
                                            class="text-danger">*</span></label> {{----}}
                                <input class="mb-0" id="password2" name="password2" type="password"
                                       placeholder="@lang('site.Confirm Password')" required> {{--Confirm Password--}}
                            </div>

                            <div class="col-md-12 col-12 mb-20">
                                <select class="chosen-select" style="width:100% !important;" name="category_id"
                                        data-placeholder="@lang('site.categories')" multiple>
                                    @foreach(App\Models\category::all() as $cat)
                                        <option value="{{$cat->id}}">{{$cat->name}}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <input type="button" name="next" class="next action-button" value="@lang('site.next')"/>
                    </fieldset>
                    <fieldset>
                        <div class="form-card">

                            <div class="col-md-12 col-12 mb-20">
                                <label>@lang('site.ShopArabicName') <span
                                            class="text-danger">*</span></label>

                                <input class="mb-0" id="name_ar" name="name_ar" value="{{old('name_ar')}}"
                                       placeholder="@lang('site.ShopArabicName')"
                                       required>
                            </div>
                            <div class="col-md-12 col-12 mb-20">
                                <label>@lang('site.ShopEnglishName') <span
                                            class="text-danger">*</span></label> {{--Shop  English Nam--}}
                                <input class="mb-0" id="name_en" name="name_en" value="{{old('name_en')}}"
                                       placeholder="@lang('site.ShopEnglishName')"
                                       required> {{--Shop   Nam--}}
                            </div>
                            <div class="col-md-12 col-12 mb-20">
                                <label>@lang('site.phone') <span
                                            class="text-danger">*</span></label> {{-- Number--}}
                                <input class="mb-0" id="phone" name="phone" value="{{old('phone')}}"
                                       placeholder="@lang('site.phone')"
                                       maxlength="10" size="10"> {{--Phone Number--}}
                            </div>

                            <div class="col-md-12 col-12 mb-20">
                                <label>@lang('site.mobilephone') <span
                                            class="text-danger">*</span></label> {{--Mobile Number--}}
                                <input class="mb-0" id="mobilephone" name="mobilephone"
                                       placeholder="@lang('site.mobilephone')" value="{{old('mobilephone')}}" maxlength="10"
                                       size="10"> {{--Mobile Number--}}
                            </div>

                            <div class="col-md-12 col-12 mb-20">
                                <label>@lang('site.address') <span class="text-danger">*</span></label> {{--Address --}}
                                <input class="mb-0" id="address" name="address" value="{{old('address')}}"
                                       placeholder="@lang('site.address')"> {{--Mobile Number--}}
                            </div>
                        </div>
                        <div class="col-md-12 col-12 mb-20">
                            <label>@lang('site.descrption') <span class="text-danger">*</span></label>
                        <!--   desc_ar -->
                            <textarea class="mb-0" id="desc_ar" name="desc_ar" value="{{old('desc_ar')}}"
                                      placeholder="@lang('site.descrption') "></textarea> {{--desc_ar--}}

                        </div>


                        <input type="button" name="next" class="next action-button" value="@lang('site.next')"/>
                        <input type="button" name="previous" class="previous action-button-previous"
                               value="@lang('site.previous')"/>
                    </fieldset>
                    <fieldset>
                        <div class="form-card">
                            <!--<div class="row">-->

                            <!--    <div class="col-5">-->
                            <!--        <h2 class="steps">Step 3 - 4</h2>-->
                            <!--    </div>-->
                            <!--</div> -->
                            <div class="col-12 mb-20">


        <span class="btn  fileinput-button">
            <label> @lang('site.file') </label>
            <input type="file" name="commerical_file" id="commerical_file" accept="image/jpeg, image/png, image/gif,"
                   required><br/>
        </span>
                            </div>
                            <div class="col-12 mb-20">


        <span class="btn  fileinput-button">
            <label> @lang('site.tax_file') </label>
            <input type="file" name="tax_file" id="tax_file" accept="image/jpeg, image/png, image/gif,"><br/>

        </span
        
                     
              <span class="btn  fileinput-button">



        <label>@lang('site.mainimage')</label>
        <input type="file" name="image" class="form-control"
               value="{{ old('image') }}">

                </span>
                            </div>


                            <div class=" col-12 mb-20 form-check">
                                <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" required>
                                <label class="form-check-label" for="defaultCheck1">
                                  @lang('site.termcondition') <a href="/public/terms" target="_blank">@lang('site.termcondition')</a>
                                </label>
                            </div>
                        </div>
                        <br>
                        <p id="commerical_msg" style="color:red;">يجب التحقق من السجل التجاري اولاً</p>
                        <div class="col-md-12">
                            <button class="register-button mt-0  action-button" disabled
                                    id="saveShop">@lang('site.add')</button> {{--Create--}}

                        </div>
                        <input type="button" name="previous" class="previous action-button-previous"
                               value="@lang('site.previous')"/>
                    </fieldset>

                </form>
            </div>
        </div>
    </div>
</div>

<script>

    //-------------------
    //   $("commerical_number").change(function(){
    //   alert("The text has been changed.");
    //   $('.status_view').append('');
    // });
    // function commericalChange(){
    //   alert("The text has been changed.");
    // }

    //-------------------
    function onSignInRegister(googleUser) {
        var profile = googleUser.getBasicProfile();
        console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
        console.log('Name: ' + profile.getName());
        console.log('Image URL: ' + profile.getImageUrl());
        console.log('Email: ' + profile.getEmail()); // This is null if the 'email' scope is not present.

        sendToServerCreateAccount(profile.getId() + "@google.com", profile.getId(), "google", profile.getName());
        //signOut();
    }

    function signOut() {
        var auth2 = gapi.auth2.getAuthInstance();
        auth2.signOut().then(function () {
            console.log('User signed out.');
        });
    }

    window.onload = function () {

        document.querySelector("#saveShop").addEventListener("click", e => {
            // resets and other code you want to happen when clicked
            console.log("inside function");
            doCreate();
        });


        function doCreate() {

            var mall_id = 0;
            var lng = 0;
            var lat = 0;

            var email = document.getElementById("email").value;
            var password = document.getElementById("password").value;
            var password2 = document.getElementById("password2").value;
            var name_en = document.getElementById("name_en").value;
            var name_ar = document.getElementById("name_ar").value;
            var phone = document.getElementById("phone").value;
            var address = document.getElementById("address").value;
            var mobilephone = document.getElementById("mobilephone").value;
            var add = document.querySelector('input[name="addressType"]:checked').value;
            var taxUrl = document.getElementById("tax_file");
            var commericalUrl = document.getElementById("commerical_file");
            var desc_ar = document.getElementById("desc_ar");


            if (email == "")
                return showNotification("pastel-danger", "", "bottom", "center", "", "");  // Please enter email-address
            if (password == "")
                return showNotification("pastel-danger", "", "bottom", "center", "", "");  // Please enter password
            if (password2 == "")
                return showNotification("pastel-danger", "", "bottom", "center", "", "");  // Please enter password
            if (password != password2)
                return showNotification("pastel-danger", "", "bottom", "center", "", "");  // Passwords are not equals
            if (name_en == "")
                return showNotification("pastel-danger", "", "bottom", "center", "", "");  // Please enter User Name
            if (name_ar == "")
                return showNotification("pastel-danger", "", "bottom", "center", "", "")

            if (add == '1') {
                mall_id = $('#mall_id').val();
            }
            if (add == '0') {
                lng = document.getElementById("lon-span").innerText;
                lat = document.getElementById("lat-span").innerText;
                mall_id = $('#mall_id').val();
            }

            // if(name  &&  email && password  && password2  ){
            //     document.getElementById("ShopRegisterForm").submit();
            // }
            // sendToServerCreateAccount(email, password, name, "email",phone , address ,mobilephone,mall_id,lat,lng,taxUrl,commericalUrl, desc_ar);
        }

        function sendToServerCreateAccount(email, password, typeReg, name, phone, address, mobilephone, mall_id, lat, lng, taxUrl, commericalUrl, desc_ar) {

            $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    type: 'POST',
                    url: '{{ url("save-shop") }}',
                    data: {
                        email: email,
                        password: password,
                        typeReg: typeReg,
                        name: name,
                        phone: phone,
                        address: address,
                        mobilephone: mobilephone,
                        lat: lat,
                        lng: lng,
                        mall_id: mall_id,
                        desc_ar: desc_ar

                    },
                    success: function (data) {
                        console.log(data);
                        if (data.error == "1")
                            window.location.reload(true);
                        else
                            showNotification("pastel-success", data.text, "bottom", "center", "", "");
                    },
                    error: function (e) {
                        console.log(e);
                    }
                }
            );

        }

        function checkRegisterState() {
            console.log("good2");
            return;
            FB.getLoginStatus(function (response) {
                console.log(response);
                console.log(response.authResponse.accessToken);
                FB.api('/me', {fields: 'name'}, function (response) {
                    console.log('Success ');
                    console.log(response);
                    sendToServerCreateAccount(response.id + "@facebook.com", response.id, "facebook", response.name);
                });
            });
        }
    }

</script>
<script type="text/javascript">
    $(document).ready(function () {

        $("#commerical_number").change(function () {//to reset status_view and commerical_number status
            $('.status_view').append('');
            $("#saveShop").prop('disabled', true);
            // $('#commerical_msg').append('');
            $("#commerical_msg").empty();
            $('#commerical_msg').append('يجب التحقق من السجل التجاري اولاً');

        });

        $(".chosen-select").chosen({
            no_results_text: "Oops, nothing found!"
        })
        $("#mapdiv").hide();
        $("#malldiv").hide();
        $("input[name$='addressType']").click(function () {
            var test = ($(this).val());
            if (test == 1) {
                $("#mapdiv").hide();
                $("#malldiv").show();
            } else {
                $("#mapdiv").show();
                $("#malldiv").hide();
            }

        });


        $('#verification_btn').on('click', function () {
            var commerical_number = document.getElementById("commerical_number").value;

            if (commerical_number == "")
                return showNotification("pastel-danger", "", "bottom", "center", "", "");  // Please enter commerical_number
            $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    type: 'POST',
                    url: '{{ url("checkCommerical") }}',
                    data: {
                        commerical_number: commerical_number
                    },
                    success: function (response) {
                        // console.log(response);
                        if (response.error == "1")
                            window.location.reload(true);
                        else {
                            var response = JSON.parse(response);
                            // selecting values from response Object
                            var status = response.status;
                            var crName = response.crName;
                            var expiryDate = response.expiryDate;
                            var description = response.description;
                            var location = response.location;

                            // console.log(crName);
                            // console.log(expiryDate);
                            // console.log(description);
                            if (status == "active") {
                                $("#name_ar").val(crName);
                                $("#address").val(location);
                                $("#desc_ar").val(description);
                                $('.status_view').append('<image src="http://nearsouq.com/public/img/valid.png" height="80" width="80">');
                                $("#commerical_msg").empty();
                                $("#saveShop").prop('disabled', false);
                            } else {
                                showNotification("pastel-danger", status, "bottom", "center", "", "");
                                $('.status_view').append('<image src="http://nearsouq.com/public/img/not_valid.png" height="80" width="80">');
                                $("#saveShop").prop('disabled', true);
                                $('#commerical_msg').append('يجب التحقق من السجل التجاري اولاً');
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
    setInputFilter(document.getElementById("tax"), function (value) {
        return /^-?\d*$/.test(value);
    });
    setInputFilter(document.getElementById("phone"), function (value) {
        return /^-?\d*$/.test(value);
    });
    setInputFilter(document.getElementById("mobilephone"), function (value) {
        return /^-?\d*$/.test(value);
    });

</script>



