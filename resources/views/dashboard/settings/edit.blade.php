@extends('layouts.dashboard.app')

@section('content')

    <div class="page-wrapper" style="min-height: 422px;" data-select2-id="16">
        <div class="content container-fluid" data-select2-id="15">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">@lang('site.settings')</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{route('dashboard.welcome') }}">@lang('site.dashboard')</li>
                            <li class="breadcrumb-item"><a
                                    hhref="{{ route('dashboard.settings.index') }}"> @lang('site.settings') </a></li>
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
                            <h4 class="card-title">@lang('site.settings')</h4>
                            @include('partials._errors')


                            <form action="{{ route('dashboard.settings.update', $setting->id) }}"
                                  method="post" enctype="multipart/form-data">

                                {{ csrf_field() }}
                                {{ method_field('put') }}

                                <div class="row">
                                    <div class="col-md-6">
                                        <label>@lang('site.param')</label>
                                        <input type="text" name="name_ar" class="form-control" disabled
                                               value="{{ $setting->param }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label>@lang('site.value')</label>
                                        <input
                                            @if(str_contains($setting->value, 'png') || str_contains($setting->value, 'jpg'))
                                            type="file" @else type="text" @endif name="value" class="form-control"
                                               value="{{ $setting->value }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>@lang('site.type')</label>
                                        <select class="form-control" name="status" disabled>
                                            <option value="customer_app" @if($setting->type =='customer_app') selected @endif> @lang('site.customer_app') </option>
                                            <option value="delivery_app" @if($setting->type =='delivery_app') selected @endif> @lang('site.delivery_app') </option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label>@lang('site.status')</label>
                                        <select class="form-control" name="status">
                                            <option value="1" @if($setting->status =='1') selected @endif>@lang('site.yes')</option>
                                            <option value="0" @if($setting->status =='0') selected @endif> @lang('site.no') </option>
                                        </select>
                                    </div>
                                </div>

                                <br>
                                <br>



                                <br>




                                <div class="text-end mt-4">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-warning mr-1"
                                                onclick="history.back();">
                                            <i class="fa fa-backward"></i> @lang('site.back')
                                        </button>
                                        <button type="submit" class="btn btn-primary"><i
                                                class="fa fa-plus"></i>
                                            @lang('site.edit')</button>
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
    <script>


        $("#add").click(function(){
            $("#rows").append('<input type="text" name="price[]">');
        });

    </script>


@endsection


