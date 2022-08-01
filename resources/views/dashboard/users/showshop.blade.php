@extends('layouts.dashboard.app')
@section('css')

    <style>


    .select2-hidden-accessibles {
    border: 0 !important;
    clip: rect(0 0 0 0) !important;
    height: 1px !important;
    margin: 1px !important;
    overflow: hidden !important;
    padding: 0 !important;
    position: absolute !important;
    width: 1px !important
    }



    </style>
@endsection
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









                                            <select class="form-control select2 select2-hidden-accessible" multiple=""  name="shop_id[]" data-placeholder="{{trans('site.select')}}" style="width: 100%;" tabindex="-1" aria-hidden="true">

                                                @foreach($shops as $shop)
                                                <option value="{{$shop->id}}"
                                                  @if(in_array($shop->id,$shopselected)) selected @endif
                                                >{{$shop->name ?? ''}}</option>

                                                @endforeach
                                            </select> </div> <!-- /.form-group -->


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

@section('scripts')


    <script>

        $(document).ready(function() {
            $('.select2').select2({
                closeOnSelect: false
            });
        });
    </script>
@endsection



