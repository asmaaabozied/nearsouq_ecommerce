@extends('layouts.dashboard.app')

@section('content')


    <div class="page-wrapper" style="min-height: 422px;">
        <div class="content container-fluid">

            <!-- Page Header -->
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

    <table class="authors-list" id="table5">
        <tr>
            <td>@lang('site.param')</td>
            <td>@lang('site.value')</td>
            <td>@lang('site.type')</td>
        </tr>

        <tr class="candidate">
            <td><input type="text" name="param[]"/></td>
            <td><input type="text" name="value[]"/></td>
            <td><input type="text" name="type[]"/></td>
            <td>
                <a onclick="deleteRow(this)" style="border: none;
                                                    background: transparent;">
                    <i class="far fa-trash-alt me-1 fa-2x delete"></i>
                </a>
            </td>
        </tr>
    </table>
    <a href="#" title="" class="add-author">@lang('site.add')</a>
    </div>

@section('scripts')

    <script src="{{asset('style/app-assets/vendors/data-tables/js/jquery.dataTables.min.js')}}"></script>

    <script
        src="{{asset('style/app-assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('style/app-assets/js/custom/custom-script.js')}}"></script>



    <!-- END THEME  JS-->
    <!-- BEGIN PAGE LEVEL JS-->
    {{--<script src="{{asset('style/app-assets/js/scripts/page-users.js')}}'"></script>--}}
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
    <script src="/vendor/datatables/buttons.server-side.js"></script>




    {!! $dataTable->scripts() !!}


@endsection

