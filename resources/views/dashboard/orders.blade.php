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
                            <li class="breadcrumb-item"><a
                                    href="{{route('dashboard.welcome') }}">@lang('site.dashboard')</a></li>

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
        @include('flash::message')
            <!-- Search Filter -->
            <div class="row" data-select2-id="14">
                <div class="col-md-12">

                    <div class="card">
                        <br>
                        <br>
                        <form action="{{route('dashboard.Transaction.index')}}" method="get">

                            <div class="row">
                                <br>
                                <div class="col-md-3">

                                    <select class="form-control" name="shop_id">
                                        <option disabled selected>@lang('site.shops')</option>
                                        @foreach(\App\Models\Shop::get() as $shop)
                                            <option value="{{$shop->id}}">
                                                {{$shop->name ?? ''}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <input name="start_date" class="form-control" type="date">
                                </div>
                                <div class="col-md-3">
                                    <input name="end_date" class="form-control" type="date">
                                </div>
                                <div class="col-md-2">

                                    <select class="form-control" name="status">
                                        <option disabled selected>@lang('site.status')</option>
                                        <option value="1">
                                            @lang('site.active')
                                        </option>
                                        <option value="0">
                                            @lang('site.inactive')
                                        </option>

                                    </select>
                                </div>
                                <div class="col-md-1">

                                    <button type="submit" class="btn-primary">@lang('site.search')</button>


                                </div>

                            </div>

                        </form>
                        <br>
                        <form action="{{route('dashboard.TransactionFile')}}" method="post"
                              enctype="multipart/form-data"

                        >
                            @csrf

                            <div class="row">
                                <br>
                                <div class="col-md-3">


                                    <input type="file" class="form-control" name="file_name">
                                </div>


                                <div class="col-md-1">

                                    <button type="submit" class="btn-primary status">@lang('site.status')</button>


                                </div>

                            </div>

                            <div class="card-body">

                                {!! $dataTable->table([], true) !!}
                            </div>
                        </form>

                    </div>
                </div>
            </div>


            <!-- /Search Filter -->


        </div>
    </div>

@section('scripts')

    <!--<script src="{{asset('style/app-assets/vendors/data-tables/js/jquery.dataTables.min.js')}}"></script>-->

    <!--<script-->
    <!--    src="{{asset('style/app-assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js')}}"></script>-->
    <!--<script src="{{asset('style/app-assets/js/custom/custom-script.js')}}"></script>-->



    <!-- END THEME  JS-->
    <!-- BEGIN PAGE LEVEL JS-->
    <!--{{--<script src="{{asset('style/app-assets/js/scripts/page-users.js')}}'"></script>--}}-->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
    <script src="/vendor/datatables/buttons.server-side.js"></script>




    {!! $dataTable->scripts() !!}


@endsection

