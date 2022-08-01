@extends('layouts.dashboard.app')
 <script src="{{ asset('dashboard_files/js/jquery.min.js') }}"></script>
<style>

        .modal-dialog {
            max-width: 10000px;
            margin: 1.75rem auto;
        },
        
        
      .signup_errors {
            
   
    background-color: antiquewhit
        }
        
        
        
        #spinnerss {
  position: fixed;
  top: 0; left: 0; z-index: 9999;
  width: 100vw; height: 100vh;
  background: rgba(0, 0, 0, 0.7);
  transition: opacity 0.2s;
}
 
/* (B) CENTER LOADING SPINNER */
#spinnerss img {
  position: absolute;
  top: 50%; left: 50%;
  transform: translate(-50%);
}
 

</style>
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
                            <h4 class="card-title">@lang('site.products')</h4>
                            @include('partials._errors')

                            
                               <form   enctype="multipart/form-data" method="post"  id="add-form">
                                   
                    
                                       
                                           <ul  id="signup_errors" class="signup_errors"></ul>

 


                                <div class="row">
                                    <div class="col-md-6">


                                        <label>@lang('site.ar.name')</label>
                                        <input type="text" name="name_ar" class="form-control"
                                               value="{{ old('name_ar') }}">
                                    </div>
                                    <div class="col-md-6">

                                        <label>@lang('site.en.name')</label>
                                        <input type="text" name="name_en" class="form-control"
                                               value="{{ old('name_en') }}"  required >
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col-md-6">


                                        <label>@lang('site.price')</label>
                                        <input type="number" required name="price" class="form-control"
                                               value="{{ old('price') }}"   required  step="any" min="1" max="13456656765"  oninput="this.value=this.value.replace(/^0/g,'');" >
                                    </div>
                                    <div class="col-md-6">

                                        <label>@lang('site.discount_price')</label>
                                        <input type="number" name="discount_price" class="form-control"
                                               value="{{ old('discount_price') }}" step="any"  min="1" max="13456656765"  oninput="this.value=this.value.replace(/^0/g,'');" >
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col-md-6">


                                        <label>@lang('site.unit')</label>
                                        <input type="text" name="unit" class="form-control"
                                               value="{{ old('unit') }}"   required >
                                    </div>
                                    <div class="col-md-6">

                                        <label>@lang('site.code')</label>
                                        <input type="text" name="code" class="form-control"
                                               value="{{  $code }}">
                                    </div>


                                </div>
                                <div class="row">
                                    <div class="col-md-6">

                                        <label>@lang('site.weight')</label>
                                        <input type="text" name="weight" class="form-control"
                                               value="{{ old('weight') }}">
                                    </div>
                                    <div class="col-md-6">


                                        <label>@lang('site.categories')</label>

                                        <select class="form-control"  name="category_id"   required >
                                            <option   disabled>@lang('site.select')</option>
                                            @foreach(\App\Models\category::all() as $category)
                                                <option value="{{$category->id}}">{{$category->name ?? ''}}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                </div>


                                <br><br>
                                <div class="row">
                                    <div class="col-md-6">


                                        <label>@lang('site.ar.description')</label>

                                        <textarea id="desc_ar" class="form-control" name="desc_ar" rows="4"
                                                  cols="50">{{ old('desc_ar') }}</textarea>

                                    </div>
                                    <div class="col-md-6">

                                        <label>@lang('site.en.description')</label>
                                        <textarea id="desc_en" class="form-control" name="desc_en" rows="4"
                                                  cols="50">{{ old('desc_en') }}</textarea>
                                    </div>


                                </div>


                                <div class="row">
                                    <div class="col-md-6">


                                        <label>@lang('site.ingredients_ar')</label>

                                        <textarea id="ingredients_ar" class="form-control" name="ingredients_ar"
                                                  rows="4" cols="50">{{ old('ingredients_ar') }}</textarea>

                                    </div>
                                    <div class="col-md-6">

                                        <label>@lang('site.ingredients_en')</label>
                                        <textarea id="ingredients_en" class="form-control" name="ingredients_en"
                                                  rows="4" cols="50">{{ old('ingredients_en') }}</textarea>
                                    </div>


                                </div>


                                <div class="row">

                                    <div class="col-md-6">


                                        <label>@lang('site.productsrelated')</label>

                                        <select name="related_product_id[]" class="form-select" multiple
                                                aria-label="multiple select example">
                                            <option value="nodataselect"> @lang('site.nodataselect')</option>
                                            @foreach($productrelated as $product)
                                                <option value="{{$product->id}}">{{$product->name ?? ''}}</option>
                                            @endforeach
                                        </select>


                                    </div>


                                    <!--<div class="col-md-6">-->


                                    <!--    <label>@lang('site.options')</label>-->


                                    <!--    <select name="option_id[]" class="form-select" multiple-->
                                    <!--            aria-label="multiple select example">-->

                                    <!--        <option value="nodataselect"> @lang('site.nodataselect')</option>-->

                                    <!--        @foreach($options as $option)-->
                                    <!--            <option-->
                                    <!--                value="{{$option->id}}">{{$option->reference_name ?? ''}}</option>-->
                                    <!--        @endforeach-->
                                    <!--    </select>-->


                                    <!--</div>-->


                                </div>
                                <br>
                                <br>

                                <div class="row">

                                    <div class="col-md-12">

                                        <div>
                                            <input type="button" id="add_new_fields" class="btn btn-primary"
                                                   value="@lang('site.add')@lang('site.options')">
                                        </div>


                                        <div id="fields" > </div>

                                <br><br>



  <div class="row">
      <div class="col-md-4">
          </div>
        <div class="col-md-4">

  <!--                              <div id='loadingmessage' style='display:none;background-color: #282a6485'>-->
  <!--                                   <i class="fa fa-spinner fa-spin" style="font-size:100px"></i>-->
  <!--</div>  -->
  
