@extends('layouts.dashboard.app')
<style>
    table {

        border: 10px #1a2226;
    }

    th, td {
        padding: 15px;
        text-align: left;
    }

    tr:hover {
        background-color: #ddb6dc;
    }

</style>
@section('content')


    <div class="page-wrapper" style="min-height: 422px;">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">

                        <h3 class="page-title">@lang('site.shops')</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{route('dashboard.welcome') }}">@lang('site.dashboard')</a></li>

                            <li class="breadcrumb-item active">@lang('site.shops')({{$parents->count()}})</li>
                        </ul>
                    </div>
                    <div class="col-auto">

                    </div>
                </div>
            </div>
            <!-- /Page Header -->


            <form method="post" action="{{route('dashboard.edit-shop',$shop->id)}}"
                  enctype='multipart/form-data'
                  files="true" autocomplete="off">
                @csrf

                {{-- start Shops--}}
                <div class="row" data-select2-id="14">
                    <div class="col-md-12">
                        <div class="card">

                            <div class="card-body">


                                <div class="row form-group">
                                    <label for="name" class="col-sm-12 col-form-label input-label"> @lang('site.tax_file')</label>
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-center">
                                            <label class="avatar avatar-xxl profile-cover-avatar m-0" for="edit_img">
                                                {{--                                             <a href="{{asset('uploads/shops/profiles/'.$shop->commerical_img)}}" target="_blank">--}}
                                                <img id="avatarImg" src="{{asset('uploads/shops/profiles/'.$shop->commerical_img)}}" alt="commerical Image" width="100px" height="100px" class="data" data-bs-toggle="modal" data-bs-target="#exampleModals" >

                                                {{--                                             </a>--}}

                                            </label>
                                            <input type="file" name="commerical_img" class="form-control"   >

                                        </div>

                                    </div>

                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModals" tabindex="-1" aria-labelledby="exampleModalLabel"
                                         aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">@lang('site.tax_file')</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <table class="border-5">
                                                        <tr>
                                                            <th>
                                                                <img name="soso" src="{{asset('uploads/shops/profiles/'.$shop->commerical_img)}}" alt="" width="400px" height="aut0">

                                                            </th>
                                                        </tr>







                                                    </table>


                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">@lang('site.Cancel')</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--  End Of Modal -->

                                    <div class="col-sm-6">
                                        <label for="name" class="col-sm-12 col-form-label input-label"> @lang('site.file')</label>

                                        <div class="d-flex align-items-center">
                                            <label class="avatar avatar-xxl profile-cover-avatar m-0" for="edit_img">
                                                {{--                                             <a href="{{asset('uploads/shops/profiles/'.$shop->vat_img)}}" target="_blank">--}}
                                                <img id="avatarImg" src="{{asset('uploads/shops/profiles/'.$shop->vat_img)}}" alt="VAT Image" width="100px" height="100px" class="data" data-bs-toggle="modal" data-bs-target="#exampleModalss" >

                                                {{--                                            </a>--}}

                                            </label>
                                            <input type="file" name="vat_img" class="form-control">
                                        </div>


                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModalss" tabindex="-1" aria-labelledby="exampleModalLabel"
                                             aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">@lang('site.file')</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <table class="border-5">
                                                            <tr>
                                                                <th>
                                                                    <img name="soso" src="{{asset('uploads/shops/profiles/'.$shop->vat_img)}}" alt="" width="400px" height="aut0">

                                                                </th>
                                                            </tr>







                                                        </table>


                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">@lang('site.Cancel')</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--  End Of Modal -->

                                        <div class="col-sm-6">
                                            <label for="name" class="col-sm-12 col-form-label input-label"> @lang('site.mainimage')</label>

                                            <div class="d-flex align-items-center">
                                                <label class="avatar avatar-xxl profile-cover-avatar m-0" for="edit_img">
                                                    {{--                                             <a href="{{asset('uploads/shops/profiles/'.$shop->vat_img)}}" target="_blank">--}}
                                                    <img id="avatarImg" src="{{asset('uploads/shops/profiles/'.$shop->image)}}" alt="VAT Image" width="100px" height="100px" class="data" data-bs-toggle="modal" data-bs-target="#exampleModalsss">


                                                    {{--                                            </a>--}}



                                                </label>


                                            </div>
                                            <input type="file" name="image" class="form-control"   >
                                        </div>
                                    </div>
                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModalsss" tabindex="-1" aria-labelledby="exampleModalLabel"
                                         aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">@lang('site.mainimage')</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <table class="border-5">
                                                        <tr>
                                                            <th>
                                                                <img name="soso" src="{{asset('uploads/shops/profiles/'.$shop->image)}}" alt="" width="400px" height="aut0">

                                                            </th>
                                                        </tr>







                                                    </table>


                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">@lang('site.Cancel')</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--  End Of Modal -->

                                    <div class="row">

                                        <div class="col-md-6">

                                            <label>@lang('site.ar.name')</label>

                                            <input type="text" class="form-control" value="{{$shop->name_ar}}" name="name_ar">
                                        </div>
                                        <div class="col-md-6">

                                            <label>@lang('site.en.name')</label>

                                            <input type="text" value="{{$shop->name_en}}" class="form-control" name="name_en">
                                        </div>


                                    </div>

                                    <div class="row">

                                        <div class="col-md-6">

                                            <label>@lang('site.brand_name_ar')</label>

                                            <input type="text" class="form-control" value="{{$shop->brand_name_ar}}"  name="brand_name_ar">
                                        </div>
                                        <div class="col-md-6">

                                            <label>@lang('site.brand_name_en')</label>

                                            <input type="text" value="{{$shop->brand_name_en}}" class="form-control"  name="brand_name_en" >
                                        </div>


                                    </div>
                                    <div class="row">

                                        <div class="col-md-6">

                                            <label>@lang('site.phone')</label>

                                            <input type="text" class="form-control" value="{{$shop->phone}}" name="phone">
                                        </div>
                                        <div class="col-md-6">

                                            <label>@lang('site.mobilephone')</label>

                                            <input type="text" value="{{$shop->mobilephone}}" class="form-control" name="mobilephone">
                                        </div>


                                    </div>
                                    <div class="row">

                                        <div class="col-md-6">

                                            <label>@lang('site.address')</label>

                                            <input type="text" class="form-control" value="{{$shop->address}}" name="address">
                                        </div>
                                        <div class="col-md-6">

                                            <label>@lang('site.tax')</label>

                                            <input type="text" value="{{$shop->vat_no}}" class="form-control" name="vat_no">
                                        </div>


                                    </div>

                                    <div class="row">

                                        <div class="col-md-6">

                                            <label>@lang('site.commerical_number')</label>

                                            <input type="text" class="form-control" value="{{$shop->commerical_number}}" name="commerical_number" disabled>
                                        </div>
                                        
                                        
                                             <div class="col-md-6">

                                            <label>@lang('site.email')</label>

                                            <input type="text" class="form-control" value="{{$user->email}}" name="email" disabled>
                                        </div>
                                        
                                        <div class="col-md-6">

                                            <label>@lang('site.vat')</label>

                                            <input type="text" value="{{$shop->vat}}" class="form-control" name="vat">
                                        </div>
                                        
                                           <div class="col-md-6">

                                            <label>@lang('site.commission')</label>

                                            <input type="text" class="form-control" value="{{$shop->commission}}" name="commission">
                                        </div>


                                    </div>

                                    <div class="row">

                                        <div class="col-md-12">

                                            <label for="sel1">@lang('site.categories'): <span class="text-danger">*</span></label>

                                            <select class="form-control" id="category_id" name="category_id"
                                                    data-placeholder="@lang('site.categories')">
                                                <option  selected disabled>@lang('site.select')</option>
                                                  <option value="0" @if($shop->category_id ==0) selected @endif >@lang('site.nodataselect')</option>

                                                @foreach(App\Models\category::all() as $cat)
                                                    <option value="{{$cat->id}}" @if($shop->category_id==$cat->id) selected @endif">{{$cat->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>



                                     
                                    </div>
                                    <div class="row">

                                        <div class="col-md-6">

                                            <label>@lang('site.latitude')</label>

                                            <input type="text" class="form-control" value="{{$shop->latitude}}" name="latitude">
                                        </div>
                                        <div class="col-md-6">

                                            <label>@lang('site.longitude')</label>

                                            <input type="text" value="{{$shop->longitude}}" class="form-control" name="longitude">
                                        </div>


                                    </div>
                                    <br><br>
                                    <div class="row">

                                        <div class="col-md-6">

                                            <label>@lang('site.ar.description')</label>
                                            <textarea id="w3review" class="form-control" rows="4" cols="50" name="desc_ar" >{{$shop->desc_ar}}
                       </textarea>

                                        </div>
                                        <div class="col-md-6">

                                            <label>@lang('site.en.description')</label>

                                            <textarea id="w3review" class="form-control"  rows="4" cols="50" name="desc_en">{{$shop->desc_en}}
                       </textarea>                                </div>


                                    </div>



                                    <div class="row">


                                        <br>
                                        <div class="col-md-6">

                                            <label>@lang('site.malls')</label>
                                        <select class="form-select" style="width:100% !important;" name="mall_id" id="mall_id">
                                            <option selected disabled>@lang('site.select')</option>
                                       
                               <option value="0" @if($shop->mall_id ==0) selected @endif >@lang('site.nodataselect')</option>

                                            @foreach(App\Models\Mall::get() as $mall)
                                                <option value="{{$mall->id}}"  @if($shop->mall_id==$mall->id)  selected @endif</option>{{$mall->name}} </option>
                                            @endforeach

                                        </select>
                                        </div>

                                        <div class="col-md-6">
                                            <br>
                                            <label>@lang('site.active')</label>
                                            <input type="checkbox" value="1" name="published" @if($shop->published=='TRUE') checked @endif>

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




                                </div>
                            </div>
                        </div>
                    </div>
                {{-- shop Shops--}}

            </form>
            <!-- Search Filter -->
            <div class="row" data-select2-id="14">
                <div class="col-md-12">
                    <div class="card">


                        <div class="card-body">


                            <table class="col-md-12">
                                <tr>

                                    <td>@lang('site.id')</td>
                                    <td>@lang('site.name')</td>
                                    <td>@lang('site.address')</td>
                                    <td>@lang('site.malls')</td>
                                    <td>@lang('site.brand_name')</td>
                                    <td>@lang('site.created_at')</td>
                                </tr>

                                @foreach($parents as $shop)
                                    <tr>
                                        <td>{{$shop->id}}</td>
                                        <td>{{$shop->name}}</td>
                                        <td>{{$shop->address}}</td>
                                        <td>{{$shop->mall->name ?? ''}}</td>
                                        <td>{{$shop->brand_name ?? ''}}</td>
                                        <td>{{$shop->created_at ?? ''}}</td>


                                    </tr>
                                @endforeach
                            </table>


                        </div>
                    </div>
                </div>
            </div>


            <!-- /Search Filter -->


        </div>
    </div>

@section('scripts')

