@extends('layouts.dashboard.app')

@section('content')
    <div class="page-wrapper" style="min-height: 422px;" data-select2-id="16">
        <div class="content container-fluid" data-select2-id="15">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">@lang('site.wallets')</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{route('dashboard.welcome') }}">@lang('site.dashboard')</li>
                            <li class="breadcrumb-item"><a
                                    hhref="{{ route('dashboard.wallets.index') }}"> @lang('site.wallets') </a></li>
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

                            @include('partials._errors')

                            <form action="{{ route('dashboard.wallets.store') }}" method="post"
                                  enctype="multipart/form-data"
                            >

                                {{ csrf_field() }}
                                {{ method_field('post') }}

                                <div class="row">
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label>@lang('site.balance')</label>
                                            <input type="text" name="balance" class="form-control"
                                                   value="{{ old('balance') }}">
                                        </div>





                                    </div>

                                </div>

                                <h4 class="card-title mt-4">@lang('site.image')</h4>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">


                                            <label>@lang('site.image')</label>
                                            <input type="file" name="image" class="form-control"
                                                   value="{{ old('image') }}">


                                        </div>

                                        <div class="form-group">
                                            <label>@lang('site.users')</label>
                                            <select name="user_id" class="form-control select2"
                                                    >
                                             <option selected disabled>@lang('site.select')</option>
                                <option value="0"  >@lang('site.nodataselect')</option>
                                                @foreach ($users as $user)
                                                    <option
                                                        value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>@lang('site.shops')</label>
                                            <select name="shop_id" class="form-control select2"
                                                    >
                                                 <option selected disabled>@lang('site.select')</option>
                                <option value="0"  >@lang('site.nodataselect')</option>
                                                @foreach ($shops as $shop)
                                                    <option
                                                        value="{{ $shop->id }}" {{ old('shop_id') == $shop->id ? 'selected' : '' }}>
                                                        {{ $shop->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>@lang('site.status')</label>
                                            <select name="status" class="form-control select2"
                                                    >
                                           <option selected>@lang('site.select')</option>
                                           <option value="1">@lang('site.active')</option>
                                           <option value="0">@lang('site.inactive')</option>

                                            </select>
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
