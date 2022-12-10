@extends('layouts.back')

@section('title', __('categories.orders'))

@section('content')
    <div class="col-12 bg-white p-0">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">{{ __('categories.orders') }}</h3>
                <div class="block-options">
                    <a href="{{ route('orders.create') }}" class="btn btn-success">{{ __('dashboard.add') }}</a>
                </div>
            </div>
            <div class="block-content">
                @if(count($orders) > 0)
                    <table class="table table-bordered table-vcenter">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th style="width: 200px;">{{ __('dashboard.client', ['section' => __('categories.order')]) }}</th>
                            <th style="width: 200px;">{{ __('dashboard.meal', ['section' => __('categories.order')]) }}</th>
                            <th>{{ __('dashboard.count', ['section' => __('categories.order')]) }}</th>
                            <th class="d-none d-sm-table-cell" style="width: 15%;">{{ __('dashboard.status') }}</th>
                            <th class="text-center" style="width: 100px;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <th class="text-center" scope="row">{{ $order->id }}</th>
                                <td>{{ $order->user->first_name }}</td>
                                <td>{{ $order->meal->title }}</td>
                                <td>{{ $order->count }}</td>
                                <td class="d-none d-sm-table-cell">
                                    @if($order->status === 1)
                                        <span class="badge badge-success">{{ __('dashboard.active') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ __('dashboard.inactive') }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('orders.show', ['order' => $order->id]) }}" type="button" class="btn btn-sm btn-secondary js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="Show">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-center mb-3">{{ __('dashboard.empty', ['section' => __('categories.orders')]) }}</p>
                @endif
            </div>
        </div>
    </div>
@endsection
