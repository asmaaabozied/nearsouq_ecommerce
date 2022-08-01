@extends('layouts.dashboard.app')

@push('css')
<style>
    .card-body .dataTable{
        width: 100% !important;
        overflow: scroll !important;
    }
    .dropdown {
  display: inline-block;
  position: relative;
}
.dropdown-content {
    
  display: none;
  position: absolute;
  width: 100%;
  overflow: auto;
  box-shadow: 0px 10px 10px 0px rgba(0,0,0,0.4);
  
}
.dropdown:hover .dropdown-content {
  display: block;
      display: table;
    background-color: #fff;
    z-index: 9;
}
.dropdown-content a {
  display: block;
  color: #000000;
  padding: 5px;
  text-decoration: none;
}
.dropdown-content a:hover {
    
  color: #FFFFFF;
  background-color: #00A4BD;
}
</style>
@endpush
{{--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">--}}
@section('content')


    <div class="page-wrapper" style="min-height: 422px;">
        <div class="content container-fluid">

             <!--Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$title}}</h3>
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
             <!--/Page Header -->
        @include('flash::message')
{{--        @include('dashboard.message')--}}
{{--            @if(flash()->message)--}}
{{--                <div class="{{ flash()->class }}">--}}
{{--                    {{ flash()->message }}--}}
{{--                </div>--}}
{{--        @endif--}}

             <!--Search Filter -->
            <div class="row" data-select2-id="14">
                <div class="col-md-12">
                    <div class="card">



                        <div class="card-body">


                            {!! $dataTable->table([], true) !!}
                        </div>
                    </div>
                </div>
            </div>







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
{{--        $(document).ready(function() {--}}


{{--                $('.delete').click(function (e) {--}}
{{--                    console.log("Tapped Delete button")--}}
{{--                    var that = $(this)--}}
{{--                    e.preventDefault();--}}
{{--                    var n = new Noty({--}}
{{--                        text: "@lang('site.confirm_delete')",--}}
{{--                        type: "error",--}}
{{--                        killer: true,--}}
{{--                        buttons: [--}}
{{--                            Noty.button("@lang('site.yes')", 'btn btn-success mr-2', function () {--}}
{{--                                that.closest('form').submit();--}}
{{--                            }),--}}
{{--                            Noty.button("@lang('site.no')", 'btn btn-primary mr-2', function () {--}}
{{--                                n.close();--}}
{{--                            })--}}
{{--                        ]--}}
{{--                    });--}}
{{--                    n.show();--}}

{{--            });--}}

{{--        });--}}
    </script>


@endsection
