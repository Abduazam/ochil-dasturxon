@extends('layouts.back')

@section('title', __('categories.meal'))

@section('content')
    <div class="col-12 bg-white p-0">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">{{ __('categories.meal') }}</h3>
                <div class="block-options">
                    <a href="{{ route('meal.create') }}" class="btn btn-success">{{ __('dashboard.add') }}</a>
                </div>
            </div>
            <div class="block-content">
                @if(count($meals) > 0)
                    <table class="table table-bordered table-vcenter">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th style="width: 200px;">{{ __('dashboard.title', ['section' => __('categories.meal')]) }}</th>
                            <th style="width: 200px;">{{ __('dashboard.price', ['section' => __('categories.meal')]) }}</th>
                            <th>{{ __('dashboard.image', ['section' => __('categories.meal')]) }}</th>
                            <th class="d-none d-sm-table-cell" style="width: 15%;">{{ __('dashboard.status') }}</th>
                            <th class="text-center" style="width: 100px;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($meals as $meal)
                            <tr>
                                <th class="text-center" scope="row">{{ $meal->id }}</th>
                                <td>{{ $meal->title }}</td>
                                <td>{{ $meal->price }}</td>
                                <td><img src="{{ @asset('images/' . $meal->img) }}" alt="" class="w-50"></td>
                                <td class="d-none d-sm-table-cell">
                                    @if($meal->status === 1)
                                        <span class="badge badge-success">{{ __('dashboard.active') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ __('dashboard.inactive') }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('meal.edit', ['meal' => $meal->id]) }}" type="button" class="btn btn-sm btn-secondary js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="Edit">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <a href="{{ route('meal.show', ['meal' => $meal->id]) }}" type="button" class="btn btn-sm btn-secondary js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="Show">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <form action="{{ route('meal.destroy', ['meal' => $meal->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-secondary" style="border-radius-top-left: 0;">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-center mb-3">{{ __('dashboard.empty', ['section' => __('categories.meal')]) }}</p>
                @endif
            </div>
        </div>
    </div>
@endsection
