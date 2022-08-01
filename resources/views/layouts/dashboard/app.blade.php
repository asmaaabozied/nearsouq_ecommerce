<!DOCTYPE html>


<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" name="csrf-token"
          content="{{ csrf_token() }}">

    <title>@lang('site.nearsouq')</title>
    {{--noty--}}

    <link rel="stylesheet" href="{{ asset('dashboard_files/plugins/noty/noty.css') }}">
    <script src="{{ asset('dashboard_files/plugins/noty/noty.min.js') }}"></script>

    {{--morris--}}
    <link rel="stylesheet" href="{{ asset('dashboard_files/plugins/morris/morris.css') }}">
    <link rel="shortcut icon" href="{{asset('frontend/assets/img/favicon.png')}}">

    <link rel="stylesheet" href="{{asset('frontend/assets/plugins/datatables/datatables.min.css')}}">
    @if(app()->getLocale()=='ar')

    <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="{{asset('frontend/asset_rtl/css/bootstrap.rtl.min.css')}}">


        <!-- Main CSS -->
        <link rel="stylesheet" href="{{asset('frontend/asset_rtl/css/style.css')}}">



<!--//bnews-->
<!-- <script src="{{ asset('dashboard_files/js/jquery.min.js') }}"></script>-->

        <!-- Main CSS -->
    @else

    <!-- Bootstrap CSS -->

        <link rel="stylesheet" href="{{asset('frontend/assets/css/style.css')}}">
        <link rel="stylesheet" href="{{asset('frontend/assets/css/bootstrap.min.css')}}">
        <script src="{{asset('frontend/assets/js/html5shiv.min.js')}}"></script>
        <script src="{{asset('frontend/assets/js/respond.min.js')}}"></script>

        <!--<script src="{{ asset('dashboard_files/js/jquery.min.js') }}"></script>-->
        <!--<script src="{{ asset('dashboard_files/js/select2.min.js') }}"></script>-->

        {{--noty--}}
        <!--<link rel="stylesheet" href="{{ asset('dashboard_files/plugins/noty/noty.css') }}">-->
        <!--<script src="{{ asset('dashboard_files/plugins/noty/noty.min.js') }}"></script>-->

        {{--morris--}}
        <!--<link rel="stylesheet" href="{{ asset('dashboard_files/plugins/morris/morris.css') }}">-->


        		<!--<link rel="shortcut icon" href="{{asset('frontend/assets/img/favicon.png')}}">-->

		<!-- Bootstrap CSS -->
		<!--<link rel="stylesheet" href="{{asset('frontend/assets/css/bootstrap.min.css')}}">-->

		<!-- Fontawesome CSS -->
		<link rel="stylesheet" href="{{asset('frontend/assets/plugins/fontawesome/css/fontawesome.min.css')}}">
		<link rel="stylesheet" href="{{asset('frontend/assets/plugins/fontawesome/css/all.min.css')}}">

		<!-- Datatables CSS -->
		<link rel="stylesheet" href="{{asset('frontend/assets/plugins/datatables/datatables.min.css')}}">

		<!-- Main CSS -->
		<link rel="stylesheet" href="{{asset('frontend/assets/css/style.css')}}">

		<!--[if lt IE 9]>
			<script src="assets/js/html5shiv.min.js"></script>
			<script src="assets/js/respond.min.js"></script>
		<![endif]-->


    @endif
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    <link rel="stylesheet" href="{{asset('frontend/assets/plugins/select2/css/select2.min.css')}}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{asset('frontend/assets/plugins/fontawesome/css/fontawesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('frontend/assets/plugins/fontawesome/css/all.min.css')}}">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    @stack('css')
    @yield('css')
    <style>
    .card-body .dataTable{
        width: 100% !important;
        overflow: scroll !important;
    }
</style>

<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
<script>
  window.OneSignal = window.OneSignal || [];
  OneSignal.push(function() {
    OneSignal.init({
      appId: "bfade32e-b06a-4f8f-9845-86a9d8c8b0ad",
    });
  });
</script>

</head>


<body>

