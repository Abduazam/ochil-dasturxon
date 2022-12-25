@extends('layouts.back')

@section('title', __('dashboard.add_section', ['section' => __('categories.order')]))

@section('content')
    <div class="col-12 bg-white p-0">
        <!-- Normal Form -->
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">{{ __('dashboard.report', ['section' => __('categories.orders')]) }}</h3>
            </div>
            <div class="block-content">
                <form action="{{ url('orders/search') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-3 pr-0">
                            <label for="organ_id">{{ __('dashboard.enter_organ_id') }}</label>
                            <select class="form-control" id="organ_id" name="organ_id">
                                <option value="0">{{ __('dashboard.enter_organ_id') }}</option>
                                @foreach($organs as $organ)
                                    <option value="{{ $organ->id }}">{{ $organ->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="date">{{ __('dashboard.first_date') }}</label>
                            <div class="form-group">
                                <input type="text" class="js-flatpickr daily-date form-control bg-white"
                                       id="date" name="first_date" placeholder="Yil-oy-kun">
                            </div>
                        </div>
                        <div class="col-md-4 pl-0">
                            <label for="date">{{ __('dashboard.second_date') }}</label>
                            <div class="form-group">
                                <input type="text" class="js-flatpickr daily-date form-control bg-white"
                                       id="date" name="second_date" placeholder="Yil-oy-kun">
                            </div>
                        </div>
                        <div class="col-md-1 pl-0">
                            <label for="submit" class="opacity-0">Filter</label>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary">{{ __('dashboard.search') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            @if(isset($orders) && count($orders) > 0)
                <div class="block-content">
                    <table class="table table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>{{ __('dashboard.name', ['section' => __('categories.users')]) }}</th>
                                <th>{{ __('dashboard.phone_number', ['section' => __('categories.users')]) }}</th>
                                <th>{{ __('dashboard.organ_id', ['section' => __('categories.users')]) }}</th>
                                <th>{{ __('dashboard.order_count') }}</th>
                                <th>{{ __('dashboard.minus_sum', ['section' => __('categories.users')]) }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <th class="text-center" scope="row">{{ $order->user_id }}</th>
                                <td>{{ $order->user->first_name }}</td>
                                <td>{{ $order->user->phone_number }}</td>
                                <td>{{ $order->user->organization->title }}</td>
                                <td class="text-center">{{ $order->total }}</td>
                                <td>{{ $order->total_sum }} {{ __('dashboard.currency') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if(isset($e))
                {{ $e }}
            @endif
        </div>
    </div>
@endsection
