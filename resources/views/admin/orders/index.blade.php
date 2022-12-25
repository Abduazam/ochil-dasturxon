@extends('layouts.back')

@section('title', __('categories.orders'))

@section('content')
    <div class="col-12 bg-white p-0">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">{{ __('categories.orders') }}: {{ date("d.m.Y") }}</h3>
                <div class="block-options">
                    <a href="{{ route('orders.create') }}" class="btn btn-success">{{ __('dashboard.add') }}</a>
                </div>
            </div>
            <div class="block-content row">
                @if(count($orders) > 0)
                    @foreach($orders as $order)
                        <div class="col-md-6 col-xl-3">
                            <a class="block block-rounded block-link-pop text-center" href="{{ route('orders.show', ['order' => $order->meal_id]) }}">
                                <div class="block-content block-content-full bg-image" style="background-image: url('/images/{{ $order->meal->img }}');">
                                    <div class="circle-order-count">
                                        <h5 class="mb-0">{{ $order->total }}</h5>
                                    </div>
                                </div>
                                <div class="block-content block-content-full">
                                    <div class="font-w600">{{ $order->meal->title }}</div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @else
                    <p class="text-center mb-3">{{ __('dashboard.empty', ['section' => __('categories.orders')]) }}</p>
                @endif
            </div>
        </div>
    </div>
@endsection
