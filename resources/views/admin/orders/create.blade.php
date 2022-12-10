@extends('layouts.back')

@section('title', __('dashboard.add_section', ['section' => __('categories.order')]))

@section('content')
    <div class="col-12 bg-white p-0">
        <!-- Normal Form -->
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">{{ __('categories.orders') }}</h3>
            </div>
            <div class="block-content">
                <form action="{{ route('orders.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        @foreach($days as $day)
                        <div class="col-md-4 pb-4">
                            <label for="meal_id">{{ __('dashboard.meal', ['section' => __('categories.order')]) }}</label>
                            <select class="form-control" id="meal_id" name="meal_id">
                                <option value="0">{{ __('dashboard.enter_meal') }}</option>
                                @foreach($day->meals as $meal)
                                    <option value="{{ $meal->id }}">{{ $meal->title }}</option>
                                @endforeach
                            </select>
                            @error('meal_id')
                            <div class="alert alert-danger mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>
                        @endforeach
                        <div class="col-md-4 pb-4">
                            <label for="count">{{ __('dashboard.count', ['section' => __('categories.order')]) }}</label>
                            <input type="number" class="form-control" id="count" name="count" placeholder="{{ __('dashboard.enter_count') }}" required>
                            @error('count')
                            <div class="alert alert-danger mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 pb-4">
                            <label for="user_id">{{ __('dashboard.client', ['section' => __('categories.order')]) }}</label>
                            <select class="form-control" id="user_id" name="user_id">
                                <option value="0">{{ __('dashboard.enter_client') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->first_name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                            <div class="alert alert-danger mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-8">
                        </div>
                        <div class="col-md-4">
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-success">{{ __('dashboard.save') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
