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

                        @foreach (config('translatable.locales') as $locale)
                            <div class="form-group">
                            @if(count(config('translatable.locales'))>1) 
                                <label>@lang('site.' . $locale . '.name')</label>
                        @else
                        <label>@lang('site.name')</label>
                        @endif
                                <input type="text" name="{{ $locale }}[name]" class="form-control" value="{{ $category->translate($locale)->name }}">
                            </div>
                        @endforeach

                        @foreach (config('translatable.locales') as $locale)
                            <div class="form-group">
                                @if(count(config('translatable.locales'))>1)
                                    <label>@lang('site.' . $locale . '.description')</label>
                                @else
                                    <label>@lang('site.description')</label>
                                @endif
                                <input type="text" name="{{ $locale }}[description]" class="form-control" value="{{ $category->translate($locale)->description }}">
                            </div>
                        @endforeach

                        @foreach (config('translatable.locales') as $locale)
                            <div class="form-group col-md-6">
                                @if(count(config('translatable.locales'))>1)
                                    <label>@lang('site.number')</label>
                                @else
                                    <label>@lang('site.number')</label>
                                @endif
                                <input type="text" name="{{ $locale }}[number]" class="form-control" value="{{ $category->translate($locale)->number }}">
                            </div>
                        @endforeach

                        <div class="form-group col-md-6 ">
                            {!! Form::label('parent', trans("site.users")) !!}
                            <select class="form-control select2" name="user_id" id="parent">
                                <option selected disabled>{{trans('site.select')}}</option>
                                @foreach(\App\User::pluck('name','id') as $id => $item)
                                    <option value="{{$id}}" >{{$item}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-sm-6 ">
                            <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title=" {{ trans('category.fields.image_help') }}"></i> &nbsp;{!! Form::label('image', trans('Cases.image')) !!}
                            <input type="file" onchange="readURL(this, 'ImagePreview', 'ImagePreview');" name="icons" id="image" @if(! isset($category)) required @endif>
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
