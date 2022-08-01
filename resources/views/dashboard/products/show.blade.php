@extends('layouts.dashboard.app')

@section('content')

    <div class="page-wrapper" style="min-height: 422px;" data-select2-id="16">
        <div class="content container-fluid" data-select2-id="15">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">@lang('site.products')</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{route('dashboard.welcome') }}">@lang('site.dashboard')</li>
                            <li class="breadcrumb-item"><a
                                    hhref="{{ route('dashboard.products.index') }}"> @lang('site.products') </a></li>
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
                            <h4 class="card-title">@lang('site.options')</h4>


                            <div class="row">
                                <div class="col-md-6">


                                    <label>@lang('site.mainimage')</label><br>
                                <!--<input disabled type="file" name="image" class="form-control"
                                               value="{{ old('image') }}">-->

                                    <label class="img-item" style="cursor: pointer">
{{--                                        <input disabled class="selector" id="image" name="image" type="file" hidden onchange="readURL()" value="{{$product->image_path}}">--}}
                                        <img name="soso" src="{{ asset('uploads/shops/products/'.$product->image) }}" alt="" width="100px" height="auto"  data-bs-toggle="modal" data-bs-target="#exampleModal"
                                        class="data open-AddBookDialog"  data-id="{{ asset('uploads/shops/products/'.$product->image) }}"
                                        >
                                    </label>
{{--                                    <button type="button" class="btn btn-primary data" data-bs-toggle="modal" data-bs-target="#exampleModal" >--}}
{{--                                        @lang('site.show')--}}
{{--                                    </button>--}}

                                </div>

                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
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
                              <img name="soso" src="" alt="" width="400px" height="aut0" class="images">

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
                                <div class="row col-md-6">
                                    <label>@lang('site.imagess')</label><br>
                                    @foreach($product->images as $image)
                                        <div class="col-md-3 img-item" id="image-div{{$image->id}}">
                                            <label style="cursor: pointer">
{{--                                                <input disabled class="selector" id="image{{$image->id}}" name='images[]' type="file" hidden onchange="readURL()" value="{{$image->id}}">--}}
                                                <img src="{{ asset('uploads/shops/products/'.$image->image) }}" alt="" width="100px " height="auto" class="data open-AddBookDialog"  data-id="{{ asset('uploads/shops/products/'.$image->image) }}" data-bs-toggle="modal" data-bs-target="#exampleModal" >
                                            </label>
{{--                                            <i class="delete-icon fa fa-trash" onclick="deletedImage({{$image->id}})" ></i>--}}
                                        </div>
                                   
                                    @endforeach
{{--                                    <input disabled type="file" class="form-control"  name='images[]' multiple>--}}
                                </div>
                                <div class="form-group col-sm-6">
                                    <img src="{{ asset('public/uploads/images') }}" style="width: 100px"
                                         class="img-thumbnail image-preview" alt="">
                                </div>
                            </div>

                                <div class="row">
                                    <div class="col-md-6">


                                        <label>@lang('site.ar.name')</label>
                                        <input disabled type="text" name="name_ar" class="form-control"
                                               value="{{ $product->name_ar ?? '' }}">
                                    </div>
                                    <div class="col-md-6">

                                        <label>@lang('site.en.name')</label>
                                        <input disabled type="text" name="name_en" class="form-control"
                                               value="{{ $product->name_en ?? '' }}">
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col-md-6">


                                        <label>@lang('site.price')</label>
                                        <input disabled type="number" name="price" class="form-control"
                                               value="{{ $product->price ?? '' }}">
                                    </div>
                                    <div class="col-md-6">

                                        <label>@lang('site.discount_price')</label>
                                        <input disabled type="number" name="discount_price" class="form-control"
                                               value="{{ $product->discount_price ?? '' }}">
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col-md-6">


                                        <label>@lang('site.unit')</label>
                                        <input disabled type="text" name="unit" class="form-control"
                                               value="{{ $product->unit ?? '' }}">
                                    </div>
                                    <div class="col-md-6">

                                        <label>@lang('site.code')</label>
                                        <input disabled type="text" name="code" class="form-control"
                                               value="{{ $product->code ?? ''}}">
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col-md-6">

                                        <label>@lang('site.weight')</label>
                                        <input disabled type="text" name="weight" class="form-control"
                                               value="{{ $product->weight ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        @php
                                        $name=  'name_' . app()->getLocale();
                                        @endphp

                                        <label>@lang('site.categories')</label>
                                            <input disabled type="text" name="category" class="form-control"
                                                            value="{{ $product->category->$name ?? '' }}">
                                       
                                    </div>


                                </div>



                                <br><br>
                                <div class="row">
                                    <div class="col-md-6">


                                        <label>@lang('site.ar.description')</label>

                                        <textarea disabled id="w3review" name="desc_ar" rows="4" class="form-control" cols="50">{{ $product->desc_ar ?? '' }}
                       </textarea>

                                    </div>
                                    <div class="col-md-6">

                                        <label>@lang('site.en.description')</label>
                                        <textarea disabled id="w3review" name="desc_en" class="form-control" rows="4" cols="50">{{ $product->desc_en ?? '' }}
                                </textarea>
                                    </div>


                                </div>

                                <div class="row">
                                    <div class="col-md-6">


                                        <label>@lang('site.ingredients_ar')</label>

                                        <textarea disabled id="w3review" name="ingredients_ar" class="form-control" rows="4"
                                                  cols="50">{{ $product->ingredients_ar ?? '' }}
                       </textarea>

                                    </div>
                                    <div class="col-md-6">

                                        <label>@lang('site.ingredients_en')</label>
                                        <textarea disabled id="w3review" name="ingredients_en" class="form-control" rows="4"
                                                  cols="50">{{ $product->ingredients_en ?? '' }}
                                </textarea>
                                    </div>


                                </div>


                                <br>


                                <div class="row">
                                    <div class="col-md-6">


                                        <label>@lang('site.productsrelated')</label>

                                        <select disabled  name="related_product_id[]" class="form-select" multiple
                                                aria-label="multiple select example">
                                            @foreach($related as $rproduct)
                                                <option value="{{$product->id}}" >{{$rproduct->name ?? ''}}</option>
                                            @endforeach
                                        </select>



                                    </div>



                                </div>


                                <br><br>
                                <div class="row">

                                    <div class="col-md-12">

                                        <div>
                                           <button class="btn btn-primary">@lang('site.options')</button>
                                        </div>


                                        <div id="fields" >

