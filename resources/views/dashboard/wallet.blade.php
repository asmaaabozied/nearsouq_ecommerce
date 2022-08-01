@extends('layouts.dashboard.app')
<!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"-->
<!--      integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">-->
@section('content')


    <div class="page-wrapper" style="min-height: 422px;">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">




                        <form action="{{ route('dashboard.openmodal') }}" method="post"
                              >

                        {{ csrf_field() }}
                        {{ method_field('post') }}
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                             aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">@lang('site.chanagebalance')</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <table class="border-5">
                                            <tr>
                                                <th>@lang('site.balance')</th>
                                                <th><input type="number"  name="balance" class="form-control oldBalance" required></th>
                                            </tr>
                                            <input type="number"  name="id" class="form-control user_id" hidden>
                                            <tr>
                                                <th>@lang('site.balanceNew')</th>
                                                <th><input type="number" id="" name="new_balance" class="form-control" required></th>
                                            </tr>
                                            <tr>

                                                <th>@lang('site.comment')</th>
                                                <th>
                                                 <textarea id="w3review" name="comment" rows="4" style="width: -webkit-fill-available;">
                                                   </textarea>

                                                </th>
                                            </tr>

                                        </table>


                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">@lang('site.Cancel')</button>
                                        <button type="submit" class="btn btn-primary">@lang('site.ok')</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <!--  End Of Modal -->

                        </form>

                        <h3 class="page-title">{{$title}}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a
                                    href="{{route('dashboard.welcome') }}">@lang('site.dashboard')</a></li>

                            <li class="breadcrumb-item active">{{$title}}({{$count}})</li>
                        </ul>
                    </div>
                    <div class="col-auto">
                        <a href="{{route('dashboard.'.$model.'.create')}}" class="btn btn-primary me-1">
                            <i class="fas fa-plus"></i>
                        </a>
                        <a class="btn btn-primary filter-btn" href="javascript:void(0);" id="filter_search">
                            <i class="fas fa-filter"></i>
                        </a>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
        @include('flash::message')
            <!-- Search Filter -->
            <div class="row" data-select2-id="14">
                <div class="col-md-12">
                    <div class="card">


                        <div class="card-body">

                            {!! $dataTable->table([], true) !!}
                        </div>
                    </div>
                </div>
            </div>


            <!-- /Search Filter -->


        </div>
    </div>


@section('scripts')

    <!--<script src="{{asset('style/app-assets/vendors/data-tables/js/jquery.dataTables.min.js')}}"></script>-->

    <!--<script-->
    <!--    src="{{asset('style/app-assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js')}}"></script>-->
    <!--<script src="{{asset('style/app-assets/js/custom/custom-script.js')}}"></script>-->



    <!-- END THEME  JS-->
    <!-- BEGIN PAGE LEVEL JS-->
    <!--{{--<script src="{{asset('style/app-assets/js/scripts/page-users.js')}}'"></script>--}}-->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
    <script src="/vendor/datatables/buttons.server-side.js"></script>




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



    </script>
    <script>



        function getdata(balance,id) {

            $('.oldBalance').val(balance)
            $('.user_id').val(id)

          console.log("daaaaata",balance,id)
        }

    </script>


@endsection

