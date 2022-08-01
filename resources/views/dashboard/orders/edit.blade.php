@extends('layouts.dashboard.app')

@section('content')

<div class="page-wrapper" style="min-height: 422px;" data-select2-id="16">
    <div class="content container-fluid" data-select2-id="15">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-title">@lang('site.orderDetails')</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('dashboard.welcome') }}">@lang('site.dashboard')</li>
                        <li class="breadcrumb-item"><a hhref="{{ route('dashboard.orderDetail.index') }}"> @lang('site.orderDetails') </a></li>
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
                        <h4 class="card-title">@lang('site.orderDetails')</h4>
                        @include('partials._errors')


                        <form action="{{ route('dashboard.orderDetail.update', $orderDetail->id) }}" method="post" enctype="multipart/form-data">

                            {{ csrf_field() }}
                            {{ method_field('put') }}

                            <div class="row">
                                <div class="col-md-6">
                                    <label>@lang('site.ar.name')</label>
                                    <input type="text" name="name_ar" class="form-control" value="{{ $orderDetail->name_ar }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label>@lang('site.en.name')</label>
                                    <input type="text" name="name_en" class="form-control" value="{{ $orderDetail->name_en }}" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>@lang('site.status')</label>
                                    <select class="form-control" name="status">
                                        <option value="NOT_DELIVERED" @if($orderDetail->status =='NOT_DELIVERED') selected @endif> @lang('site.NOT_DELIVERED') </option>
                                        <option value="RECEIVED" @if($orderDetail->status =='RECEIVED') selected @endif> @lang('site.RECEIVED') </option>
                                        <option value="READY" @if($orderDetail->status =='READY') selected @endif> @lang('site.READY') </option>
                                        <option value="SHIPPED" @if($orderDetail->status =='SHIPPED') selected @endif> @lang('site.SHIPPED') </option>
                                        <option value="DELIVERED" @if($orderDetail->status =='DELIVERED') selected @endif> @lang('site.DELIVERED') </option>
                                        <option value="CANCELED" @if($orderDetail->status =='CANCELED') selected @endif> @lang('site.CANCELED') </option>
                                        <option value="RETURNED" @if($orderDetail->status =='RETURNED') selected @endif> @lang('site.RETURNED') </option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>@lang('site.product')</label>
                                    <select class="form-control" name="product_id" disabled>
                                        @foreach($products as $product)
                                        <option value="{{$product->id}}" @if($orderDetail->product_id == $product->id) selected @endif> {{$product->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>@lang('site.price')</label>
                                    <input type="text" name="price" class="form-control" value="{{ $orderDetail->price }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label>@lang('site.discount_price')</label>
                                    <input type="text" name="discount_price" class="form-control" value="{{ $orderDetail->discount_price }}" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>@lang('site.vat')</label>
                                    <input type="text" name="vat" class="form-control" value="{{ $orderDetail->vat }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label>@lang('site.vat_value')</label>
                                    <input type="text" name="vat_value" class="form-control" value="{{ $orderDetail->vat_value }}" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>@lang('site.commsion')</label>
                                    <input type="text" name="commsion" class="form-control" value="{{ $orderDetail->commsion }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label>@lang('site.commsion_value')</label>
                                    <input type="text" name="commsion_value" class="form-control" value="{{ $orderDetail->commsion_value }}" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>@lang('site.quantity')</label>
                                    <input type="text" name="quantity" class="form-control" value="{{ $orderDetail->quantity }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label>@lang('site.delivery_date')</label>
                                    <input type="date" name="delivery_date" class="form-control" value="{{ $orderDetail->delivery_date }}" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>@lang('site.reason')</label>
                                    <input type="text" name="reason_id" class="form-control" value="{{ $orderDetail->reason_id }}" disabled>
                                </div>
                            </div>
                            <br>
                            <br>

                            <a href="#" title="" class="add-author">@lang('site.add')</a>
                            <br>




                            <div class="text-end mt-4">
                                <div class="form-group">
                                    <button type="button" class="btn btn-warning mr-1" onclick="history.back();">
                                        <i class="fa fa-backward"></i> @lang('site.back')
                                    </button>
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i>
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
    $("#add").click(function() {
        $("#rows").append('<input type="text" name="price[]">');
    });

</script>


@endsection
