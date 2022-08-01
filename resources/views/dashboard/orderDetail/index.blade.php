@extends('layouts.dashboard.app')

@push('css')



        <style>
         .card-body .dataTable{
             width: 100% !important;
             overflow: scroll !important;
             font-size: small !important;
             font-weight: 600;
         }
    </style>
@endpush
@section('content')

<div class="modal" tabindex="-1" role="dialog" id="not_delivered_reason">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">@lang('site.cancel_reason')</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <select name="reason" id="reason" style="width: 100%;text-align: center;">
            @foreach($reasons as $reason)
               <option value="{{$reason->id}}"> {{$reason->name}} </option>
            @endforeach
        </select>
      </div>
      <div class="modal-footer">
        
      </div>
    </div>
  </div>
</div>

<div class="page-wrapper" style="min-height: 422px;">
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">

                    <h3 class="page-title">{{$title}}</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <table style="width:100%;font-size: small;font-weight: 600;">
                                <tbody>
                                    <tr>
                                        <th>@lang('site.username')</th>
                                        <td> {{$order->name}}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('site.phone')</th>
                                        <td>{{$order->phone}}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('site.address')</th>
                                        <td>{{$order->street}},&nbsp;{{$order->city}},&nbsp;{{$order->country}}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('site.payment_status')</th>
                                        <td>
                                            @if($order->payment_status == "PAID")
                                            @lang('site.paid')
                                            @else
                                            @lang('site.not_paid')
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>@lang('site.bill_number')</th>
                                        <td>{{$order->bill_number}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table style="width:100%">
                                <tbody>
                                    <tr>
                                        <th>@lang('site.payment_type')</th>
                                        <td>@if(app()->getLocale() == "ar")
                                            {{$order->name_ar}}
                                            @else
                                            {{$order->name_en}}
                                            @endif</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('site.delivery_cost')</th>
                                        <td>{{$order->delivery_cost}}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('site.delivery_distance_in_km')</th>
                                        <td>{{$order->delivery_distance_in_km}}</td>
                                    </tr>
                                    @if(isset($order->capon_id))
                                    <tr>
                                        <th>@lang('site.capon_id')</th>
                                        <td> {{$order->capon_id}} </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>@lang('site.created_at')</th>
                                        <td>{{$order->created_at}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('dashboard.welcome') }}">@lang('site.dashboard')</a></li>

                        <li class="breadcrumb-item active">{{$title}}({{$count}})</li>
                    </ul>
                </div>
                <div class="col-auto">
                    <a href="{{route('dashboard.'.$model.'.create')}}" class="btn btn-primary me-1">
                        <i class="fas fa-plus"></i>
                    </a>
                    <a class="btn btn-primary filter-btn" href="javascript:void(0);" id="filter_search">
                        <i class="fas fa-filter"></i>
                    </a>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <!-- Search Filter -->
        <div class="row" data-select2-id="14">
            <div class="col-md-12">
                <div class="card">



                    <div class="card-body">

                        {!! $dataTable->table([], true) !!}
                    </div>
                </div>
            </div>
        </div>




        <!-- /Search Filter -->





    </div>
</div>

@section('scripts')

    <script>

        $(document).ready(function(){
            // $(".alert").delay(5000).slideUp(300);
            $(".alert").slideDown(300).delay(5000).slideUp(300);
        });
        setTimeout(function() {
            $('.alert-box').remove();
        }, 30000);

    </script>
{{--    <script type="text/javascript">--}}
{{--        $('.flash-message.alert').not('.alert-important').delay(5000).slideUp(350);--}}
{{--    </script>--}}

<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
<script src="/vendor/datatables/buttons.server-side.js"></script>

<script>

    $(document).ready(function() {
        jQuery('.delete').click(function (event) {
            event.preventDefault();


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



{!! $dataTable->scripts() !!}

  <script>

      function confirmDelete($id){
         console.log("Tapped Delete button")
         var that = document.getElementById("deleteForm"+$id);
         var n = new Noty({
            text: "@lang('site.confirm_delete')",
              type: "error",
            killer: true,
             buttons: [
                   Noty.button("@lang('site.yes')", 'btn btn-success mr-2', function () {
                      that.submit();
                 }),
                  Noty.button("@lang('site.no')", 'btn btn-primary mr-2', function () {
                       n.close();
                 })
             ]
          });
         n.show();
       }
      $(document).ready(function() {

$("#deleteForm").on('click', "#delete", function(e) {
             
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


function do_something($id){
    console.log($id);
        var opval = $('#status'+$id).val(); //Get value from select element
        console.log(opval);
        var myform = $("#status"+$id).closest("form");
        if(opval === "NOT_DELIVERED"){ //Compare it and if true
            $('#not_delivered_reason').modal("show"); //Open Modal
            $('#reason').change(function() {
               $('#reason_id').val($('#reason').val()) ;
                $('#status'+$id).closest("form").submit();
            });
        }else{
            $('#status'+$id).closest("form").submit();
        }
}
</script>


@endsection

