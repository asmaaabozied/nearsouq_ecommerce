@extends('layouts.dashboard.app')

@section('content')

    <div class="content-wrapper">

        <section class="content-header">

            <h1>@lang('site.cases')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('dashboard.cases.index') }}"> @lang('site.cases')</a></li>
                <li class="active">@lang('site.edit')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header">
                    <h3 class="box-title">@lang('site.edit')</h3>
                </div><!-- end of box header -->

                <div class="box-body">

                    @include('partials._errors')

                    <form action="{{ route('dashboard.cases.update', $category->id) }}" method="post">

                        {{ csrf_field() }}
                        {{ method_field('put') }}

                        <div class="form-group col-md-6">

                            <label>@lang('site.name')</label>

                            <input type="text" name="name" class="form-control"  >
                        </div>


                        <div class="form-group col-md-6">

                            <label>@lang('site.phone')</label>

                            <input type="text" name="phone" class="form-control"  >
                        </div>

                        <div class="form-group col-md-6">

                            <label>@lang('site.email')</label>

                            <input type="text" name="email" class="form-control"  >
                        </div>

                        <div class="form-group col-md-6">

                            <label>@lang('site.jobs')</label>

                            <input type="text" name="job" class="form-control"  >
                        </div>

                        <div class="form-group col-md-6">

                            <label>@lang('site.description')</label>

                            <input type="text" name="description" class="form-control"  >
                        </div>



                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i> @lang('site.edit')</button>
                        </div>

                    </form><!-- end of form -->

                </div><!-- end of box body -->

            </div><!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->

@endsection
