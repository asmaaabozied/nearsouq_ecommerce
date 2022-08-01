@extends('layouts.dashboard.app')

@push('css')
    <style>
        .card-body .dataTable {
            width: 100% !important;
            overflow: scroll !important;
        }
    </style>
@endpush
@section('content')


    <div class="page-wrapper" style="min-height: 422px;">
        <div class="content container-fluid">

            <!--Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">{{$title}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{route('dashboard.welcome') }}">@lang('site.dashboard')</a></li>

                            <li class="breadcrumb-item active">{{$title}}</li>


                        </ul>
                        <div class="row">


                            <div class="col-lg-4">@lang('site.total')({{$count}})</div>

                            <div class="col-lg-4" >@lang('site.Total last month')({{$countmonth}})</div>

                        </div>
                    </div>

                </div>
            </div>

            <form action="{{route('dashboard.'.$route)}}" method="get">

                <div class="page-header">
                    
                      <div class="row align-items-center">
                           <div class="col-lg-1">
                               </div>
                        <div class="col-lg-9">

                           
<select class="form-control product-select">
    <option selected disabled>@lang('site.select')</option>
  <option value={{url('dashboard/ProductsReports?status=1')}}>
     @lang('site.Most Wanted Products')
    </option>
    
        <option value={{url('dashboard/ProductsReports?status=0')}}>
            
        @lang('site.Products are about to expire')
    </option>
</select>

                        </div>
                   


                    </div>
                    <div class="row align-items-center">
                        <div class="col-lg-5">

                            <label>@lang('site.start_date')</label>
                            <input type="date" class="form-control" name="start_date" required>

                        </div>
                        <div class="col-lg-5">

                            <label>@lang('site.end_date')</label>
                            <input type="date" class="form-control" name="end_date" required>

                        </div>

                        <div class="col-lg-1">


                            <button type="submit" class="btn-primary form-control">
                                @lang('site.search')
                            </button>

                        </div>

                    </div>
                </div>


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
    
    
    $('.product-select').on('change', function (e) {
        e.preventDefault();
        var item = $(this).val();
        window.location.href = item;
    });

        $(document).ready(function () {
            // $(".alert").delay(5000).slideUp(300);
            $(".alert").slideDown(300).delay(5000).slideUp(300);
        });
        setTimeout(function () {
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

        $(document).ready(function () {
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

        function confirmDelete($id) {
            console.log("Tapped Delete button")
            var that = document.getElementById("deleteForm" + $id);
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
