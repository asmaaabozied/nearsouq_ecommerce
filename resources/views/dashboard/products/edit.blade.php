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
                            <h4 class="card-title">@lang('site.options')</h4>
                            @include('partials._errors')

                            <form action="{{ route('dashboard.products.update', $product->id) }}"
                                  method="post" enctype="multipart/form-data">

                                {{ csrf_field() }}
                                {{ method_field('put') }}

                                <div class="row">
                                    <div class="col-md-6">


                                        <label>@lang('site.ar.name')</label>
                                        <input type="text" name="name_ar" class="form-control"
                                               value="{{ $product->name_ar ?? '' }}"  required >
                                    </div>
                                    <div class="col-md-6">

                                        <label>@lang('site.en.name')</label>
                                        <input type="text" name="name_en" class="form-control"
                                               value="{{ $product->name_en ?? '' }}"   required >
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col-md-6">


                                        <label>@lang('site.price')</label>
                                        <input required type="number" name="price" class="form-control"
                                               value="{{ $product->price ?? '' }}"  required   step="any"  min="1"  oninput="this.value=this.value.replace(/^0/g,'');">
                                    </div>
                                    <div class="col-md-6">

                                        <label>@lang('site.discount_price')</label>
                                        <input type="number" name="discount_price" class="form-control"
                                               value="{{ $product->discount_price ?? '' }}"   step="any"  min="1"  oninput="this.value=this.value.replace(/^0/g,'');">
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col-md-6">


                                        <label>@lang('site.unit')</label>
                                        <input type="text" name="unit" class="form-control"
                                               value="{{ $product->unit ?? '' }}">
                                    </div>
                                    <div class="col-md-6">

                                        <label>@lang('site.code')</label>
                                        <input type="text" name="code" class="form-control"
                                               value="{{ $product->code}}">
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col-md-6">

                                        <label>@lang('site.weight')</label>
                                        <input type="text" name="weight" class="form-control"
                                               value="{{ $product->weight ?? '' }}"  required >
                                    </div>
                                    <div class="col-md-6">


                                        <label>@lang('site.categories')</label>

                                        <select class="form-control"  name="category_id" required >
                                            @foreach(\App\Models\category::all() as $category)
                                                <option
                                                    value="{{ $category->id }}" {{$category->id == $product->category_id  ? 'selected' : ''}}>{{ $category->name}}</option>

                                            @endforeach
                                        </select>
                                    </div>


                                </div>


                                <br><br>
                                <div class="row">
                                    <div class="col-md-6">


                                        <label>@lang('site.ar.description')</label>

                                        <textarea id="w3review" name="desc_ar" rows="4" class="form-control" cols="50">{{ $product->desc_ar ?? '' }}
                       </textarea>

                                    </div>
                                    <div class="col-md-6">

                                        <label>@lang('site.en.description')</label>
                                        <textarea id="w3review" name="desc_en" class="form-control" rows="4" cols="50">{{ $product->desc_en ?? '' }}
                                </textarea>
                                    </div>


                                </div>

                                <div class="row">
                                    <div class="col-md-6">


                                        <label>@lang('site.ingredients_ar')</label>

                                        <textarea id="w3review" name="ingredients_ar" class="form-control" rows="4"
                                                  cols="50">{{ $product->ingredients_ar }}
                       </textarea>

                                    </div>
                                    <div class="col-md-6">

                                        <label>@lang('site.ingredients_en')</label>
                                        <textarea id="w3review" name="ingredients_en" class="form-control" rows="4"
                                                  cols="50">{{ $product->ingredients_en }}
                                </textarea>
                                    </div>


                                </div>


                                <br>
                                <div class="row">
                                    <div class="col-md-6">


                                        <label>@lang('site.mainimage')</label><br>
                                    <!--<input type="file" name="image" class="form-control"
                                               value="{{ old('image') }}">-->

                                        <label class="img-item" style="cursor: pointer">
                                            <input class="selector" id="image" name="image" type="file" hidden
                                                   onchange="readURL()" value="{{$product->image_path}}">
                                            <img name="soso" src="{{ $product->image_path }}" alt="" width="100px"
                                                 height="auto">
                                        </label>
                                        <input type="file" class="form-control" name='image' multiple>


                                    </div>
                                    <div class="row col-md-6">
                                        <label>@lang('site.imagess')</label><br>
                                        @foreach($product->images as $image)
                                            <div class="col-md-3 img-item" id="image-div{{$image->id}}">
                                                <label style="cursor: pointer">
                                                    <input class="selector" id="image{{$image->id}}" name='images[]'
                                                           type="file" hidden onchange="readURL()"
                                                           value="{{$image->id}}">
                                                    <img src="{{ asset('uploads/shops/products/'.$image->image) }}"
                                                         alt="" width="100px " height="auto">
                                                </label>
                                                <i class="delete-icon fa fa-trash"
                                                   onclick="deletedImage({{$image->id}})"></i>
                                            </div>
                                        @endforeach
                                        <input type="file" class="form-control" name='images[]' multiple>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <img src="{{ asset('public/uploads/images') }}" style="width: 100px"
                                             class="img-thumbnail image-preview" alt="">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">


                                        <label>@lang('site.productsrelated')</label>

                                        <select name="related_product_id[]" class="form-select" multiple
                                                aria-label="multiple select example">
                                            <option value="nodataselect"> @lang('site.nodataselect')</option>
                                            @foreach($productrelated as $product)
                                                <option value="{{$product->id}}"
                                                        @if(in_array($product->id,$related)) selected @endif>{{$product->name ?? ''}}</option>
                                            @endforeach
                                        </select>


                                    </div>

                                    <!--<div class="col-md-6">-->


                                <!--    <label>@lang('site.options')</label>-->

                                    <!--    <select name="option_id[]" class="form-select" multiple-->
                                    <!--            aria-label="multiple select example">-->
                                <!--        <option value="nodataselect"> @lang('site.nodataselect')</option>-->

                                <!--        @foreach($options as $option)-->
                                {{--            <option value="{{$option->id}}"  @if(in_array($option->id,$optionselected)) selected @endif>{{$option->reference_name ?? ''}}</option>--}}
                                <!--        @endforeach-->
                                    <!--    </select>-->


                                    <!--</div>-->

                                </div>


                                <br><br>
                                <div class="row">

                                    <div class="col-md-12">

                                        <div>
                                            <input type="button" id="add_new_fields" class="btn btn-primary"
                                                   value="@lang('site.add')@lang('site.options')">
                                        </div>


                                        <div id="fields">

                                            @foreach($optionselected as $option)
                                                <div id="fields_{{$option->id}}">
                                                <!--field_{{$option->id}}_items-->
                                                    <div id="field_item_{{$option->id}}" class="row mt-2 form-group">
                                                        <div class="col-md-1 p-0 m-0">
                                                        <!--<a onclick="removeField($option['id'])" href="{{url('dashboard/options',$option->id)}}"><i class="fas fa-trash"></i></a>-->
                                                            <a onclick="removeFields('{{$option->id}}')"
                                                               href="javascript:;"><i class="fas fa-trash"></i></a>
                                                            {{--                                                    <form action="{{url('dashboard/options',$option->id)}}" method="post" style="display: inline-block">--}}
                                                            {{--                                                     @csrf--}}
                                                            {{--                                                        <button type="submit" id="delete" class="delete" style="border: none;--}}
                                                            {{--    background: transparent;">--}}
                                                            {{--                                                            <i class="far fa-trash-alt me-1 fa-2x delete"></i>--}}
                                                            {{--                                                        </button>--}}
                                                            {{--                                                    </form>--}}
                                                        </div>

                                                        <div class="col">
                                                            <input type="text" class="form-control"
                                                                   name=fields[{{$option->id}}][ar][name][]
                                                                   value="{{$option['name_ar']}}"
                                                                   placeholder="@lang('site.ar.name')" required
                                                                   value=""/>
                                                        </div>

                                                        <div class="col">
                                                            <input type="text" class="form-control mb-1"
                                                                   name=fields[{{$option->id}}][en][name][]
                                                                   value="{{$option['name_en']}}"
                                                                   placeholder="@lang('site.en.name')" required
                                                                   value=""/>
                                                        </div>

                                                        <div class="col">
                                                            <input type="text" class="form-control mb-1"
                                                                   name=fields[{{$option->id}}][ar][reference_name][]
                                                                   value="{{$option['reference_name']}}"
                                                                   placeholder="@lang('site.reference_name')" value=""/>
                                                        </div>
                                                        <div class="col">


                                                            <select class="form-control"
                                                                    name="fields[{{$option->id}}][option_type][]"
                                                                    required style="height:42px">
                                                                <option disabled>@lang('site.type')</option>
                                                                <option
                                                                    value="OPTION" {{$option->type == 'OPTION'  ? 'selected' : ''}}>@lang('site.OPTION')</option>
                                                                <option
                                                                    value="REQUIRED" {{$option->type == 'REQUIRED'  ? 'selected' : ''}}>@lang('site.Required')</option>

                                                            </select>
                                                        </div>

                                                        <div id="field_{{$option->id}}_items" class="mt-2">
                                                            @foreach($option->variants as $variant)
                                                                <div id="itemFromFiled_{{$variant->id}}"
                                                                     class="row m-1 form-group">

                                                                    <div class="items">
                                                                        <div class="row m-1 form-group">
                                                                            <div class="col-md-1">
                                                                                -@lang('site.variants')

                                                                                <a onclick="removeItemFromFieldForMe('{{$option->id}}', '{{$variant->id}}')"
                                                                                   href="javascript:;"><i
                                                                                        class="fas fa-trash"></i></a>

                                                                            </div>


                                                                            <div class="col">
                                                                                <input type="text" class="form-control"
                                                                                       name="fields[{{$option->id}}][ar][value][]"
                                                                                       value="{{$variant['name_ar']}}"
                                                                                       placeholder="@lang('site.ar.name')"
                                                                                       required value=""/>
                                                                            </div>
                                                                            <div class="col">
                                                                                <input type="text"
                                                                                       class="form-control mb-1"
                                                                                       name="fields[{{$option->id}}][en][value][]"
                                                                                       value="{{$variant['name_en']}}"
                                                                                       placeholder="@lang('site.en.name')"
                                                                                       required value=""/>
                                                                            </div>
                                                                            <div class="col">
                                                                                <input type="number" step="any"
                                                                                       class="form-control"
                                                                                       name="fields[{{$option->id}}][add_price][]"
                                                                                       value="{{$variant['extra_price']}}"
                                                                                       placeholder="@lang('site.price')"
                                                                                       value=""/>
                                                                            </div>
                                                                            <input type="hidden"
                                                                                   value="{{$variant->id}}"
                                                                                   name="fields[{{$option->id}}][id][]">

                                                                            <div class="col">

                                                                                <input type="file" class="form-control"
                                                                                       name="fields[{{$option->id}}][images][]"
                                                                                       placeholder="@lang('site.image')"
                                                                                       value="{{$variant->image}}"/>
                                                                            </div>
                                                                            <div class="col">
                                                                                <div class="col-md-6">
                                                                                    <img
                                                                                        src="{{ asset('uploads/shops/options/'.$variant->image) }}"
                                                                                     
                                                                                        class="data img-responsive"
                                                                                        data-bs-toggle="modal"
                                                                                        data-bs-target="#exampleModalss">
                                                                                </div>
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
                                                                                                 src="{{ asset('uploads/shops/options/'.$variant->image) }}"
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
                                                            @endforeach

                                                        </div>
                                                    </div>
                                                    <div><input type="button"
                                                                onclick="addNewItemToField({{$option->id}})"
                                                                class="ml-5 mb-3 btn btn-info addNewItemToFieldBTN"
                                                                value="@lang('site.add_new_option')">

                                                    </div>
                                                </div>

                                            @endforeach

                                        </div>


                                    </div>


                                </div>
                                <br><br>


                                <select class="form-control" id="shops">
                                    @if (auth()->user()->hasRole('Vendor') || auth()->user()->hasRole('Merchant'))
                                        @foreach(auth()->user()->shops as $shop)
                                            <option value="{{$shop->id}}">{{$shop->name ?? ''}}</option>
                                        @endforeach
                                    @else

                                        @foreach(\App\Models\Shop::all() as $shop)
                                            <option value="{{$shop->id}}">{{$shop->name ?? ''}}</option>
                                        @endforeach
                                    @endif

                                </select>


                                <input type="number" id="input_quantity"/>

                                <input type="button" class="btn btn-primary" value="@lang('site.add_store')"
                                       onclick="addShopQuantity()">

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
                                    @foreach($shops as $Shop)

                                        <tr>
                                            <td><input type="hidden" name="shop_id[]" value="{{$Shop->id}}" hidden/>
{{--                                            <td> {{$Shop->name}}</td>--}}
                                                {{$Shop->name}}</td>

                                            <td><input type="text" name="quantity[]"
                                                       value="{{$Shop->pivot->quantity}}"/>
{{--                                                <input type="hidden" name="shop_id[]" value="{{$Shop->id}}" hidden/>--}}
                                            </td>
                                            <td>
                                                <a onclick="deleteRow(this)" style="border: none;
                                                    background: transparent;">
                                                    <i class="far fa-trash-alt me-1 fa-2x delete"></i>
                                                </a>
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


        function deletedImage(id) {
            document.getElementById('image-div' + id).style.display = "none";
            document.getElementById('image' + id).id = "";

            $.ajax({
                type: 'post',
                url: '/en/dashboard/deleteProductImage',
                data: {
                    'image_id': id,
                    _token: '{{csrf_token()}}'
                },
                success: function (result) {
                    console.log("deleted successfully");
                }
            });
        }
    </script>
@endsection

@section('scripts')

    <script type="text/javascript">
        $(function () {
            $("#shops option:selected").hide();
        });
    </script>
    <script type="text/javascript">


        $("#addRowCust").click(function () {
            var html = '<div class="col-md-12">';
            html += $(".copyThis").html();
            html += '</div>';

            $('#newRowCust').append(html);
        });

        $(document).ready(function () {
            jQuery('a.add-author').click(function (event) {
                event.preventDefault();
                var newRow = jQuery('<tr class="candidate"><td>' +
                    '<input type="text" name="options[].vname_ar[]"/>' +
                    '</td><td>' +
                    '<input type="text" name="options[].vname_en[]"/>' +
                    '</td><td>' +
                    '<input type="text" name="options[].extra_price[]"/>' +
                    '</td><td>' +
                    '<input type="file" name="images[]" class="form-control"/>' +
                    '</td>' +
                    '<td>' +
                    ' <a onclick="deleteRow(this)">' +
                    '<i class="far fa-trash-alt me-1 fa-2x delete"></i>' +
                    '</a>' +
                    '</td>' +
                    '</tr>');
                jQuery('table.authors-list').append(newRow);
            });
        });
    </script>

    <script>


        //      $("input#add_new_fieldss").click(function() {

        //             // let id = Math.floor((1 + Math.random()) * 0x10000)
        //             //     .toString(16)
        //             //     .substring(1);

        //          // var current = jQuery(this);
        //          // var url = current.data('url');
        //          let id = $(this).data('id');

        //          console.log("idd",id);

        //             $("div#fields").append(`
        //   <div id="field_item_` + id + `" class="row mt-2 form-group">
        //   <div class="col-md-1 p-0 m-0">
        //                         <a onclick="removeFields('` + id + `')" href="javascript:;"><i class="fas fa-trash"></i></a>
        //                     </div>
        //                     <div id="field_` + id + `_items" class="mt-2">
        //                         <div class="items">
        //                             <div class="row m-1 form-group">
        //                                 <div class="col-md-1">-@lang('site.variants')</div>
        //                                 <div class="col">
        //                                     <input type="text" class="form-control mb-1" name="fields[` + id + `][en][value][]" placeholder="@lang('site.en.name')" size="20" maxlength="20" required value="" />
        //                                 </div>
        //                                 <div class="col">
        //                                     <input type="text" class="form-control" name="fields[` + id + `][ar][value][]" placeholder="@lang('site.ar.name')" size="20" maxlength="20" required value="" />
        //                                 </div>
        //                                 <div class="col">
        //                                     <input type="number" step="any" class="form-control" name="fields[` + id + `][add_price][]" placeholder="@lang('site.price')"  value="" />
        //                                 </div>
        //                                 <div class="col">
        //                                     <input type="file" class="form-control" name="fields[` + id + `][image][]" placeholder="@lang('site.image')"  value="" />
        //                                 </div>
        //                             </div>
        //                             <!-- <div class="row m-1 form-group">
        //                                 <div class="col-md-1">-</div>
        //                                 <div class="col">
        //                                     <input type="text" class="form-control mb-1" name="fields[` + id + `][en][value][]" placeholder="@lang('site.en.name')" size="20" maxlength="20" required value="" />
        //                                 </div>
        //                                 <div class="col">
        //                                     <input type="text" class="form-control" name="fields[` + id + `][ar][value][]" placeholder="@lang('site.ar.name')" size="20" maxlength="20" required value="" />
        //                                 </div>
        //                                 <div class="col">
        //                                     <input type="number" step="any" class="form-control" name="fields[` + id + `][add_price][]" placeholder="@lang('site.price')"  value="" />
        //                                 </div>
        //                                 <div class="col">
        //                                     <input type="file" class="form-control" name="fields[` + id + `][image][]" placeholder="@lang('site.image')"  value="" />
        //                                 </div>
        //                                 </div>
        //                             </div> -->
        //                       <!--  // <div>
        //                         //     <input type="button" onclick="addNewItemToField('` + id + `')" class="ml-5 mb-3 btn btn-info" value="@lang('site.add_new_option')">
        //                         // </div>  <!--!>
        //                     </div>
        //                 </div>
        //             `);
        //         });


        $("input#add_new_fields").click(function () {

            let id = Math.floor((1 + Math.random()) * 0x10000)
                .toString(16)
                .substring(1);


            $("div#fields").append(`
                <div id="field_item_` + id + `" class="row mt-2 form-group">
                    <div class="col-md-1 p-0 m-0">
                        <a onclick="removeField('` + id + `')" href="javascript:;"><i class="fas fa-trash"></i></a>
                    </div>
                     <div class="col">
                        <input type="text" class="form-control" name="fields[` + id + `][ar][name][]" placeholder="@lang('site.ar.name')" size="20" maxlength="20" required value="" />
                    </div>

                    <div class="col">
                        <input type="text" class="form-control mb-1" name="fields[` + id + `][en][name][]" placeholder="@lang('site.en.name')" size="20" maxlength="20" required value="" />
                    </div>
                       <div class="col">
                        <input type="text" class="form-control mb-1" name="fields[` + id + `][ar][reference_name][]" placeholder="@lang('site.reference_name')" size="20" maxlength="20" required value="" />
                    </div>
                    <div class="col">
                        <select class="form-control" name="fields[` + id + `][option_type][]" required style="height:42px">
                            <option disabled>@lang('site.type')</option>
                            <option  value="OPTION">@lang('site.OPTION')</option>
                            <option value="REQUIRED">@lang('site.Required')</option>
                        </select>
                    </div>

                    <div id="field_` + id + `_items" class="mt-2">
                        <div class="items">
                            <div class="row m-1 form-group">
                                <div class="col-md-1">-@lang('site.variants')</div>

                                <div class="col">
                                    <input type="text" class="form-control" name="fields[` + id + `][ar][value][]" placeholder="@lang('site.ar.name')" size="20" maxlength="20" required value="" />
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control mb-1" name="fields[` + id + `][en][value][]" placeholder="@lang('site.en.name')" size="20" maxlength="20" required value="" />
                                </div>
                                <div class="col">
                                    <input type="number" step="any" class="form-control" name="fields[` + id + `][add_price][]" placeholder="@lang('site.price')"  value="" />
                                </div>
                                <div class="col">
                                    <input type="file" class="form-control" name="fields[` + id + `][images][]" placeholder="@lang('site.image')"  value="" />
                                </div>
                            </div>

                        </div>
                      <div>
                            <input type="button" onclick="addNewItemToFields('` + id + `')" class="ml-5 mb-3 btn btn-info" value="@lang('site.add_new_option')">
                        </div>
                    </div>
                </div>
            `);
        });

        function addNewItemToField(item_id) {
            let id = Math.floor((1 + Math.random()) * 0x10000)
                .toString(16)
                .substring(1);
            //   let item_id = Math.floor((1 + Math.random()) * 0x10000)
            // .toString(16)
            // .substring(1);
            $("div#fields div#field_" + item_id + "_items ").append(`
                <div id="itemFromFiled_` + id + `" class="row m-1 form-group">
                    <div class="col-md-1">-@lang('site.variants')

            <a onclick="removeItemFromFieldForMe('` + item_id + `', '` + id + `')" href="javascript:;"><i class="fas fa-trash"></i></a>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" name="fields[` + item_id + `][ar][value][]" placeholder="@lang('site.ar.name')"  required value="" />
                    </div>
                     <div class="col">
                        <input type="text" class="form-control mb-1" name="fields[` + item_id + `][en][value][]" placeholder="@lang('site.en.name')"  required value="" />
                    </div>
                    <div class="col">
                        <input type="number" step="any" class="form-control" name="fields[` + item_id + `][add_price][]" placeholder="@lang('site.price')"  value="" />
                    </div>
                    <div class="col">
                        <input type="file" class="form-control" name="fields[` + item_id + `][images][]" placeholder="@lang('site.image')"  value="" />
                    </div>
                </div>
            `);


        }

        function removeItemFromField(item_id, id) {
            console.log(item_id);
            $("div#fields div#field_" + item_id + "_items .items #itemFromFiled_" + id).remove();
        }


        function removeItemFromFieldForMe(item_id, id) {
            console.log(item_id);
            $("div#fields div#field_" + item_id + "_items #itemFromFiled_" + id).remove();
        }

        function removeField(field_item_id) {
            // console.log(field_item_id);
            $("div#fields #field_item_" + field_item_id).remove();
        }

        function removeFields(field_item_id) {
            // console.log(field_item_id);
            $("div#fields #fields_" + field_item_id).remove();
        }


    </script>

    <script type="text/javascript">


        function addNewItemToFields(item_id) {
            let id = Math.floor((1 + Math.random()) * 0x10000)
                .toString(16)
                .substring(1);
            $("div#fields div#field_" + item_id + "_items .items").append(`
                <div id="itemFromFiled_` + id + `" class="row m-1 form-group">
                    <div class="col-md-1">-@lang('site.variants')
            <a onclick="removeItemFromField('` + item_id + `', '` + id + `')" href="javascript:;"><i class="fas fa-trash"></i></a>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" name="fields[` + item_id + `][ar][value][]" placeholder="@lang('site.ar.name')"  required value="" />
                    </div>
                     <div class="col">
                        <input type="text" class="form-control mb-1" name="fields[` + item_id + `][en][value][]" placeholder="@lang('site.en.name')"  required value="" />
                    </div>
                    <div class="col">
                        <input type="number" step="any" class="form-control" name="fields[` + item_id + `][add_price][]" placeholder="@lang('site.price')"  value="" />
                    </div>
                    <div class="col">
                        <input type="file" class="form-control" name="fields[` + item_id + `][images][]" placeholder="@lang('site.image')"  value="" />
                    </div>
                </div>
            `);
        }

    </script>

@endsection



