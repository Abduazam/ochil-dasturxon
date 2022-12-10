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
            <div class="block-content">
                @if(count($days) > 0)
                    @foreach($days as $day)
                    <div class="col-md-4">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="bg-gray-light">
                                    <th>
                                        {{ $day->day }}
                                    </th>
                                    <th></th>
                                    <th style="width: 50px;">
                                        <a href="{{ route('day.edit', ['day' => $day->id]) }}" type="button" class="btn btn-sm btn-secondary">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($day->meals as $meal)
                                    <tr>
                                        <td><a href="{{ route('meal.show', ['meal' => $meal->id]) }}" class="text-secondary">{{ $meal->title }}</a></td>
                                        <td style="width: 70px;" class="text-center">
                                            @if($day->mealStatus($meal->id) === 1)
                                                <span class="badge badge-success">{{ __('dashboard.active') }}</span>
                                            @else
                                                <span class="badge badge-danger">{{ __('dashboard.inactive') }}</span>
                                            @endif
                                        </td>
                                        <td style="width: 50px;">
                                            <form action="{{ url('day/inactivate', ['day' => $day->id, 'meal' => $meal->id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm bg-pulse-light text-white border-danger" style="border-radius-top-left: 0;">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
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
