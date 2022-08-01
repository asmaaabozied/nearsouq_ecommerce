@extends('layouts.dashboard.app')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
@section('content')

<div class="page-wrapper" style="min-height: 422px;">
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h2> {{$page->name}} </h2>
                    <p> {!! $page->description !!} </p>
                </div>
            </div>
        </div>
    </div>
</div>


                    @endsection
