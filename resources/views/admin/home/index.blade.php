@extends('layouts.back')

@section('title', 'Dashboard')

@section('content')
    <div class="row invisible" data-toggle="appear">
        <div class="col-6 col-xl-3">
            <a class="block block-rounded block-bordered block-link-shadow" href="{{ route('meal.index') }}">
                <div class="block-content block-content-full clearfix">
                    <div class="float-right mt-15 d-none d-sm-block">
                        <i class="si si-cup fa-2x text-primary-light"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-primary" data-toggle="countTo" data-speed="700" data-to="{{ $meals }}">0</div>
                    <div class="font-size-sm font-w600 text-uppercase text-muted">{{ __('categories.meal') }}</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-3">
            <a class="block block-rounded block-bordered block-link-shadow" href="{{ route('day.index') }}">
                <div class="block-content block-content-full clearfix">
                    <div class="float-right mt-15 d-none d-sm-block">
                        <i class="si si-book-open fa-2x text-success"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-success" data-toggle="countTo" data-speed="700" data-to="{{ $daily }}">0</div>
                    <div class="font-size-sm font-w600 text-uppercase text-muted">{{ __('categories.daily') }}</div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-3">
            <a class="block block-rounded block-bordered block-link-shadow" href="{{ route('organization.index') }}">
                <div class="block-content block-content-full clearfix">
                    <div class="float-right mt-15 d-none d-sm-block">
                        <i class="fa fa-bank fa-2x text-elegance-light"></i>
                    </div>
                    <div class="font-size-h3 font-w600 text-elegance" data-toggle="countTo" data-speed="700" data-to="{{ $organs }}">0</div>
                    <div class="font-size-sm font-w600 text-uppercase text-muted">{{ __('categories.organs') }}</div>
                </div>
            </a>
        </div>
    </div>
@endsection
