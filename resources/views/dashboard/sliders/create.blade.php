@extends('layouts.dashboard.app')
@inject('model','App\Slider')
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>@lang('site.sliders')</h1>

            <ol class="breadcrumb">
                <li><a href="{{ route('dashboard.welcome') }}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li><a href="{{ route('dashboard.sliders.index') }}"> @lang('site.sliders')</a></li>
                <li class="active">@lang('site.add')</li>
            </ol>
        </section>

        <section class="content">

            <div class="box box-primary">

                <div class="box-header">
                    <h3 class="box-title">@lang('site.add')</h3>
                </div><!-- end of box header -->
                <div class="box-body">

                    @include('partials._errors')

                    {!!Form::model($model,[
        'action'=>'Dashboard\SliderController@store',
        'files'=>true,
        'class'=>'m-form m-form--fit m-form--label-align-right'
                                     ]) !!}

                    <div class="col-lg-12">
                        <label class="col-lg-1 col-form-label"> @lang('site.image')</label>


                        <div class="col-lg-9">
                            {!!Form::file('image',null,[
                        'class'=>'form-control m-input'
                        ])!!}
                        </div>
                    </div>


                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('site.add')</button>
                        </div>
                    {!! Form::close()!!}

                    </div><!-- end of form -->

                </div><!-- end of box body -->

            <!-- end of box -->

        </section><!-- end of content -->

    </div><!-- end of content wrapper -->

@endsection
