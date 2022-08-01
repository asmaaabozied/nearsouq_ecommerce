@extends('layouts.dashboard.app')

@section('content')
    <div class="page-wrapper" style="min-height: 422px;" data-select2-id="16">
        <div class="content container-fluid" data-select2-id="15">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">@lang('site.users')</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{route('dashboard.welcome') }}">@lang('site.dashboard')</li>
                            <li class="breadcrumb-item"><a
                                    hhref="{{ route('dashboard.users.index') }}"> @lang('site.users') </a></li>
                            <li class="breadcrumb-item active"><a> @lang('site.show') </a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row" data-select2-id="14">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">@lang('site.show') @lang('site.shops')</h4>
                            @include('partials._errors')

                            <form action="{{ route('dashboard.SaveShopUser') }}" method="post"
                                  enctype="multipart/form-data">

                                {{ csrf_field() }}
                                {{ method_field('post') }}

                                <div class="row">


                                    <div class="col-md-6">
                                        @foreach($shops as $shop)
                                            <br>

                                            <input type="checkbox" name="shop_id[]" value="{{$shop->id}}">
                                            <span>  {{$shop->name ?? ''}}</span>

                                        @endforeach


                                    </div>
                                </div>


                                <div class="text-end mt-4">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-warning mr-1"
                                                onclick="history.back();">
                                            <i class="fa fa-backward"></i> @lang('site.back')
                                        </button>
                                        <button type="submit" class="btn btn-primary"><i
                                                class="fa fa-plus"></i>
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
