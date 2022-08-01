@extends('layouts.dashboard.app')

@section('content')

    <div class="page-wrapper" style="min-height: 422px;" data-select2-id="16">
        <div class="content container-fluid" data-select2-id="15">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">@lang('site.files')</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{route('dashboard.welcome') }}">@lang('site.dashboard')</li>
                            <li class="breadcrumb-item"><a
                                    hhref="{{ route('dashboard.products.index') }}"> @lang('site.products') </a></li>
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
                            <h4 class="card-title">@lang('site.files')</h4>
                            @include('partials._errors')


                            <form action="{{ route('dashboard.uploadecxel') }}" method="post"
                                  enctype="multipart/form-data">

                                {{ csrf_field() }}
                                {{ method_field('post') }}
                                <div class="col-md-6">
                                    <div class="form-group">


                                        <label>@lang('site.file')</label>
                                        <input type="file" name="file" class="form-control"
                                               value="{{ old('file') }}">


                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary"><i
                                        class="fa fa-plus"></i>
                                    @lang('site.add')</button>


                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




@endsection

<script type="text/javascript">
    $(".js-example-basic-multiple").select2();
</script>

