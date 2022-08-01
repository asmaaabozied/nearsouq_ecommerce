@extends('layouts.dashboard.app')

@section('content')

    <div class="page-wrapper" style="min-height: 422px;" data-select2-id="16">
        <div class="content container-fluid" data-select2-id="15">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">@lang('site.banners')</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{route('dashboard.welcome') }}">@lang('site.dashboard')</li>
                            <li class="breadcrumb-item"><a
                                    hhref="{{ route('dashboard.banners.index') }}"> @lang('site.banners') </a></li>
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
                            <h4 class="card-title">@lang('site.banners')</h4>
                            @include('partials._errors')

                            <form action="{{ route('dashboard.banners.update', $banner->id) }}"
                                  method="post" enctype="multipart/form-data">

                                {{ csrf_field() }}
                                {{ method_field('put') }}

                                <div class="row">
                                    <div class="col-md-6">


                                        <label>@lang('site.ar.name')</label>
                                        <input type="text" name="name_ar" class="form-control"
                                               value="{{ $banner->name_ar ?? '' }}">
                                    </div>
                                    <div class="col-md-6">

                                        <label>@lang('site.en.name')</label>
                                        <input type="text" name="name_en" class="form-control"
                                               value="{{ $banner->name_en ?? '' }}">
                                    </div>



                                </div>


                                <div class="row">

                                    <div class="col-md-6">


                                        <label>@lang('site.shops')</label>

                                        <select class="form-control" name="shop_id" id="shop_id">
                                            <option selected disabled>@lang('site.select')</option>
                                            <option value="0" @if($banner->shop_id ==0) selected @endif> @lang('site.nodataselect')</option>
                                            @foreach($shops as $shop)
                                                <option value="{{$shop->id}}"  @if($banner->shop_id ==$shop->id) selected @endif>{{$shop->name ?? ''}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">


                                        <label>@lang('site.products')</label>

                                        <select class="form-control" name="product_id" id="product_id">
                                            <option selected disabled>@lang('site.select')</option>
                                            <option value="0" @if($banner->product_id ==0) selected @endif> @lang('site.nodataselect')</option>

                                        @foreach(\App\Models\Product::all() as $product)
                                                <option value="{{$product->id}}" @if($banner->product_id ==$product->id) selected @endif>{{$product->name ?? ''}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!--<div class="col-md-6">-->


                                    <!--    <label>@lang('site.user')</label>-->

                                    <!--    <select class="form-control" name="admin_id">-->
                                    <!--        <option selected disabled>@lang('site.select')</option>-->
                                    <!--        <option value="0" @if($banner->admin_id ==0) selected @endif> @lang('site.nodataselect')</option>-->

                                    <!--    @foreach($users as $user)-->
                                    <!--            <option value="{{$user->id}}"  @if($banner->admin_id ==$user->id) selected @endif>{{$user->name ?? ''}}</option>-->
                                    <!--        @endforeach-->
                                    <!--    </select>-->
                                    <!--</div>-->
                                          <div class="col-md-6">

                                        <label>@lang('site.position')</label>


                                        <select class="form-control" name="position">
                                            <option>@lang('site.select')</option>
                                            <option value="TOP"   @if($banner->position =='TOP') selected @endif> @lang('site.TOP') </option>
                                            <option value="DOWN" @if($banner->position =='DOWN') selected @endif> @lang('site.DOWN') </option>

                                        </select>
                                    </div>
                                    

                                    <div class="col-md-6">

                                        <label>@lang('site.type')</label>


                                        <select class="form-control" name="type">
                                            <option>@lang('site.select')</option>
                                            <option value="INAPP"
                                                    @if($banner->type =='INAPP') selected @endif> @lang('site.INAPP') </option>
                                            <option value="OUTSIDE_APP"
                                                    @if($banner->type =='OUTSIDE_APP') selected @endif> @lang('site.OUTSIDE_APP') </option>

                                        </select>
                                    </div>


                              

                                </div>


                                <br><br>



                                <br>
                                <div class="row">
                                    <div class="col-md-6">


                                        <label>@lang('site.image')</label>
                                        <input type="file" name="image" class="form-control"
                                               value="{{ old('image') }}">

                                    </div>

                                    <div class="col-md-6">
                                        <label>@lang('site.image')</label>
                                        <img
                                            src="{{asset('/uploads/shops/banners/'.$banner->image)}}"

                                            data-bs-toggle="modal"
                                            data-bs-target="#exampleModalss" width="100px" height="100px">
                                    </div>
                                           <div class="col-md-6">

                                        <label>@lang('site.url')</label>
                                        <input type="text" name="banner_url" class="form-control"
                                               value="{{ $banner->banner_url ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
<br><br>

                                        <label>@lang('site.visible')</label>

                                        <input type="checkbox" value="1" name="visible" @if($banner->visible ==1) checked @endif>
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
                                                                 src="{{asset('/uploads/shops/banners/'.$banner->image)}}"
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
                                <br><br><br>

                                   <div class="row">
                                    <div class="col-md-6">


                                        <label>@lang('site.ar.description')</label>

                                        <textarea id="w3review" class="form-control" name="details_ar" rows="4" cols="50">{{ $banner->details_ar ?? '' }}
                       </textarea>

                                    </div>
                                    <div class="col-md-6">

                                        <label>@lang('site.en.description')</label>
                                        <textarea id="w3review"   class="form-control" name="details_en" rows="4" cols="50">{{ $banner->details_en ?? '' }}
                                </textarea>
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

@section('scripts')


    <script>

        $('#shop_id').on('change', function (e) {
            var typeId = e.target.value;
            $.get("{{url('dashboard/getshopProduct')}}/" + typeId, function (data) {
                $('#product_id').empty();
                $('#product_id').append('<option>             </option>');
                $.each(data, function (index, product) {
                    $('#product_id').append('<option value="' + product.id + '">' + product.name_ar + '</option>')
                });
            })
        })


    </script>
@endsection