<div id="spinnerss" style='display:none'>
  <img src="{{asset('uploads/loading.gif')}}"/>
</div>
</div>
    <div class="col-md-4">
          </div>
</div>
</div>
<br><br>

                                <div class="row">

                                    <div class="col-md-6">


                                        <label>@lang('site.mainimage')</label>
                                        <input type="file" required name="image" class="form-control"
                                               value="{{ old('image') }}">

                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>@lang('site.imagess')</label>
                                        <input type="file" class="form-control" name='images[]' multiple>
                                    </div>

                                </div>


                                <br>
                                <br>


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
                                    <tr>

                                    </tr>
                                    </tbody>
                                </table>
                                <br>


                                <div class="text-end mt-4">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-warning mr-1"
                                                onclick="history.back();">
                                            <i class="fa fa-backward"></i> @lang('site.back')
                                        </button>
                                        <button  type="submit" class="btn btn-primary btn-submit" id="btn-submit"><i
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
@section('scripts')

<script>
//     function addShopQuantity() {
//         var shop_id = $("#shops").val();
//         var quantity = $("#input_quantity").val();



//         var shop_name = $("#shops option:selected").html();


// //   alert(shop_name);
// //   return;
//         if (quantity == "") {
//             $("#input_quantity").addClass("red-border");
//         } else {

//             // if ($('#table5 tr:contains("' + cat +'")').length > 0) {
//             //   alert("found duplicate values");
//             // } else {
//             if ($('#table5 tr:contains("' + shop_id +'")').length > 0) {
//                 alert("found duplicate values");
//             } else {
//                 var markup =
//                     "<tr>" +
//                     "<td>" +
//                     shop_name +
//                     "</td>"+
//                     "<input type='hidden' name='shop_id[]' value="+shop_id+"> " +
//                     // shop_id +
//                     "<td>" +


//                     "<input type='text' name='quantity[]' value="+quantity+"> " +
//                     // amt +

//                     "</td>" +
//                     '<td>' +
//                     ' <a onclick="deleteRow(this)">' +
//                     '<i class="far fa-trash-alt me-1 fa-2x delete"></i>' +
//                     '</a>' +
//                     '</td>' +

