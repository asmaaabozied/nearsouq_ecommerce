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
                            <li class="breadcrumb-item"><a href="{{route('dashboard.welcome') }}">@lang('site.dashboard')</li>
                            <li class="breadcrumb-item"><a hhref="{{ route('dashboard.settings.index') }}"> @lang('site.settings') </a></li>
                            <li class="breadcrumb-item active"><a> @lang('site.create') </a></li>
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


                            <form action="{{ route('dashboard.settings.store') }}" method="post" enctype="multipart/form-data">

                                {{ csrf_field() }}
                                {{ method_field('post') }}

                                <div class="row">
                                    <div class="col-md-12">
                                        <label>@lang('site.param')</label>
                                        <input type="text" name="param" class="form-control" value=" {{old('param')}} ">
                                    </div>
                                    <div class="col-md-12">
                                        <label>@lang('site.value')</label>
                                        <div class="row">

                                        <label for="textInput" class="radio-inline col-md-6">
                                            <input type="radio" id="textInput" name="valueRadio" onchange="valueType(1)">@lang('site.text')
                                        </label>

                                        <label for="imageInput" class="radio-inline col-md-6">
                                            <input type="radio" id="imageInput" name="valueRadio" onchange="valueType(2)">@lang('site.image')
                                        </label>
                                        </div>
                                        <input type="text" name="valueText" class="form-control" value=" {{old('value')}}" id="text" style="display:none">

                                        <input type="file" name="valueImage" class="form-control" value=" {{old('value')}}" id="image" style="display:none">

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>@lang('site.type')</label>
                                        <select class="form-control" name="type">
                                            <option value="customer_app" {{ old('type') == "customer_app" ? 'selected' : '' }}>@lang('site.customer_app')</option>
                                            <option value="delivery_app" {{ old('type') == "delivery_app" ? 'selected' : '' }}> @lang('site.delivery_app') </option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label>@lang('site.status')</label>
                                        <select class="form-control" name="status">
                                            <option value="1" {{ old('status') == "1" ? 'selected' : '' }}>@lang('site.yes')</option>
                                            <option value="0" {{ old('status') == "0" ? 'selected' : '' }}> @lang('site.no') </option>
                                        </select>
                                    </div>
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



    <script>
        function valueType($id){
            if($id === 1){
                document.getElementById("text").style.display = "block";
                document.getElementById("image").style.display = "none";
            }else if($id === 2){
                document.getElementById("image").style.display = "block";
                document.getElementById("text").style.display = "none";
            }
        }

    </script>
@endsection
@section('script')



@endsection
