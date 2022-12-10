@extends('layouts.back')

@section('title', __('categories.payment'))

@section('content')
    <div class="col-12 bg-white p-0">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">{{ __('categories.payment') }}</h3>
            </div>
            <div class="block-content">
                @if(count($payments) > 0)
                    <table class="table table-bordered table-vcenter">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th style="width: 200px;">{{ __('dashboard.client', ['section' => __('categories.pay')]) }}</th>
                                <th style="width: 200px;">{{ __('dashboard.date', ['section' => __('categories.pay')]) }}</th>
                                <th style="width: 300px;">{{ __('dashboard.image', ['section' => __('categories.pay')]) }}</th>
                                <th class="d-none d-sm-table-cell" style="width: 15%;">{{ __('dashboard.minus_sum', ['section' => __('categories.pay')]) }}</th>
                                <th class="d-none d-sm-table-cell" style="width: 15%;">{{ __('dashboard.status') }}</th>
                                <th class="text-center" style="width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($payments as $payment)
                            <tr>
                                <th class="text-center" scope="row">{{ $payment->id }}</th>
                                <td>{{ $payment->user->first_name }}</td>
                                <td>{{ $payment->date }}</td>
                                <td><img src="{{ $payment->image }}" alt="Payment image" class="w-25" data-id='{{ $payment->id }}' id='open-image' data-toggle='modal' data-target='#modal-default'></td>
                                <td>{{ $payment->minus_sum }} {{ __('dashboard.currency') }}</td>
                                <td class="d-none d-sm-table-cell">
                                    @if($payment->status === 0)
                                        <span class="badge badge-danger">{{ __('dashboard.not_seen') }}</span>
                                    @elseif($payment->status === 1)
                                        <span class="badge badge-success">{{ __('dashboard.accepted') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ __('dashboard.not_accepted') }}</span>
                                    @endif
                                </td>
                                @if($payment->status !== 1)
                                <td class="text-center">
                                    <div class="btn-group">
                                        <form action="{{ route('payment.destroy', ['payment' => $payment->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-secondary" style="border-radius-top-left: 0;">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </form>
                                        <a href="#" type="button" class="btn btn-sm btn-secondary" data-id='{{ $payment->id }}' id='open-accept' data-toggle='modal' data-target='#modal-default2'>
                                            <i class="fa fa-check"></i>
                                        </a>
                                    </div>
                                </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-center mb-3">{{ __('dashboard.empty', ['section' => __('categories.payment')]) }}</p>
                @endif
            </div>
        </div>
    </div>

@endsection
