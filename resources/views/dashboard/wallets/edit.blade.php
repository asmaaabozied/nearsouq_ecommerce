@extends('layouts.dashboard.app')

@section('content')

    <div class="page-wrapper" style="min-height: 422px;" data-select2-id="16">
        <div class="content container-fluid" data-select2-id="15">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">@lang('site.wallets')</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{route('dashboard.welcome') }}">@lang('site.dashboard')</li>
                            <li class="breadcrumb-item"><a
                                    hhref="{{ route('dashboard.wallets.index') }}"> @lang('site.wallets') </a></li>
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


                            <div class="row">

                                <div class="col-md-6">
                                    <label>@lang('site.user')</label>
                                    {{$wallet->user->name ?? ''}}
                                </div>

                                <div class="col-md-6">

                                    <label>@lang('site.balance')</label>
                                    {{$wallet->balance ?? ''}}
                                </div>

                            </div>

<br><br>
                            <div class="row">
                                <br><br>

                                {!! $dataTable->table([], true) !!}




                            </div>
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

        $(document).ready(function(){
            // $(".alert").delay(5000).slideUp(300);
            $(".alert").slideDown(300).delay(5000).slideUp(300);
        });
        setTimeout(function() {
            $('.alert-box').remove();
        }, 30000);

    </script>

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
    <script src="/vendor/datatables/buttons.server-side.js"></script>

    <script>

        $(document).ready(function() {
            jQuery('.delete').click(function (event) {
                event.preventDefault();


                console.log("Tapped Delete button")
                var that = $(this)
                e.preventDefault();
                var n = new Noty({
                    text: "@lang('site.confirm_delete')",
                    type: "error",
                    killer: true,
                    buttons: [
                        Noty.button("@lang('site.yes')", 'btn btn-success mr-2', function () {
                            that.closest('form').submit();
                        }),
                        Noty.button("@lang('site.no')", 'btn btn-primary mr-2', function () {
                            n.close();
                        })
                    ]
                });
                n.show();


            });
        });

    </script>



    {!! $dataTable->scripts() !!}

    <script>

        function confirmDelete($id){
            console.log("Tapped Delete button")
            var that = document.getElementById("deleteForm"+$id);
            var n = new Noty({
                text: "@lang('site.confirm_delete')",
                type: "error",
                killer: true,
                buttons: [
                    Noty.button("@lang('site.yes')", 'btn btn-success mr-2', function () {
                        that.submit();
                    }),
                    Noty.button("@lang('site.no')", 'btn btn-primary mr-2', function () {
                        n.close();
                    })
                ]
            });
            n.show();
        }
        $(document).ready(function() {

            $("#deleteForm").on('click', "#delete", function(e) {

                console.log("Tapped Delete button")
                var that = $(this)
                e.preventDefault();
                var n = new Noty({
                    text: "@lang('site.confirm_delete')",
                    type: "error",
                    killer: true,
                    buttons: [
                        Noty.button("@lang('site.yes')", 'btn btn-success mr-2', function () {
                            that.closest('form').submit();
                        }),
                        Noty.button("@lang('site.no')", 'btn btn-primary mr-2', function () {
                            n.close();
                        })
                    ]
                });
                n.show();

            });

        });
    </script>


@endsection
