@extends('layouts.dashboard.app')

@section('content')
    <div class="page-wrapper" style="min-height: 422px;" data-select2-id="16">
        <div class="content container-fluid" data-select2-id="15">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">@lang('site.versions')</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{route('dashboard.welcome') }}">@lang('site.dashboard')</li>
                            <li class="breadcrumb-item"><a
                                    hhref="{{ route('dashboard.versions.index') }}"> @lang('site.versions') </a></li>
                            <li class="breadcrumb-item active"><a> @lang('site.add') </a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row" data-select2-id="14">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">@lang('site.versions')</h4>
                            @include('partials._errors')

                            <form action="{{ route('dashboard.versions.store') }}" method="post"
                                  enctype="multipart/form-data"
                            >

                                {{ csrf_field() }}
                                {{ method_field('post') }}

                                <div class="row">
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label>@lang('site.version_no')</label>
                                            <input type="text" name="version_no" class="form-control"
                                                   value="{{ old('version_no') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>@lang('site.build_no')</label>
                                            <input type="text" name="build_no" class="form-control"
                                                   value="{{ old('build_no') }}">
                                        </div>
                                        <div class="form-group">
                                            <label>@lang('site.release_date')</label>
                                            <div id="result">
                                                <input type="date" name="release_date" class="form-control"

                                                       value="{{date('Y-m-d', time())}}"
                                                >

                                            </div>
                                        </div>




                                        <div class="form-group">
                                            <label>@lang('site.expiry_date')</label>
                                            <input type="date" name="expiry_date"
                                                   class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('site.change_log_en')</label>
                                            <!--<input type="text" name="change_log_en" class="form-control"-->
                                            <!--       value="{{ old('change_log_en') }}">-->
                                            <textarea name="change_log_en" class="form-control"></textarea>
                                        </div>

                                    <div class="form-group">

                                    <label>@lang('site.change_log_ar')</label>
                                            <!--<input type="text" name="change_log_ar" class="form-control"-->
                                            <!--       value="{{ old('change_log_ar') }}">-->
                                            
                                            <textarea name="change_log_ar" class="form-control"></textarea>
                                        </div>


                                    <div class="form-group">

                                    <label>@lang('site.os')</label>
                                          
                                                   
                                     <select class="form-control" name="os">
                                            <option>@lang('site.select')</option>
                                            <option value="iphone"> @lang('site.iphone') </option>
                                            <option value="android"> @lang('site.android') </option>
                                            <option value="huawei"> @lang('site.huawei') </option>
                                   

                                        </select>
                                        </div>

                                    <div class="form-group">

                                            <label>@lang('site.type')</label>


                                        <select class="form-control" name="type">
                                            <option>@lang('site.select')</option>
                                            <option value="customer_app"> @lang('site.customer_app') </option>
                                            <option value="delivery_app"> @lang('site.delivery_app') </option>
                                            <option value="vendor_app"> @lang('site.vendor_app') </option>
                                            <option value="web"> @lang('site.web') </option>

                                        </select>


                                        </div>



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
