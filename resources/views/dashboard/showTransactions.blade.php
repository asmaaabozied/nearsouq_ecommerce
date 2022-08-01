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

                        <h3 class="page-title">@lang('site.transactions')</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{route('dashboard.welcome') }}">@lang('site.dashboard')</a></li>

                            <li class="breadcrumb-item active">@lang('site.shops')({{$transactions->count()}})</li>
                        </ul>
                    </div>
                    <div class="col-auto">

                    </div>
                </div>
            </div>
            <!-- /Page Header -->

        <!-- Search Filter -->
            <div class="row" data-select2-id="14">
                <div class="col-md-12">
                    <div class="card">


                        <div class="card-body">


                            <table class="col-md-12">
                                <tr>

                                    <td>@lang('site.id')</td>
                                    <td>@lang('site.name')</td>
                                    <td>@lang('site.debit')</td>
                                    <td>@lang('site.credit')</td>
                                    <td>@lang('site.final_balance')</td>
                                    <td>@lang('site.transaction_date')</td>
                                    <td>@lang('site.username')</td>
                                    <td>@lang('site.order')</td>
                                </tr>

                                @foreach($transactions as $transaction)
                                    <tr>
                                        <td>{{$transaction->id}}</td>
                                        <td>{{$transaction->name}}</td>
                                        <td>{{$transaction->debit ?? '0'}}</td>
                                        <td>{{$transaction->credit ?? '0'}}</td>
                                        <td>{{$transaction->final_balance ?? '0'}}</td>
                                        <td>{{$transaction->transaction_date ?? ''}}</td>
                                        <td>{{$transaction->user_name ?? ''}}</td>
                                        <td> <a href="{{route('dashboard.order.details',$transaction->order_id)}}" >
                                            <i class="far fa-eye me-1 fa fa-2x"></i>
                                                </a>
                                        </td>


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

