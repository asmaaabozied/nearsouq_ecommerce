@extends('layouts.dashboard.app')

@section('content')

    <div class="page-wrapper" style="min-height: 422px;" data-select2-id="16">
        <div class="content container-fluid" data-select2-id="15">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">@lang('site.reasons')</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{route('dashboard.welcome') }}">@lang('site.dashboard')</li>
                            <li class="breadcrumb-item"><a
                                    hhref="{{ route('dashboard.reasons.index') }}"> @lang('site.reasons') </a></li>
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
                            <h4 class="card-title">@lang('site.reasons')</h4>
                            @include('partials._errors')



                            <form action="{{ route('dashboard.reasons.store') }}" method="post"
                                  enctype="multipart/form-data">

                                {{ csrf_field() }}
                                {{ method_field('post') }}

                                <div class="row">
                                    <div class="col-md-6">


                                        <label>@lang('site.ar.name')</label>
                                        <input type="text" name="name_ar" class="form-control"
                                               value="{{ old('name_ar') }}">
                                    </div>
                                    <div class="col-md-6">

                                        <label>@lang('site.en.name')</label>
                                        <input type="text" name="name_en" class="form-control"
                                               value="{{ old('name_en') }}">
                                    </div>


                                </div>


                                <br><br>


                                <div class="row">

                                    <div class="col-md-6">

                                        <label>@lang('site.type')</label>


                                        <select class="form-control" name="type">
                                            <option>@lang('site.select')</option>
                                            <option value="CustomerApp"> @lang('site.CustomerApp') </option>
                                            <option value="DeliveryApp"> @lang('site.DeliveryApp') </option>

                                        </select>
                                    </div>









                                </div>




                                <br>


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



@section('scripts')


    <script>

        $('#shop_id').on('change', function (e) {
            var typeId = e.target.value;
            $.get("{{url('dashboard/getshopProduct')}}/" + typeId, function (data) {
                $('#product_id').empty();
                $('#product_id').append('<option>             </option>');
                $.each(data, function (index, product) {
                    $('#product_id').append('<option value="' + product.id + '">' + product.name_ar + '</option>')
                });
            })
        })


    </script>
@endsection

