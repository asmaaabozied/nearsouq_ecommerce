@extends('layouts.dashboard.app')

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
                            <h4 class="card-title">@lang('site.users')</h4>
                            @include('partials._errors')

                            <form action="{{ route('dashboard.users.store') }}" method="post"
                                  enctype="multipart/form-data"
                            >

                                {{ csrf_field() }}
                                {{ method_field('post') }}

                                <div class="row">
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label>@lang('site.name')</label>
                                            <input type="text" name="name" class="form-control"
                                                   value="{{ old('name') }}" required >
                                        </div>
                                        <!--<div class="form-group">-->
                                        <!--    <label>@lang('site.last_name')</label>-->
                                        <!--    <input type="text" name="last_name" class="form-control"-->
                                        <!--           value="{{ old('last_name') }}">-->
                                        <!--</div>-->
                                        <div class="form-group">
                                            <label>@lang('site.phone')</label>
                                            <div id="result">
                                                <input type="text" name="phone" class="form-control"

                                                       type="tel" 
                                                   id="mobilephone" maxlength="10"
                               size="10" required
                                                >

                                            </div>
                                        </div>




                                        <div class="form-group">
                                            <label>@lang('site.password_confirmation')</label>
                                            <input type="password" name="password_confirmation"
                                                   class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('site.address')</label>
                                            <input type="text" name="address" class="form-control"
                                                   value="{{ old('address') }}"   required >
                                        </div>

                                    <!--<div class="form-group">-->

                                    <!--<label>@lang('site.code')</label>-->
                                    <!--        <input type="text" name="code" class="form-control"-->
                                    <!--               value="{{ old('code') }}">-->
                                    <!--    </div>-->


                                    <div class="form-group">

                                    <label>@lang('site.email')</label>
                                            <input type="email" name="email" class="form-control"
                                                   value="{{ old('email') }}"  required >
                                        </div>

                                    <div class="form-group">

                                            <label>@lang('site.password')</label>
                                            <input type="password" name="password" class="form-control" required >
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
                                        @if (auth()->user()->hasPermission('read_roles'))

                                        <div class="form-group">
                                            <label>@lang('site.roles')</label>
                                            <select name="roles[]" class="form-control select2"
                                                    >
                                                <option disabled selected>@lang('site.select')</option>
                                                @foreach ($roles as $role)
                                                    <option
                                                        value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                        {{ $role->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                            @endif



{{--                                            @if (auth()->user()->hasRole('SuperAdmin'))--}}
{{--                                         <div class="form-group">--}}
{{--                                             <label>@lang('site.shops')</label>--}}
{{--                                             <select name="shop_id" class="form-control select2"--}}
{{--                                                     >--}}
{{--                                                 <option disabled selected>@lang('site.select')</option>--}}
{{--                                                 @foreach (\App\Models\Shop::get() as $shop)--}}
{{--                                                     <option--}}
{{--                                                         value="{{ $shop->id }}" {{ old('shop_id') == $shop->id ? 'selected' : '' }}>--}}
{{--                                                         {{ $shop->name }}--}}
{{--                                                     </option>--}}
{{--                                                 @endforeach--}}
{{--                                             </select>--}}
{{--                                         </div>--}}
{{--                                             @endif--}}

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