//                     "</tr>";
//                 $("#table5 tbody").append(markup);
//                 $("#shops option:selected").hide();
//             }
//         }

//     }
</script>
<script type="text/javascript">
  $(document).ready(function(){
    // document.getElementById('discount').change = function() {
                       jQuery('input.discount_price').change(function () {
event.preventDefault();

            // let discount = document.getElementById("discount");
                 var discount = jQuery('#discount').val();
            
        console.log('ddd',discount);
        }
  }

$( "#discount" ).keydown(function( event ) {
    console.log(event);
  if ( event.which == 13 ) {
   event.preventDefault();
  }


    $("#addRowCust").click(function () {
        var html = '<div class="col-md-12">';
        html += $(".copyThis").html();
        html += '</div>';

        $('#newRowCust').append(html);
    });

    $(document).ready(function(){
        jQuery('a.add-author').click(function(event){
            event.preventDefault();
            var newRow = jQuery('<tr class="candidate"><td>' +
                '<input type="text" name="options[].vname_ar[]"/>' +
                '</td><td>' +
                '<input type="text" name="options[].vname_en[]"/>' +
                '</td><td>' +
                '<input type="text" name="options[].extra_price[]"/>' +
                '</td><td>' +
                '<input type="file" name="image[]" class="form-control"/>' +
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
    $("input#add_new_fields").click(function() {

        let id = Math.floor((1 + Math.random()) * 0x10000)
            .toString(16)
            .substring(1);


        $("div#fields").append(`
                <div id="field_item_` + id + `" class="row mt-2 form-group">
                    <div class="col-md-1 p-0 m-0">
                        <a onclick="removeField('` + id + `')" href="javascript:;"><i class="fas fa-trash"></i></a>
                    </div>


                    <div class="col">
                        <input type="text" class="form-control" name="fields[` + id + `][ar][name][]" placeholder="@lang('site.ar.name')"   required value="{{ old('fields` + id + `.ar.name') }}" />
                    </div>
                      <div class="col">
                        <input type="text" class="form-control mb-1" name="fields[` + id + `][en][name][]" placeholder="@lang('site.en.name')"  required value="" />
                    </div>

                       <div class="col">
                        <input type="text" class="form-control mb-1" name="fields[` + id + `][ar][reference_name][]" placeholder="@lang('site.reference_name')"  value="" />
                    </div>
                    <div class="col">
                        <select class="form-control" name="fields[` + id + `][option_type][]"  style="height:42px">
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
                                    <input type="text" class="form-control" name="fields[` + id + `][ar][value][]" placeholder="@lang('site.ar.name')"  required value="" />
                                </div>
                                 <div class="col">
                                    <input type="text" class="form-control mb-1" name="fields[` + id + `][en][value][]" placeholder="@lang('site.en.name')"  required value="" />
                                </div>
                                <div class="col">
                                    <input type="number" step="any" class="form-control" name="fields[` + id + `][add_price][]" placeholder="@lang('site.price')"  value="" />
                                </div>
                                <div class="col">
                                    <input type="file" class="form-control" name="fields[` + id + `][images][]" placeholder="@lang('site.image')"  value="" />
                                </div>
                            </div>
                            <!-- <div class="row m-1 form-group">
                                <div class="col-md-1">-</div>

                                <div class="col">
                                    <input type="text" class="form-control" name="fields[` + id + `][ar][value][]" placeholder="@lang('site.ar.name')"  required value="" />
                                </div>
                                 <div class="col">
                                    <input type="text" class="form-control mb-1" name="fields[` + id + `][en][value][]" placeholder="@lang('site.en.name')"  required value="" />
                                </div>
                                <div class="col">
                                    <input type="number" step="any" class="form-control" name="fields[` + id + `][add_price][]" placeholder="@lang('site.price')"  value="" />
                                </div>
                                <div class="col">
                                    <input type="file" class="form-control" name="fields[` + id + `][images][]" placeholder="@lang('site.image')"  value="" />
                                </div>
                            </div> -->
                        </div>
                        <div>
                            <input type="button" onclick="addNewItemToField('` + id + `')" class="ml-5 mb-3 btn btn-info" value="@lang('site.add_new_option')">
                        </div>
                    </div>
                </div>
            `);
    });

    function addNewItemToField(item_id) {
        let id = Math.floor((1 + Math.random()) * 0x10000)
            .toString(16)
            .substring(1);
        $("div#fields div#field_" + item_id + "_items .items").append(`
                <div id="itemFromFiled_` + id + `" class="row m-1 form-group">
                    <div class="col-md-1">
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

    function removeItemFromField(item_id, id) {
        // console.log(item_id);
        $("div#fields div#field_" + item_id + "_items .items #itemFromFiled_" + id).remove();
    }

    function removeField(field_item_id) {
        // console.log(field_item_id);
        $("div#fields #field_item_" + field_item_id).remove();
    }





</script>
<script type="text/javascript">

 $.ajaxSetup({
        headers: {
                     'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }
    });

    // $("#btn-submit").click(function(e){
        
          $("#add-form").submit(function(e){

        
             $('#spinnerss').show();

        e.preventDefault();

        var data = $("#add-form").serialize();
         

        console.log(data);
        var url = '{{route('dashboard.products.store')}}';

        $.ajax({
           url:url,
           method:'POST',
        cache: false,
   processData:false,
   contentType: false,
        //enctype: 'multipart/form-data',
           data: new FormData(document.getElementById("add-form")),
           success:function(response){
          
              if(response.success){
                     $('#spinnerss').hide();
                    window.location.href ='{{route('dashboard.products.index')}}';
                  
                 
              }else{
                  alert("Error")
              }
           },
           error:function(result){
                 $('#spinnerss').hide();
              console.log(result)
                  var errors = result.responseJSON;
                    var errorsList = "";
                    $.each(errors, function (_, value) {
                        $.each(value, function (_, fieldErrors) {
                            fieldErrors.forEach(function (error) {
                                errorsList += "<span style='color:red'>" + error + "<br></span>";
                            })
                        });
                    });
                    $('#signup_errors').html(errorsList).style(' background-color: antiquewhit');
           }
        });
	});

</script>

//     <script>
// //-------------------insert data to database ------------------------------
// var frm = $('#ProductRegisterForm');
// // console.log(frm.serialize());
// // return false;
// $.ajax({
//         headers: {
            // 'X-CSRF-TOKEN': "{{ csrf_token() }}"
//         },
//         type: 'POST',
//         url: "{{route('dashboard.products.store')}}",

//         data: frm.serialize(),
//         success: function (response) {
          
//                 var response = JSON.parse(response);
//                 // selecting values from response Object
//                 var status = response.status;



//             }
//         },
//         error: function (e) {
//             console.log(e);
//         }
//     }
// );

   <script type="text/javascript">
    //     function checkArray() {
    //         let str = 'This is a string';
    //         let num = 25;
    //         let arr = [10, 20, 30, 40];
  
    //         ans = str.constructor === Array;
    //         document.querySelector(
    //           '.outputString').textContent = ans;
            
    //         ans = num.constructor === Array;
    //         document.querySelector(
    //           '.outputNumber').textContent = ans;
            
    //         ans = arr.constructor === Array;
    //         document.querySelector(
    //           '.outputArray').textContent = ans;
            
    //     }
        
    //     function getScript(source, callback) {
    // var el = document.createElement('script');
    // el.onload = callback;
    // el.src = source;
    
    // document.body.appendChild(el);
}


function processRecords (records) {
    if (!_.isArray(fields)) {
        console.log(`Array provided array not valid`);
        return;
    }

    if (!fields.length) {
        console.log(`No records to process`);
        return;
    }
  
    fields.forEach(record => {
      console.log(`The record is: ${record}`);
    });
}
    </script>

//     </script>

@endsection
