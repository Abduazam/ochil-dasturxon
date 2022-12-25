@extends('layouts.back')

@foreach($meal as $m)
    @section('title', __('dashboard.show_section', ['section' => __('categories.order'), 'title' => $m->title]))
@endforeach

@section('content')
    <div class="col-12 bg-white p-0">
        <!-- Hero -->
        @foreach($meal as $ml)
        <div class="bg-image" style="background-image: url('/images/{{ $ml->img }}');">
            <div class="bg-black-op">
                <div class="content content-top content-full text-center">
                    <div class="py-20">
                        <h1 class="h2 font-w700 text-white mb-10">{{ $ml->title }}</h1>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Page Content -->
        <div class="content">
            <!-- Products -->
            <h2 class="content-heading pt-0">{{ __('categories.orders') }} ({{ count($orders) }})</h2>
            <div class="block block-rounded">
                <div class="block-content p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 50px;">#</th>
                                    <th style="width: 170px;">{{ __('dashboard.client', ['section' => __('categories.order')]) }}</th>
                                    <th>{{ __('dashboard.count', ['section' => __('categories.order')]) }}</th>
                                    <th>{{ __('dashboard.organ_id', ['section' => __('categories.users')]) }}</th>
                                    <th>{{ __('dashboard.phone_number', ['section' => __('categories.users')]) }}</th>
                                    <th class="d-none d-sm-table-cell" style="width: 15%;">{{ __('dashboard.status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <th class="text-center" scope="row">{{ $loop->index+1 }}</th>
                                    <td>{{ $order->user->first_name }}</td>
                                    <td>{{ $order->count }}</td>
                                    <td>{{ $order->user->organization->title }}</td>
                                    <td>{{ $order->user->phone_number }}</td>
                                    <td class="d-none d-sm-table-cell">
                                        @if($order->status == 0)
                                            <span class="badge badge-danger">{{ __('dashboard.inactive') }}</span>
                                        @else
                                            <span class="badge badge-success">{{ __('dashboard.active') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <!-- <a href="{{ route('orders.show', ['order' => $order->id]) }}" type="button" class="btn btn-sm btn-secondary js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="Show">
                                                <i class="fa fa-eye"></i>
                                            </a> -->
                                            <a href="" type="button" class="btn btn-sm btn-secondary js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="Show">
                                                <i class="fa fa-check"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- END Products -->
        </div>
    </div>
@endsection