<!-- Main Wrapper -->
<div class="main-wrapper">

    <!-- Header -->
    <div class="header">

        <!-- Logo -->
        <div class="header-left">
            <a  class="logo">
                <img src="{{asset('frontend/assets/img/logo.png')}}" alt="Logo" onclick="window.location.href='/dashboard';">
            </a>
            <a  class="logo logo-small">
                <img src="{{asset('frontend/assets/img/logo-small.png')}}" alt="Logo" width="30" height="30" onclick="window.location.href='/dashboard';">
            </a>
        </div>
        <!-- /Logo -->

        <!-- Sidebar Toggle -->
        <a href="javascript:void(0);" id="toggle_btn">
            <i class="fas fa-bars"></i>
        </a>
        <!-- /Sidebar Toggle -->

        <!-- Search -->
        <div class="top-nav-search">
            <form>
                <input type="text" class="form-control" placeholder="Search here">
                <button class="btn" type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <!-- /Search -->

        <!-- Mobile Menu Toggle -->
        <a class="mobile_btn" id="mobile_btn">
            <i class="fas fa-bars"></i>
        </a>
        <!-- /Mobile Menu Toggle -->

        <!-- Header Menu -->
        <ul class="nav nav-tabs user-menu">
            <!-- Flag -->
            <li class="nav-item dropdown has-arrow main-drop">

                <a class="nav-link dropdown-toggle"  id="menu-buttonsss" data-bs-toggle="dropdown"  role="button">
                    <img src="{{asset('frontend/assets/img/flags/sa.png')}}" alt="" height="20"> <span>@lang('site.Arabic')</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right"  id="mob-menu">


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
            <!-- Notifications -->
					<li class="nav-item dropdown">
						<a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown"   id="notify" @click="readNotification()">
			
							    <i class="fa fa-bell" ></i> @if(session('notification_count') > 0)<span class="badge rounded-pill" id="noti_count">{{session('notification_count')}}</span> @endif
							   	<a href="{{route('dashboard.changenotificationsstatus')}}"> 
							</a>
						</a>
						<div class="dropdown-menu notifications">
							<div class="topnav-dropdown-header">
								<span class="notification-title">@lang('site.notifications')</span>
								<a href="javascript:readNotification()" class="clear-noti"> Clear All</a>
							</div>
							<div class="noti-content">
								<ul class="notification-list">
								    @if(session('notifications'))
								    <!--<?php  $data ?>-->
                                @foreach(session('notifications') as $notification)
                                  <!--<?php  $data=$notification->product->name ?? '' ?>-->
									<li class="notification-message"
                                    @if($notification->read == 0) style="background-color:aliceblue" @endif>
										 
										       @if($notification->type === 'ORDER')
											<div class="media d-flex">
												
												<div class="media-body" style="width:80%">
													<p class="noti-details"><span class="noti-title">@lang($notification->title)</span> @lang($notification->message) </p>
													<p class="noti-time"><span class="notification-time">{{$notification->created_at}}</span></p>
												</div>
                                           
                                                <span class="avatar avatar-sm" style="text-align:end">
													<button type="button" class="btn btn-primary" onclick="window.location.href='{{url('dashboard/order/details/'.$notification->order_id)}}'">@lang('site.show')</button>
												</span>
                                                
                                                
                                                
                                                   
                                                       
                                
                                             
											</div>
											
									    @elseif($notification->type === 'NOTIFY')
									      @if(auth()->user()->hasPermission('read_notificationproducts'))
									    	<div class="media d-flex">
												 <!--@lang($notification->message)-->
												<div class="media-body" style="width:80%">
													<p class="noti-details"><span class="noti-title">@lang($notification->title)</span>    {{$notification->product->name ?? ''}}
													<!--@if($data=$notification->product->name)-->
													
													<!--@else-->
													
													@lang('site.finishedproduct')
													
													<!--@endif-->
													</p>
													<p class="noti-time"><span class="notification-time">{{$notification->created_at}}</span></p>
												</div>
                                           
                                        
                                                
                                                
                                                   
                                                       
                                                        
                                                <span class="avatar avatar-sm" style="text-align:end">
													<button type="button" class="btn btn-primary" onclick="window.location.href='{{url('dashboard/products/'.$notification->order_id.'/edit')}}'">@lang('site.show')</button>
												</span>
												
                                           
											</div>
									    
										     @endif
										      @endif
									</li>
                                    @endforeach
                                    @endif
								</ul>
							</div>
							<!--<div class="topnav-dropdown-footer">
								<a href="activities.html">View all Notifications</a>
							</div>-->
						</div>
					</li>
					<!-- /Notifications -->
        @if (auth()->user())


        @if (auth()->user()->hasRole('Vendor'))

            <!-- shops -->

                <li class="nav-item dropdown has-arrow main-drop">
                    <select class="dropdown-toggle nav-link shop_change" data-bs-toggle="dropdown">
                        @foreach(auth()->user()->shops as $shop)


                            <option class="dropdown-item" value="{{route('dashboard.updateshopsession',$shop->id)}}" {{ Session::has('shop_id') ? ( Session::get('shop_id') == $shop->id ? 'selected' : '' ) : ''}} >

                                {{ $shop->brand_name }}
                            </option>

                        @endforeach
                    </select>
                </li>

                <!-- /shops -->

        @endif
  @endif

        <!-- User Menu -->
            <li class="nav-item dropdown has-arrow main-drop">
                <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
							<span class="user-img">
							     @if (auth()->user())
								<img src="{{asset('uploads/'.auth()->user()->image)}}" alt="">
								  @endif
								<span class="status online"></span>
							</span>
                    <span>{{auth()->user()->username ?? ''}}</span>
                </a>
                <div class="dropdown-menu">
                    @if (auth()->user())
                    <a class="dropdown-item" href="{{route('dashboard.users.edit',auth()->user()->id)}}"><i
                            data-feather="user" class="me-1"></i> @lang('site.Profile')</a>

                    @if (auth()->user()->hasPermission('read_roles'))
                        <a class="dropdown-item" href="{{route('dashboard.roles.index')}}"><i  data-feather="users"
                                                                                              class="me-1"></i> @lang('site.roles')
                        </a>
                    @endif
                    <a class="dropdown-item" href="{{route('dashboard.logout')}}"><i data-feather="log-out"
                                                                                     class="me-1"></i> @lang('site.logout')
                    </a>
                       @endif
                </div>
            </li>

            <!-- /User Menu -->

        </ul>
        <!-- /Header Menu -->



