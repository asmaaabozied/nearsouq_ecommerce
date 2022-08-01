@extends('layouts.dashboard.app')

@section('content')

    <div class="page-wrapper" style="min-height: 422px;" data-select2-id="16">
        <div class="content container-fluid" data-select2-id="15">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">@lang('site.options')</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{route('dashboard.welcome') }}">@lang('site.dashboard')</li>
                            <li class="breadcrumb-item"><a
                                    hhref="{{ route('dashboard.options.index') }}"> @lang('site.options') </a></li>
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
                            <h4 class="card-title">@lang('site.options')</h4>
                            @include('partials._errors')

                            <form action="{{ route('dashboard.options.store') }}" method="post"
                                  enctype="multipart/form-data">

                                {{ csrf_field() }}
                                {{ method_field('post') }}

                                <div class="row">
                                    <div class="col-md-6">


                                        <label>@lang('site.ar.name')</label>
                                        <input type="text" name="name_ar" class="form-control"
                                               value="{{ old('name_ar') }}" required>
                                    </div>
                                    <div class="col-md-6">

                                        <label>@lang('site.en.name')</label>
                                        <input type="text" name="name_en" class="form-control"
                                               value="{{ old('name_en') }}" required>
                                    </div>

                                    <div class="col-md-6">

                                        <label>@lang('site.reference_name')</label>
                                        <input type="text" name="reference_name" class="form-control"
                                               value="{{ old('reference_name') }}" required>
                                    </div>


                                </div>
                                <div class="row">


                                    <div class="col-md-12">

                                        <label>@lang('site.type')</label>


                                        <select class="form-control" name="type">
                                            <option>@lang('site.select')</option>
                                            <option value="OPTION"> @lang('site.OPTION') </option>
                                            <option value="REQUIRED"> @lang('site.Required') </option>

                                        </select>
                                    </div>


{{--                                    <div class="col-md-12">--}}


{{--                                        <label>@lang('site.image')</label>--}}
{{--                                        <input type="file" name="image" class="form-control"--}}
{{--                                               value="{{ old('image') }}">--}}


{{--                                    </div>--}}
                                </div>
                                <br>
                                <br>


                                <table class="authors-list" id="table5">
                                    <tr>
                                        <td>@lang('site.ar.name')</td>
                                        <td>@lang('site.en.name')</td>
                                        <td>@lang('site.price')</td>
                                        <td>@lang('site.image')</td>
                                        <td>@lang('site.action')</td>
                                    </tr>

                                    <tr class="candidate">
                                        <td><input type="text" name="vname_ar[]"/></td>
                                        <td><input type="text" name="vname_en[]"/></td>
                                        <td><input type="text" name="extra_price[]"/></td>
                                        @for ($i = 0; $i < 10; $i++)

                                            <input type="hidden" name="id"  value="{{$i}}">
                                        @endfor

                                        <td>
                                            <input type="file" name="images[]" class="form-control">
                                        </td>
                                        <td>
                                            <a onclick="deleteRow(this)" style="border: none;
                                                    background: transparent;">
                                                <i class="far fa-trash-alt me-1 fa-2x delete"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                                <a href="#" title="" class="add-author">@lang('site.add')</a>

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


    $(document).ready(function(){
        jQuery('a.add-author').click(function(event){
            event.preventDefault();
            var newRow = jQuery('<tr class="candidate"><td>' +
                '<input type="text" name="vname_ar[]"/>' +
                '</td><td>' +
                '<input type="text" name="vname_en[]"/>' +
                '</td><td>' +
                '<input type="text" name="extra_price[]"/>' +
                '</td><td>' +
                '<input type="file" name="images[]" class="form-control"/>' +
                '</td>' +
                '<td>' +
                ' <a onclick="deleteRow(this)">' +
                '<i class="far fa-trash-alt me-1 fa-2x delete"></i>' +
                '</a>' +
                '</td>' +
                '</tr>');
            jQuery('table.authors-list').append(newRow);
        });
    });

</script>

@endsection
