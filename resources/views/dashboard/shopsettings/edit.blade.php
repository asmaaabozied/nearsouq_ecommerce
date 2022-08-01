@extends('layouts.dashboard.app')
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

@section('content')

    <div class="page-wrapper" style="min-height: 422px;" data-select2-id="16">
        <div class="content container-fluid" data-select2-id="15">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">@lang('site.settingss')</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{route('dashboard.welcome') }}">@lang('site.dashboard')/</li>
{{--                            <li class="breadcrumb-item"><a--}}
{{--                                    hhref="{{ route('dashboard.categories.index') }}"> @lang('site.settings') </a></li>--}}
                            <li class="breadcrumb-item active"><a> @lang('site.edit') </a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            @include('flash::message')
            <div class="row" data-select2-id="14">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">@lang('site.settingss')</h4>
                            @include('partials._errors')


                            <form action="{{ route('dashboard.shop_settings.update', $setting->id) }}"
                                  method="post" enctype="multipart/form-data">

                                {{ csrf_field() }}
                                {{ method_field('put') }}

                                <div class="row">



                                    <div class="col-md-6">
                                        <label>@lang('site.paymentss')</label>
                                        <select class="form-control" name="payment">
                                           
                                            <option value="prompt" @if($setting->payment =='prompt') selected @endif> @lang('site.prompt') </option>
                                            <option value="post" @if($setting->payment =='post') selected @endif> @lang('site.post') </option>

                                        </select>
                                    </div>
                                    
                                 
                                </div>

                                  <div class="row">
                                        <div class="col-md-6">
                                        <label>@lang('site.shops')</label>
                                        <select class="form-control" name="shop_id[]" id="required" multiple="multiple"  style="height: 200px"
            size="8">
                                             <option id="selectAll">@lang('site.select_all')</option>
                                            @foreach($shops as $shop)
                                            <option value="{{$shop->id}}"  @if(in_array($shop->id,$selectedshop)) selected @endif > {{$shop->name}} </option>
@endforeach
                                        </select>
                                    </div>
                                     </div>




<br><br>


                                <br>

                                <br>



                                <br>




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

    <script>
        document.getElementById('selectAll').onclick = function() {
            let options = document.getElementsByTagName("option");
            for (let i=0; i<options.length; i++)
            {
                options[i].selected = "true";
            }
        }
    </script>


@endsection

@section('script')
    <script>


        $("#add").click(function(){
            $("#rows").append('<input type="text" name="price[]">');
        });

    </script>



@endsection