<!--        <ul class="nav nav-tabs user-menu">-->
					<!-- Flag -->
<!--					<li class="nav-item dropdown has-arrow flag-nav">-->
<!--						   <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button">-->
<!--                    <img src="{{asset('frontend/assets/img/flags/sa.png')}}" alt="" height="20"> <span>@lang('site.Arabic')</span>-->
<!--                </a>-->
<!--					     <div class="dropdown-menu dropdown-menu-right">-->


<!--                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)-->
<!--@if($properties['native']=='English')-->

<!--                        <a class="dropdown-item" hreflang="{{ $localeCode }}"-->
<!--                           href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">-->

<!--<img src="{{asset('frontend/assets/img/flags/fr.png')}}" alt="" height="16">-->




                            <!--<img src="{{asset('frontend/assets/img/flags/sa.png')}}" alt="" height="16">-->



<!--                            <span>{{ $properties['native'] }}</span>-->
<!--                        </a>-->
<!--                        @else-->


<!--                                        <a class="dropdown-item" hreflang="{{ $localeCode }}"-->
<!--                           href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">-->





<!--                            <img src="{{asset('frontend/assets/img/flags/sa.png')}}" alt="" height="16">-->



<!--                            <span>{{ $properties['native'] }}</span>-->
<!--                        </a>-->


<!--                        @endif-->

<!--                    @endforeach-->

<!--                </div>-->
<!--					</li>-->
<!--</ul>-->



    </div>
    <!-- /Header -->

    @include('layouts.dashboard.aside')
{{--    @include('dashboard.message')--}}
{{--    @include('sweetalert::alert')--}}

    @yield('content')


</div>

<!-- jQuery -->
<!--<script src="{{asset('frontend/assets/js/jquery-3.6.0.min.js')}}"></script>-->

<!-- Bootstrap Core JS -->
<script src="{{asset('frontend/assets/js/popper.min.js')}}"></script>
<script src="{{asset('frontend/assets/js/bootstrap.min.js')}}"></script>

