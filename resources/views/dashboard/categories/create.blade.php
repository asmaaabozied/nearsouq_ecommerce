@extends('layouts.dashboard.app')

@section('content')

<div class="page-wrapper" style="min-height: 422px;" data-select2-id="16">
    <div class="content container-fluid" data-select2-id="15">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-title">@lang('site.categories')</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('dashboard.welcome') }}">@lang('site.dashboard')</li>
                        <li class="breadcrumb-item"><a hhref="{{ route('dashboard.categories.index') }}"> @lang('site.categories') </a></li>
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
                        <h4 class="card-title">@lang('site.categories')</h4>
                        @include('partials._errors')


                        <form action="{{ route('dashboard.categories.store') }}" method="post" enctype="multipart/form-data">

                            {{ csrf_field() }}
                            {{ method_field('post') }}

                            <div class="row">
                                <div class="col-md-6">
                                    <label>@lang('site.ar.name')</label>
                                    <input type="text" name="name_ar" class="form-control" value=" {{old('name_ar')}} ">
                                </div>
                                <div class="col-md-6">
                                    <label>@lang('site.en.name')</label>
                                    <input type="text" name="name_en" class="form-control" value=" {{old('name_en')}} ">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>@lang('site.ar.description')</label>
                                    <input type="text" name="description_ar" class="form-control" value=" {{old('description_ar')}} ">
                                </div>
                                <div class="col-md-6">
                                    <label>@lang('site.en.description')</label>
                                    <input type="text" name="description_en" class="form-control" value=" {{old('description_en')}} ">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>@lang('site.category')</label>
                                    <select class="form-control" name="parent">
                                        <option>-</option>
                                        @foreach($categories as $category)
                                        <option value="{{$category->id}}" {{ old('category_id') == $category->id ? 'selected' : '' }}> {{$category->name_ar}} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>@lang('site.visible')</label>
                                    <select class="form-control" name="status">
                                        <option value="1" {{ old('visible') == 1 ? 'selected' : '' }}> yes </option>
                                        <option value="0" {{ old('category_id') == 0 ? 'selected' : '' }}> no </option>

                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label for="name" class="col-sm-3 col-form-label input-label">@lang('site.image')</label>
                                <input type="file" id="category_image" value="{{ old('image') }}" name="image">
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




@endsection
@section('script')
<script>
    $("#add").click(function() {
        $("#rows").append('<input type="text" name="price[]">');
    });

</script>


@endsection
