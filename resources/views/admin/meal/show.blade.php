@extends('layouts.back')

@section('title', __('dashboard.show_section', ['section' => __('categories.meal'), 'title' => $meal->title]))

@section('content')
    <div class="col-12 bg-white p-0">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">{{ __('categories.meal') }}: {{ $meal->id }}</h3>
                <a href="{{ route('meal.edit', ['meal' => $meal->id]) }}" type="button" class="btn btn-warning mr-2">{{ __('dashboard.edit') }}</a>
                <form action="{{ route('meal.destroy', ['meal' => $meal->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-{{ $meal->status === 1 ? 'danger' : 'success' }}">{{ $meal->status === 1 ? __('dashboard.delete') : __('dashboard.activate') }}</button>
                </form>
            </div>
            <div class="block-content">
                <table class="table table-bordered table-vcenter">
                    <tr>
                        <th class="text-uppercase col-2">{{ __('dashboard.title', ['section' => __('categories.meal')]) }}</th>
                        <td>{{ $meal->title }}</td>
                    </tr>
                    <tr>
                        <th class="text-uppercase">{{ __('dashboard.price', ['section' => __('categories.meal')]) }}</th>
                        <td>{!! $meal->price !!}</td>
                    </tr>
                    <tr>
                        <th class="text-uppercase">{{ __('dashboard.image', ['section' => __('categories.meal')]) }}</th>
                        <td><img src="{{ @asset('/images/' . $meal->img) }}" class="w-25" alt="{{ $meal->title }}"></td>
                    </tr>
                    <tr>
                        <th class="text-uppercase">{{ __('dashboard.status') }}</th>
                        <td>
                            @if($meal->status === 1)
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
