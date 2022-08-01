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
                            <h4 class="card-title">@lang('site.users')</h4>
                            @include('partials._errors')


                            <form action="{{ route('dashboard.users.update', $user->id) }}"
                                  method="post"
                                  enctype="multipart/form-data">

                                {{ csrf_field() }}
                                {{ method_field('put') }}
                                <input id="type" hidden type="text" name="type" value="Admin"
                                       required>
                                  <div class="row form-group">
                                    <label for="name"
                                           class="col-sm-3 col-form-label input-label">@lang('site.image')</label>
                                    <div class="col-sm-9">
                                        <div class="d-flex align-items-center">
                                            <div class="col-md-6">
                                                <label>@lang('site.image')</label>
                                                <img
                                                    src="{{asset('uploads/'.$user->image)}}"

                                                    data-bs-toggle="modal"
                                                    data-bs-target="#exampleModalss" width="100px" height="100px">
                                            </div>


                                        </div>
                                    </div>
                                </div>

                                <!-- Modal -->
                                <div class="modal fade" id="exampleModalss"
                                     tabindex="-1" aria-labelledby="exampleModalLabel"
                                     aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"
                                                    id="exampleModalLabel">@lang('site.image')</h5>
                                                <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <table class="border-5">
                                                    <tr>
                                                        <th>
                                                            <img name="soso"
                                                                 src="{{asset('uploads/'.$user->image)}}"
                                                                 alt="" width="400px"
                                                                 height="aut0">

                                                        </th>
                                                    </tr>


                                                </table>


                                            </div>
                                            <div class="modal-footer">
                                                <button type="button"
                                                        class="btn btn-secondary"
                                                        data-bs-dismiss="modal">@lang('site.Cancel')</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--  End Of Modal -->
                                <div class="row">
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label>@lang('site.name')</label>
                                            <input type="text" name="name" class="form-control"
                                                   value="{{ $user->name }}" disabled>
                                        </div>
                                        <!--<div class="form-group">-->
                                        <!--    <label>@lang('site.last_name')</label>-->
                                        <!--    <input type="text" name="last_name" class="form-control"-->
                                        <!--           value="{{ $user->last_name }}" disabled>-->
                                        </div>
                                        </div>
                                        <div class="row">
                                           <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('site.phone')</label>
                                            <div id="result">
                                                <input type="text" name="phone" class="form-control"

                                                       type="tel" id="phone"
                                                       value="{{ $user->phone }}"
                                                       disabled
                                                >

                                            </div>
                                        </div>
</div>
  </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('site.address')</label>
                                            <input type="text" name="address" class="form-control"
                                                   value="{{ $user->address }}" disabled>
                                        </div>


                                        <div class="form-group">
                                            <label>@lang('site.email')</label>
                                            <input type="email" name="email" class="form-control"
                                                   value="{{ $user->email }}" disabled>
                                        </div>

                                        <div class="form-group">
                                            <label>@lang('site.code')</label>
                                            <input type="text" name="code" class="form-control"
                                                   value="{{ $user->code }}" disabled>
                                        </div>



                                        <div class="form-group">
                                            <label>@lang('site.password_confirmation')</label>
                                            <input type="password" name="password_confirmation"
                                                   class="form-control" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label>@lang('site.password')</label>
                                            <input type="password" name="password" class="form-control" disabled>
                                        </div>


                                    </div>
                                </div>

                                <h4 class="card-title mt-4">@lang('site.roles')</h4>

                                <div class="row">
                                    <div class="col-md-6">
                                  
                                        @if (auth()->user()->hasPermission('read_roles'))
                                            <div class="form-group">
                                                <label>@lang('site.roles')</label>
                                                <select name="roles[]" class="form-control select2"
                                                disabled>
                                                    <option disabled selected>@lang('site.select')</option>
                                                    @foreach ($roles as $role)
                                                        <option
                                                            value="{{ $role->id }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                                            {{ $role->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif

                                        @if (auth()->user()->hasRole('SuperAdmin'))
                                            <div class="form-group">
                                                <label>@lang('site.shops')</label>
                                                <select name="shop_id" class="form-control select2"
                                               disabled >
                                                    <option disabled selected>@lang('site.select')</option>
                                                    @foreach (\App\Models\Shop::get() as $shop)
                                                        <option
                                                            value="{{ $shop->id }}" {{ $user->shop_id == $shop->id ? 'selected' : '' }}>
                                                            {{ $shop->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif

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