<!-- Feather Icon JS -->
<script src="{{asset('frontend/assets/js/feather.min.js')}}"></script>

 <!--Slimscroll JS -->
<script src="{{asset('frontend/assets/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>

 <!--Chart JS -->
<script src="{{asset('frontend/assets/plugins/apexchart/apexcharts.min.js')}}"></script>
<script src="{{asset('frontend/assets/plugins/apexchart/chart-data.js')}}"></script>


<!-- Datatables CSS -->
<link rel="stylesheet" href="{{asset('frontend/assets/plugins/datatables/datatables.min.css')}}" >

<!-- Custom JS -->
{{--<script src="{{asset('frontend/assets/js/script.js')}}"></script>--}}
<!--<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>-->

{{--<script src="{{asset('frontend/assets/js/jquery-3.6.0.min.js')}}"></script>--}}




		<!--//nnnewss-->
		<!-- Bootstrap Core JS -->
		<script src="{{asset('frontend/assets/js/popper.min.js')}}"></script>
		<!--<script src="{{asset('frontend/assets/js/bootstrap.min.js')}}"></script>-->

		 <!--Feather Icon JS -->
		<script src="{{asset('frontend/assets/js/feather.min.js')}}"></script>

		 <!--Slimscroll JS -->
		<script src="{{asset('frontend/assets/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>

		 <!--Datatables JS -->
		<script src="{{asset('frontend/assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
		<script src="{{asset('frontend/assets/plugins/datatables/datatables.min.js')}}"></script>

		 <!-- JS -->
		<script src="{{asset('frontend/assets/js/script.js')}}"></script>
				<!--//nnnewss-->

