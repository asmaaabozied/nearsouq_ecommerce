@extends('layouts.dashboard.app')

@section('content')


    <div class="page-wrapper" style="min-height: 422px;" data-select2-id="16">
        <div class="content container-fluid" data-select2-id="15">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">@lang('site.roles')</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{route('dashboard.welcome') }}">@lang('site.dashboard')</li>
                            <li class="breadcrumb-item"><a
                                    hhref="{{ route('dashboard.roles.index') }}"> @lang('site.roles') </a></li>
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
                            <h4 class="card-title">@lang('site.roles')</h4>
                            @include('partials._errors')

                            <form action="{{ route('dashboard.roles.update', $role->id) }}"
                                  method="post" enctype="multipart/form-data">

                                {{ csrf_field() }}
                                {{ method_field('put') }}

                                <div class="row">
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label>@lang('site.name')</label>
                                            <input type="text" name="name" class="form-control"
                                                   value="{{ $role->name }}" onkeypress="return (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode >= 48 && event.charCode <= 57)">
                                        </div>

                                        <div class="form-group">
                                            <label>@lang('site.display_name')</label>
                                            <input type="text" name="display_name" class="form-control"
                                                   value="{{ $role->display_name }}" onkeypress="return (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode >= 48 && event.charCode <= 57)">
                                        </div>
                                        <div class="form-group">
                                            <label>@lang('site.description')</label>
                                            <input type="text" name="description" class="form-control"
                                                   value="{{ $role->description }}"   >
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <h3>@lang('site.permissions')</h3>
                                            <div class="form-group">

                                                <ul class="nav ">
                                                    <table class="table table-hover table-bordered">


                                                        @foreach ($models as $index=>$model)
                                                            <tr>
                                                                <td>
                                                                    <li class="form-group {{ $index == 0 ? 'active' : '' }}">@lang('site.' . $model)</li>
                                                                </td>
                                                                <td>

                                                                    <div
                                                                        class="form-group {{ $index == 0 ? 'active' : '' }}"
                                                                        id="{{ $model }}">

                                                                        @foreach ($maps as $map)
                                                                            <label><input
                                                                                    type="checkbox"
                                                                                    name="permissions[]"
                                                                                    {{ $role->hasPermission($map . '_' . $model) ? 'checked' : '' }} value="{{ $map . '_' . $model }}"> @lang('site.' . $map)
                                                                                <span></span>
                                                                            </label>



                                                                        @endforeach

                                                                    </div>
                                                                </td>

                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </ul>

                                                <div class="tab-content">


                                                </div><!-- end of tab content -->

                                            </div><!-- end of nav tabs -->

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
