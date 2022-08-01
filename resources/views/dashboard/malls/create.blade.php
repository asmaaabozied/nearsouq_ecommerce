@extends('layouts.dashboard.app')

@section('content')
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
<div class="page-wrapper" style="min-height: 422px;" data-select2-id="16">
    <div class="content container-fluid" data-select2-id="15">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-title">@lang('site.malls')</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('dashboard.welcome') }}">@lang('site.dashboard')</li>
                        <li class="breadcrumb-item"><a hhref="{{ route('dashboard.malls.index') }}"> @lang('site.malls') </a></li>
                        <li class="breadcrumb-item active"><a> @lang('site.edit') </a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <div class="row" data-select2-id="14">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">@lang('site.malls')</h4>
                        @include('partials._errors')


                        <form action="{{ route('dashboard.malls.store') }}" method="post" enctype="multipart/form-data">

                            {{ csrf_field() }}
                            {{ method_field('post') }}

                            <div class="row">
                                <div class="col-md-6">
                                    <label>@lang('site.ar.name')</label>
                                    <input type="text" name="name_ar" class="form-control"  required >
                                </div>
                                <div class="col-md-6">
                                    <label>@lang('site.en.name')</label>
                                    <input type="text" name="name_en" class="form-control"  required >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>@lang('site.ar.owner_name')</label>
                                    <input type="text" name="owner_name" class="form-control"   >
                                </div>
                                <div class="col-md-6">
                                    <label>@lang('site.phone')</label>
                                    <input type="text" name="owner_phone" class="form-control mobilephone"  id="mobilephone" maxlength="10"
                               size="10"  >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>@lang('site.ar.description')</label>
                                    <input type="text" name="desc_ar" class="form-control"  required >
                                </div>
                                <div class="col-md-6">
                                    <label>@lang('site.en.description')</label>
                                    <input type="text" name="desc_en" class="form-control"  required >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>@lang('site.email')</label>
                                    <input type="email" name="email" class="form-control"   >
                                </div>
                                <div class="col-md-6">
                                    <label>@lang('site.contact_number')</label>
                                    <input type="text" name="contact_number" class="form-control mobilephone"  id="phone" maxlength="10"
                               size="10"  >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>@lang('site.category')</label>
                                    <select class="form-control" name="mall_category_id" required >
                                      <option selected disabled>@lang('site.select')</option>
                                <option value="0" >@lang('site.nodataselect')</option>
                                        @foreach($categories as $category)
                                        <option value="{{$category->id}}" {{ old('category_id') == $category->id ? 'selected' : '' }}> {{$category->name_ar}} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>@lang('site.visible')</label>
                                    <select class="form-control" name="visible">
                                        <option value="1" {{ old('visible') == 1 ? 'selected' : '' }}> yes </option>
                                        <option value="0" {{ old('category_id') == 0 ? 'selected' : '' }}> no </option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>@lang('site.address')</label>
                                    <input type="text" name="address" required class="form-control" value=" {{old('address')}} "  >
                                </div>
                            </div>
                            <div class="row form-group">
                                <label for="name" class="col-sm-3 col-form-label input-label">@lang('site.image')</label>
                                <input type="file" id="mall_image" value="{{ old('image') }}" name="image" name="image" >
                            </div>
                            <div class="col-md-12 col-12 mb-20" id="mapdiv">

                                <input id="searchMapInput" class="mapControls controls" type="text"
                                       placeholder="" name="address2">

                                <div id="map"></div>

                                <ul id="geoData">

                                    <li>@lang('site.latitude'): <span id="lat-span"></span></span> <input value="{{old('latitude')}}" id="lat"
                                                                                                       name="latitude"
                                                                                                       class="latitude">
                                    </li>

                                    <li>@lang('site.longitude'): <span id="lon-span"></span> <input value="{{old('longitude')}}" id="lng"
                                                                                                name="longitude"
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
                    <br>
                    <br>
                    <br>

                    <div class="text-end mt-4">
                        <div class="form-group">
                            <button type="button" class="btn btn-warning mr-1" onclick="history.back();">
                                <i class="fa fa-backward"></i> @lang('site.back')
                            </button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i>
                                @lang('site.add')</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>




@endsection
@section('script')
<script type="text/javascript">

    $("#add").click(function() {
        $("#rows").append('<input type="text" name="price[]">');
    });

</script>


@endsection
