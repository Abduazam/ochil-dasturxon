@extends('layouts.back')

@section('title', __('categories.daily'))

@section('content')
    <div class="col-12 bg-white p-0">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">{{ __('categories.daily') }}</h3>
                <div class="block-options">
                    <a href="{{ route('day.create') }}" class="btn btn-success">{{ __('dashboard.add') }}</a>
                </div>
            </div>
            <div class="block-content row">
                @if(count($days) > 0)
                    @foreach($days as $day)
                        <div class="col-md-3">
                            <table class="table table-bordered">
                                <thead>
                                <tr class="bg-gray-light">
                                    <th>{{ $day->day }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($day->meals as $meal)
                                    <tr>
                                        <td><a href="{{ route('meal.show', ['meal' => $meal->id]) }}" class="text-secondary">{{ $meal->title }}</a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                @else
                    <p class="text-center mb-3">{{ __('dashboard.empty', ['section' => __('categories.daily')]) }}</p>
                @endif
            </div>
        </div>
    </div>
@endsection
