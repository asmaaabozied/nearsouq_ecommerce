@extends('layouts.dashboard.app')

@section('content')

    <div class="page-wrapper" style="min-height: 422px;" data-select2-id="16">
        <div class="content container-fluid" data-select2-id="15">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">@lang('site.categories')</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{route('dashboard.welcome') }}">@lang('site.dashboard')</li>
                            <li class="breadcrumb-item"><a
                                    hhref="{{ route('dashboard.categories.index') }}"> @lang('site.categories') </a></li>
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
                            <h4 class="card-title">@lang('site.categories')</h4>
                            @include('partials._errors')


                            <form action="{{ route('dashboard.categories.update', $category->id) }}"
                                  method="post" enctype="multipart/form-data">

                                {{ csrf_field() }}
                                {{ method_field('put') }}

                                <div class="row">
                                    <div class="col-md-6">
                                        <label>@lang('site.ar.name')</label>
                                        <input type="text" name="name_ar" class="form-control"
                                               value="{{ $category->name_ar }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label>@lang('site.en.name')</label>
                                        <input type="text" name="name_en" class="form-control"
                                               value="{{ $category->name_en }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>@lang('site.ar.description')</label>
                                        <input type="text" name="description_ar" class="form-control"
                                               value="{{ $category->description_ar }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label>@lang('site.en.description')</label>
                                        <input type="text" name="description_en" class="form-control"
                                               value="{{ $category->description_en }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>@lang('site.created_by')</label>
                                        <input type="text" name="created_by" class="form-control"
                                               value="@if(isset($mall->user_name))
                                               {{$mall->user_name->name}}
                                               @endif" disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>@lang('site.category')</label>
                                        <select class="form-control" name="parent">
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}" @if($category->parent ==$category->id) selected @endif> {{$category->name_ar}} </option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label>@lang('site.visible')</label>
                                        <select class="form-control" name="status">
                                            <option value="1" @if($category->status ==1) selected @endif> yes </option>
                                            <option value="0" @if($category->status ==0) selected @endif> no </option>

                                        </select>
                                    </div>
                                </div>
<br><br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>@lang('site.image')</label>
                                     <input type="file" name="image" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label>@lang('site.image')</label>
                                        <img
                                            src="{{$image}}"
                                        
                                            data-bs-toggle="modal"
                                            data-bs-target="#exampleModalss" width="100px" height="100px">
                                    </div>
                                </div>

                                <br>
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
                                                                 src="{{$image}}"
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
@section('script')
    <script>


        $("#add").click(function(){
            $("#rows").append('<input type="text" name="price[]">');
        });

    </script>


@endsection


