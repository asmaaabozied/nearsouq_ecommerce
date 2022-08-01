@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.orders')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.orders')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header with-border">

                    <h3 class="box-title" style="margin-bottom: 15px">@lang('site.orders') <small>{{ $orders->total() }}</small></h3>

                    <form action="{{ route('dashboard.orderDetail.index') }}" method="get">

                        <div class="row">

                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="@lang('site.search')" value="{{ request()->search }}">
                            </div>

                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> @lang('site.search')</button>
                                @if (auth()->user()->hasPermission('create_orders'))

                                    <!--<a href="{{ route('dashboard.order.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('site.add')</a>-->
                                {{-- @else
                                    <a href="#" class="btn btn-primary disabled"><i class="fa fa-plus"></i> @lang('site.add')</a> --}}
                                @endif
                            </div>

                        </div>
                    </form><!-- end of form -->

                </div><!-- end of box header -->

                <div class="box-body">

                    @if ($orders->count() > 0)

                        <table class="table table-hover">

                            <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('site.confirmed')</th>
                                <th>@lang('site.username')</th>
                                <th>@lang('site.subtotal')</th>
                                <th>@lang('site.total')</th>
                                <th>@lang('site.bill_number')</th>
                                <th>@lang('site.created_at')</th>
                                <th>@lang('site.details')</th>
                                @if (auth()->user()->hasPermission('update_orders','delete_orders'))
                                <th>@lang('site.action')</th>
                                @endif
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($orders as $index=>$orders)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $order->confirmed }}</td>
                                    <td>{{ $order->name }}</td>
                                    <td>{{ $order->subtotal }}</td>
                                    <td>{{ $order->total }}</td>
                                    <td>{{ $order->bill_number }}</td>
                                    <td>{{ $order->created_at }}</td>
                                    <td>
                                    <a href="{{ route('dashboard.order.details', $order->id) }}" class="btn btn-info btn-sm"><i class="fa fa-gear"></i> @lang('site.details')</a>
                                    </td>
                                    <td>
                                        @if (auth()->user()->hasPermission('update_orders'))
                                            <a href="{{ route('dashboard.order.edit', $order->id) }}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> @lang('site.edit')</a>
                                        {{-- @else
                                            <a href="#" class="btn btn-info btn-sm disabled"><i class="fa fa-edit"></i> @lang('site.edit')</a> --}}
                                        @endif
                                        @if (auth()->user()->hasPermission('delete_orders'))
                                            <form action="{{ route('dashboard.order.destroy', $order->id) }}" method="post" style="display: inline-block">
                                                {{ csrf_field() }}
                                                {{ method_field('delete') }}
                                                <button type="submit" class="btn btn-danger delete btn-sm"><i class="fa fa-trash"></i> @lang('site.delete')</button>
                                            </form><!-- end of form -->
                                        {{-- @else
                                            <button class="btn btn-danger btn-sm disabled"><i class="fa fa-trash"></i> @lang('site.delete')</button> --}}
                                        @endif
                                    </td>
                                </tr>

                            @endforeach
                            </tbody>

                        </table><!-- end of table -->

                        {{ $orders->appends(request()->query())->links() }}

                    @else

                        <h2>@lang('site.no_data_found')</h2>

                    @endif

                </div><!-- end of box body -->


            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->


@endsection
