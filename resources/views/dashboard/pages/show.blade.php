@extends('layouts.dashboard.app')

@section('content')

    <div class="page-wrapper" style="min-height: 422px;" data-select2-id="16">
        <div class="content container-fluid" data-select2-id="15">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">@lang('site.pages')</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{route('dashboard.welcome') }}">@lang('site.dashboard')</li>
                            <li class="breadcrumb-item"><a
                                    hhref="{{ route('dashboard.pages.index') }}"> @lang('site.pages') </a></li>
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
                            <h4 class="card-title">@lang('site.pages')</h4>
                            @include('partials._errors')
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>@lang('site.ar.name')</label>
                                            <p>{{ $page->name_ar ?? '' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label>@lang('site.en.name')</label>
                                        <p>{{ $page->name_en ?? '' }}</p>
                                    </div>
                                </div>
                                <br><br>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label>@lang('site.copy')</label>
                                        <p>{{ $page->copy ?? '' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label>@lang('site.slug')</label>
                                        <p>{{ $page->slug ?? '' }}</p>
                                    </div>
                                </div>

                                <br><br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>@lang('site.ar.description')</label>
                                        <p>{{ $page->description_ar ?? '' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label>@lang('site.en.description')</label>
                                       <p> {{ $page->description_en ?? '' }}</p>
                                    </div>
                                </div>
                                <br>
                                <br>

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

@section('scripts')

 <script type="text/javascript" src="//js.nicedit.com/nicEdit-latest.js"></script>
  <script type="text/javascript">

        bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
  </script>
@endsection



