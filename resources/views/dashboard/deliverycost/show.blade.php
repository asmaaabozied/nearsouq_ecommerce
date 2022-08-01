@extends('layouts.dashboard.app')

@section('content')

    <div class="page-wrapper" style="min-height: 422px;" data-select2-id="16">
        <div class="content container-fluid" data-select2-id="15">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">@lang('site.deliverycost')</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{route('dashboard.welcome') }}">@lang('site.dashboard')</li>
                            <li class="breadcrumb-item"><a
                                    hhref="{{ route('dashboard.deliverycost.index') }}"> @lang('site.deliverycost') </a></li>
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
                            <h4 class="card-title">@lang('site.deliverycost')</h4>



                            <form action="{{ route('dashboard.deliverycost.update', $delivery->id) }}"
                                  method="post"
                                  enctype="multipart/form-data">

                                {{ csrf_field() }}
                                {{ method_field('put') }}

                                <div class="row">
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label>@lang('site.price')</label>
                                            <input type="text" name="price" class="form-control"
                                                   value="{{ $delivery->price }}"  readonly >
                                        </div>
                                        <div class="form-group">
                                            <label>@lang('site.max_distance')</label>
                                            <input type="text" name="max_distance" class="form-control"
                                                   value="{{ $delivery->max_distance }}" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>@lang('site.min_distance')</label>
                                            <div id="result">
                                                <input type="text" name="min_distance" class="form-control"


                                                       value="{{ $delivery->min_distance }}"
                                                       readonly
                                                >

                                            </div>
                                        </div>


                                    </div>

                                </div>





                                <div class="text-end mt-4">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-warning mr-1"
                                                onclick="history.back();">
                                            <i class="fa fa-backward"></i> @lang('site.back')
                                        </button>

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
