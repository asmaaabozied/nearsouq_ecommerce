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
    <style>
        .userSelector{
            height: 100%;
        }
        .userOption{
            padding-top:15px;
            padding-bottom: 15px;
            text-align: center;
        }
    </style>
    <div class="page-wrapper" style="min-height: 422px;" data-select2-id="16">
        <div class="content container-fluid" data-select2-id="15">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">@lang('site.notifications')</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('dashboard.welcome') }}">@lang('site.dashboard')</li>
                            <li class="breadcrumb-item"><a hhref="{{ route('dashboard.notifications.index') }}"> @lang('site.notifications') </a></li>
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
                            <h4 class="card-title">@lang('site.notifications')</h4>
                            @include('partials._errors')


                            <form action="{{ route('dashboard.notifications.store') }}" method="post" enctype="multipart/form-data">

                                {{ csrf_field() }}
                                {{ method_field('post') }}

                                <div class="row">
                                    <div class="col-md-6">
                                        <label>@lang('site.title')</label>
                                        <input type="text" name="title" class="form-control" value=" {{old('title')}} ">
                                    </div>
                                    <div class="col-md-6">
                                        <label>@lang('site.message')</label>
                                        <input type="text" name="message" class="form-control" value=" {{old('message')}} ">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>@lang('site.users')</label><br>
                                        <input type="checkbox" id="selectAll" name="all" value="1"> @lang('site.select_all')
                                        <!--<select class="form-control select2 userSelector" name="user_id[]" id="selectAll" multiple>-->
                                        <!--    @foreach($users as $user)-->
                                        <!--        <option class="userOption" value="{{$user->id}}" {{ old('user_id') == $user->id ? 'selected' : '' }} name="userOption"> {{$user->name}} </option>-->
                                        <!--    @endforeach-->
                                        <!--</select>-->
                                        
                                              <select class="form-control select2 select2-hidden-accessible"  id="selectAll" multiple=""  name="user_id[]" data-placeholder="{{trans('site.select')}}" style="width: 100%;" tabindex="-1" aria-hidden="true">

                                                @foreach($users as $user)
                                                <option class="userOption" value="{{$user->id}}" {{ old('user_id') == $user->id ? 'selected' : '' }} name="userOption"  >{{$user->name ?? ''}}</option>

                                                @endforeach
                                            </select>
                                    </div>
                                    <div class="col-md-6">
                                    <label for="name" class="col-sm-3 col-form-label input-label">@lang('site.image')</label>
                                    <input type="file" id="mall_image" value="{{ old('image') }}" name="image" name="image">
                                </div>
                                </div>

                                <br>
                                <br>
                                <br>

                                <div class="text-end mt-4">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-warning mr-1" onclick="history.back();">
                                            <i class="fa fa-backward"></i> @lang('site.back')
                                        </button>
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i>
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



    <script>
         document.getElementById('selectAll').onclick = function() {
            let options = document.getElementsByTagName("option");
            console.log(document.getElementById('selectAll').checked);
            if(document.getElementById('selectAll').checked === true){
                console.log(true);
                for (let i=0; i<options.length; i++)
                {
                    options[i].selected = true;
                }
            }else{
                console.log(false);
                for (let i=0; i<options.length; i++)
                {
                    options[i].selected = false;
                }
            }
            
        }
    </script>
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