@foreach($optionselected as $option)
<div id="fields_{{$option->id}}" >
<!--field_{{$option->id}}_items-->
                                            <div id="field_item_{{$option->id}}" class="row mt-2 form-group">
                                                <div class="col-md-1 p-0 m-0">
                                                    <!--<a onclick="removeField($option['id'])" href="{{url('dashboard/options',$option->id)}}"><i class="fas fa-trash"></i></a>-->
{{--                                        <a onclick="removeFields('{{$option->id}}')" href="javascript:;"><i class="fas fa-trash"></i></a>--}}
{{--                                                    <form action="{{url('dashboard/options',$option->id)}}" method="post" style="display: inline-block">--}}
{{--                                                     @csrf--}}
{{--                                                        <button type="submit" id="delete" class="delete" style="border: none;--}}
{{--    background: transparent;">--}}
{{--                                                            <i class="far fa-trash-alt me-1 fa-2x delete"></i>--}}
{{--                                                        </button>--}}
{{--                                                    </form>--}}
                                                </div>

                                                 <div class="col">
                                                    <input disabled type="text" class="form-control" name=fields[{{$option->id}}][ar][name][]    value="{{$option['name_ar']}}" placeholder="@lang('site.ar.name')"  required value="" />
                                                </div>

                                                <div class="col">
                                                    <input disabled type="text" class="form-control mb-1" name=fields[{{$option->id}}][en][name][] value="{{$option['name_en']}}" placeholder="@lang('site.en.name')"  required value="" />
                                                </div>

                                                <div class="col">
                                                    <input disabled type="text" class="form-control mb-1" name=fields[{{$option->id}}][ar][reference_name][]     value="{{$option['reference_name']}}" placeholder="@lang('site.reference_name')"  value="" />
                                                </div>
                                                <div class="col">



                                                    <select class="form-control" name="fields[{{$option->id}}][option_type][]" required style="height:42px">
                                                        <option disabled>@lang('site.type')</option>
                                                         <option value="OPTION" {{$option->type == 'OPTION'  ? 'selected' : ''}}>@lang('site.OPTION')</option>
                                                         <option value="REQUIRED" {{$option->type == 'REQUIRED'  ? 'selected' : ''}}>@lang('site.Required')</option>

                                                    </select>
                                                </div>

                                                <div id="field_{{$option->id}}_items" class="mt-2">
                                                    @foreach($option->variants as $variant)
                                                                    <div id="itemFromFiled_{{$variant->id}}" class="row m-1 form-group">

                                                    <div class="items">
                                                        <div class="row m-1 form-group">
                                                            <div class="col-md-1">-@lang('site.variants')

{{--                                                             <a onclick="removeItemFromFieldForMe('{{$option->id}}', '{{$variant->id}}')" href="javascript:;"><i class="fas fa-trash"></i></a>--}}

                                                            </div>



                                                            <div class="col">
                                                                <input disabled type="text" class="form-control" name="fields[{{$option->id}}][ar][value][]"     value="{{$variant['name_ar']}}" placeholder="@lang('site.ar.name')"  required value="" />
                                                            </div>
                                                            <div class="col">
                                                                <input disabled type="text" class="form-control mb-1" name="fields[{{$option->id}}][en][value][]"   value="{{$variant['name_en']}}" placeholder="@lang('site.en.name')"  required value="" />
                                                            </div>
                                                            <div class="col">
                                                                <input disabled type="number" step="any" class="form-control" name="fields[{{$option->id}}][add_price][]"  value="{{$variant['extra_price']}}" placeholder="@lang('site.price')"  value="" />
                                                            </div>
                                                            <div class="col">
                                                                   <div class="col-md-6">
                                                                <img src="{{ asset('uploads/shops/options/'.$variant->image) }}" alt="" width="50px " height="50px"  data-bs-toggle="modal" data-bs-target="#exampleModal"
                                                                
                                                                class="data open-AddBookDialog"  data-id="{{ asset('uploads/shops/options/'.$variant->image) }}" 
                                                                >
                                                                
                                                                </div>

{{--                                                                <input disabled type="file" class="form-control" name="fields[{{$option->id}}][image][]" placeholder="@lang('site.image')"  value="" />--}}
                                                            </div>

                                                        </div>
                                                        </div>

                                                    </div>

                                                

                                                    @endforeach

                                                </div>
                                            </div>
{{--         <div>  <input disabled type="button" onclick="addNewItemToField({{$option->id}})" class="ml-5 mb-3 btn btn-info addNewItemToFieldBTN" value="@lang('site.add_new_option')">--}}

                                                    </div>
                                                    </div>

@endforeach

                                        </div>



                                    </div>



                                </div>
                                <br><br>

                                <table id="table5" class="table" border="1">
                                    <thead>
                                    <tr>
                                        <!--<th scope="col">Select</th>-->
                                        <th scope="col">@lang('site.shops')</th>
                                        <th scope="col">@lang('site.quantity')</th>
                                        <th scope="col">@lang('site.action')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($product->Shops as $Shop)

                                        <tr>
                                            <td><input disabled type="hidden" name="shop_id[]"  value="{{$Shop->id}}" hidden />
                                                {{$Shop->name}}
                                            </td>
                                            <td><input disabled type="text" name="quantity[]"
                                                       value="{{$Shop->pivot->quantity}}"/></td>
                                            <td>

                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
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

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
         
            $(".open-AddBookDialog").click(function () {
                
                //images path in data_id
                console.log($(this).data('id'))
                // $('.images').src();
                $(".images").attr("src",$(this).data('id'));
             
            });
        });
    </script>

@endsection



