@extends('layouts.back')

@section('title', __('dashboard.show_section', ['section' => __('categories.order'), 'title' => $order->id]))

@section('content')
    <div class="col-12 bg-white p-0">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">{{ __('dashboard.client', ['section' => __('categories.order')]) }}: {{ $order->user->first_name }}</h3>
            </div>
            <div class="block-content">
                <table class="table table-bordered table-vcenter">
                    <tr>
                        <th class="text-uppercase col-3">{{ __('dashboard.meal', ['section' => __('categories.order')]) }}</th>
                        <td>{{ $order->meal->title }}</td>
                    </tr>
                    <tr>
                        <th class="text-uppercase col-3">{{ __('dashboard.count', ['section' => __('categories.order')]) }}</th>
                        <td>{{ $order->count }}</td>
                    </tr>
                    <tr>
                        <th class="text-uppercase col-3">{{ __('dashboard.meal_price', ['section' => __('categories.order')]) }}</th>
                        <td>{{ $order->meal->price * $order->count }} {{ __('dashboard.currency') }}</td>
                    </tr>
                    @if($order->user->organ_id)
                    <tr>
                        <th class="text-uppercase">{{ __('dashboard.organ_id', ['section' => __('categories.users')]) }}</th>
                        <td>{{ $order->user->organization->title }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th class="text-uppercase">{{ __('dashboard.phone_number', ['section' => __('categories.users')]) }}</th>
                        <td>{{ $order->user->phone_number }}</td>
                    </tr>
                    @if(!$order->user->organ_id)
                    <tr>
                        <th class="text-uppercase">{{ __('dashboard.location', ['section' => __('categories.users')]) }}</th>
                        <td>
                            <div id="map" style="width: 100%; height: 300px;">
                            </div>
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <th class="text-uppercase">{{ __('dashboard.status') }}</th>
                        <td>
                            @if($order->status === 1)
                                <span class="badge badge-success">{{ __('dashboard.active') }}</span>
                            @else
                                <span class="badge badge-danger">{{ __('dashboard.inactive') }}</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection
