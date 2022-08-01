@extends('layouts.dashboard.app')

@section('content')

    <div class="page-wrapper" style="min-height: 422px;" data-select2-id="16">
        <div class="content container-fluid" data-select2-id="15">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">@lang('site.userReports')</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{route('dashboard.welcome') }}">@lang('site.dashboard')</li>
                            <li class="breadcrumb-item"><a
                                    hhref="{{ route('dashboard.userReports.index') }}"> @lang('site.userReports') </a></li>
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
                            <h4 class="card-title">Basic Info</h4>
                            @include('partials._errors')


                            <form action="{{ route('dashboard.userReports.update', $userReport->id) }}"
                                  method="post"
                                  enctype="multipart/form-data">

                                {{ csrf_field() }}
                                {{ method_field('put') }}
                                
                                
                                <div class="row">
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label>@lang('site.name')</label>
                                            <input type="text" name="name" class="form-control"
                                                   value="{{ $userReport->name }}">
                                        </div>
                                        </div>
                                        <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('site.email')</label>
                                            <input type="text" name="email" class="form-control"
                                                   value="{{ $userReport->email }}">
                                        </div>
                                        </div>
                                        <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('site.message')</label>
                                            <div id="result">
                                                <textarea type="text" name="message" class="form-control"

                                                       type="text" id="message"  rows="5" cols="60">{{ $userReport->message }}
                                                    
                                                    
                                                </textarea>
                                                <!--<input type="text" name="message" class="form-control"-->

                                                <!--       type="text" id="message"-->
                                                <!--       value="{{ $userReport->message }}">-->

                                            </div>
                                        </div>
                                        
                                                <div class="col-md-12">
                                        <div class="form-group">
   <label>@lang('site.status')</label>

                                                            <select class="form-control"
                                                                    name="status"
                                                                     style="height:42px">
                                                                <option disabled>@lang('site.select')</option>
                                                                <option
                                                                    value="responded" {{$userReport->status == 'responded'  ? 'selected' : ''}}>@lang('site.responded')</option>
                                                                <option
                                                                    value="not_responded" {{$userReport->status == 'not_responded'  ? 'selected' : ''}}>@lang('site.not_responded')</option>

                                                            </select>
                                                        </div>
                                                        </div>


                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('site.answer')</label>
                                            <input type="text" name="answer" class="form-control"
                                                   value="{{ $userReport->answer }}">
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