{{--<script type="text/javascript"--}}
{{--        src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.22/b-1.6.4/b-colvis-1.6.4/b-flash-1.6.4/b-html5-1.6.4/b-print-1.6.4/datatables.min.js"></script>--}}


{{--<script type="text/javascript">--}}
{{--    $(".js-example-basic-multiple").select2();--}}
{{--</script>--}}



    // <script>
    //   $(document).ready(function() {

    //     // create MultiSelect from select HTML element
    //     var required = $("#required").kendoMultiSelect({
    //       select: function(e) {
    //         var dataItem = this.dataSource.view()[e.item.index()];
    //         var values = this.value();

    //         if (dataItem.value === "ALL") {
    //             this.value(values.includes("ALL") ? "ALL" : "");
    //         } else if (values.indexOf("ALL") !== -1) {
    //           values = $.grep(values, function(value) {
    //             return value !== "ALL";
    //           });

    //           this.value(values.length === this.dataSource.view().length -1 ? "" : values);
    //         }
    //       }
    //     }).data("kendoMultiSelect");
    //   });
    </script>

<script>


    $(document).ready(function(){
        // $(".alert").delay(5000).slideUp(300);
        $(".alert").slideDown(300).delay(5000).slideUp(300);
    });
    setTimeout(function() {
        $('.alert-box').remove();
    }, 30000);

</script>
<script type="text/javascript">
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

 setInputFilter(document.getElementById("mobilephone"), function (value) {
        return /^-?\d*$/.test(value);
    });
     setInputFilter(document.getElementById("phone"), function (value) {
        return /^-?\d*$/.test(value);
    });

    //  setInputFilter(document.getElementById("discount"), function (value) {
    //     return /0\d*$/0.test(value);
    // });

    $(document).ready(function(){
        jQuery('a.add-author-product').click(function(event){
            event.preventDefault();
            var newRow = jQuery('<tr class="candidate"><td>' +
                '<input type="text" name="vname_ar[]"/>' +
                '</td><td>' +
                '<input type="text" name="vname_en[]"/>' +
                '</td><td>' +
                '<input type="text" name="reference_name[]"/>' +
                '</td><td>' +
            '<input type="file" name="photo[]" class="form-control"/>' +
            '</td>' +
                '<td>' +
                ' <a onclick="deleteRow(this)">' +
                '<i class="far fa-trash-alt me-1 fa-2x delete"></i>' +
                '</a>' +
                '</td>' +
                '</tr>');
            jQuery('table.authors-list-product').append(newRow);
        });
    });
</script>

<script>
    function addShopQuantity() {
        var shop_id = $("#shops").val();
        var quantity = $("#input_quantity").val();



        var shop_name = $("#shops option:selected").html();


//   alert(shop_name);
//   return;
        if (quantity == "") {
            $("#input_quantity").addClass("red-border");
        } else {

            // if ($('#table5 tr:contains("' + cat +'")').length > 0) {
            //   alert("found duplicate values");
            // } else {
            if ($('#table5 tr:contains("' + shop_id +'")').length > 0) {
                alert("found duplicate values");
            } else {
                var markup =
                    "<tr>" +
                    "<td>" +
                    shop_name +
                    "</td>"+
                    "<input type='hidden' name='shop_id[]' value="+shop_id+"> " +
                    // shop_id +
                    "<td>" +


                    "<input type='text' name='quantity[]' value="+quantity+"> " +
                    // amt +

                    "</td>" +
                    '<td>' +
                    ' <a onclick="deleteRow(this)">' +
                    '<i class="far fa-trash-alt me-1 fa-2x delete"></i>' +
                    '</a>' +
                    '</td>' +

                    "</tr>";
                $("#table5 tbody").append(markup);
                $("#shops option:selected").hide();
            }
        }

    }

</script>

<script>

    function deleteRow(r) {
        var i = r.parentNode.parentNode.rowIndex;
        document.getElementById("table5").deleteRow(i);
    }



</script>
@yield('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>


    $('.shop_change').on('change', function (e) {
        e.preventDefault();
        var item = $(this).val();

        var n = new Noty({
            text: "@lang('site.confirm_remove')",
            type: "error",
            killer: true,
            buttons: [
                Noty.button("@lang('site.yes')", 'btn btn-success mr-2', function () {
                    window.location.href = item;
                }),
                Noty.button("@lang('site.no')", 'btn btn-primary mr-2', function () {
                    n.close();
                })
            ]
        });
        n.show();



    });
    // $('.delete').on('click', function (e) {
    //     console.log("Tapped Delete button")
    //     var that = $(this)
    //     e.preventDefault();

    //     var n = new Noty({
    //         text: "@lang('site.confirm_delete')",
    //         type: "error",
    //         killer: true,
    //         buttons: [
    //             Noty.button("@lang('site.yes')", 'btn btn-success mr-2', function () {
    //                 that.closest('form').submit();
    //             }),
    //             Noty.button("@lang('site.no')", 'btn btn-primary mr-2', function () {
    //                 n.close();
    //             })
    //         ]
    //     });

    //     n.show();



    // });


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

<script>
    var myTable = null;

    function drawTableCallback(e) {
        //delete
        $('.update').click(function (e) {
            var that = $(this)
            e.preventDefault();
            var n = new Noty({
                text: "@lang('site.confirm_update')",
                type: "warning",
                killer: true,
                buttons: [
                    Noty.button("@lang('site.yes')", 'btn btn-success mr-2', function () {
                        that.closest('form').submit();
                    }),
                    Noty.button("@lang('site.no')", 'btn btn-primary mr-2', function () {
                        n.close();
                    })
                ]
            });
            n.show();
        });

    }


    $(document).ready(function () {

        $('#delete').click(function (e) {
            console.log("Tapped Delete button")
            var that = $(this)
            e.preventDefault();
            var n = new Noty({
                text: "@lang('site.confirm_delete')",
                type: "error",
                killer: true,
                buttons: [
                    Noty.button("@lang('site.yes')", 'btn btn-success mr-2', function () {
                        that.closest('form').submit();
                    }),
                    Noty.button("@lang('site.no')", 'btn btn-primary mr-2', function () {
                        n.close();
                    })
                ]
            });
            n.show();
        });//end of delete
    })
    $(document).ready(function () {

        $('.status').click(function (e) {
            console.log("Tapped Delete button")
            var that = $(this)
            e.preventDefault();
            var n = new Noty({
                text: "@lang('site.confirm_status')",
                type: "error",
                killer: true,
                buttons: [
                    Noty.button("@lang('site.yes')", 'btn btn-success mr-2', function () {
                        that.closest('form').submit();
                    }),
                    Noty.button("@lang('site.no')", 'btn btn-primary mr-2', function () {
                        n.close();
                    })
                ]
            });
            n.show();
        });

    })

    $(function () {
        $('#delete').click(function (e) {
            console.log("Tapped Delete button")
            var that = $(this)
            e.preventDefault();
            var n = new Noty({
                text: "@lang('site.confirm_delete')",
                type: "error",
                killer: true,
                buttons: [
                    Noty.button("@lang('site.yes')", 'btn btn-success mr-2', function () {
                        that.closest('form').submit();
                    }),
                    Noty.button("@lang('site.no')", 'btn btn-primary mr-2', function () {
                        n.close();
                    })
                ]
            });
            n.show();
        });
    });

</script>
<script>
// $('#reportshops-table').DataTable( {
//     buttons: [
//         'pdf'
//     ]
// } );

    $("body").on("change", ".img-item input", function() {
        readURL(this);
    })

    function readURL(input) {
        var img = $(input).closest(".img-item").find("img");
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $(img).attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    function readNotification(){
    $.ajax({
        url: "/ar/dashboard/readNotification",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        method: 'get',
        success: function (data) {
            document.getElementById('noti_count').style.display = 'none';
        }
    });
    }
</script>

<script>
    OneSignal.push(function() {
      /* These examples are all valid */
      var isPushSupported = OneSignal.isPushNotificationsSupported();
      if (isPushSupported) {
        OneSignal.isPushNotificationsEnabled(function(isEnabled) {
        if (isEnabled){
          console.log("Push notifications are enabled!");
          OneSignal.getUserId(function(userId) {
            OneSignal.getUserId(function(userId) {
                console.log(userId);
        $.ajax({
        url: "/ar/dashboard/saveOneSigalId",
        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}"},
        method: 'post',
        data: {
            oneSignalID: userId,
        },
        success: function (data) {
            console.log(data);
        }
    });
      });
          });
        }
        else{
          console.log("Push notifications are not enabled yet.");
          OneSignal.push(function() {
            OneSignal.showSlidedownPrompt();
          });
          }
      });
      } else {
        // Push notifications are not supported
      }
    });
    /*****************Auth keys**********************/
    // dba090555875e826
    // b08174da0a9780ec
    // 94411b27d08f6279
    // 54cbd575636f6b4b
    // b97f68fe66998c2a
    // b95f8ffc662e25b9
    // 1856b28fa3e78957
    // c8ce3dcecba77053
    // b437f58f9c51b883
    // 641ee4b3ae466e9e
    /************************************************/
    </script>

<!--<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>-->
<!--    <script src="https://cdn.jsdelivr.net/npm/smartwizard@5/dist/js/jquery.smartWizard.min.js" type="text/javascript"></script>-->
<!--  <link href="https://cdn.jsdelivr.net/npm/smartwizard@5/dist/css/smart_wizard_all.min.css" rel="stylesheet" type="text/css" /> -->

<!--         <script>-->
<!--            $(document).ready(function(){-->
<!--// SmartWizard initialize-->
<!--            $('#smartwizard').smartWizard();-->

<!--          });-->
<!--    </script>-->



<script type="text/javascript">

 $.ajaxSetup({
        headers: {
                     'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }
    });

    $(".dropdown-toggle").click(function(e){

        e.preventDefault();

        var data = 'True';
        console.log('daaaa');
         

        console.log(data);
        var url = '{{route('dashboard.changenotificationsstatus')}}';

        $.ajax({
           url:url,
           method:'POST',
        enctype: 'multipart/form-data',
           data:data,
           success:function(response){
          
              if(response.success){
                    // window.location.href ='{{route('dashboard.products.index')}}';
                  
    
              }else{
                  alert("Error")
              }
           },
           error:function(result){
              console.log(result)
                //   var errors = result.responseJSON;
                //     var errorsList = "";
                //     $.each(errors, function (_, value) {
                //         $.each(value, function (_, fieldErrors) {
                //             fieldErrors.forEach(function (error) {
                //                 errorsList += "<span style='color:red'>" + error + "<br></span>";
                //             })
                //         });
                //     });
                //     $('#signup_errors').html(errorsList).style(' background-color: antiquewhit');
           }
        });
	});

</script>


</body>
</html>

