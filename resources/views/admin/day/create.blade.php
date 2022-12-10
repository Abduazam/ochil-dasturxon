@extends('layouts.back')

@section('title', __('dashboard.add_section', ['section' => __('categories.daily')]))

@section('content')
    <div class="col-12 bg-white p-0">
        <!-- Normal Form -->
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">{{ __('categories.daily') }}</h3>
            </div>
            <div class="block-content">
                <form action="{{ route('day.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <div class="col-6">
                            <label for="date">{{ __('dashboard.date', ['section' => __('categories.daily')]) }}</label>
                            <div class="form-group">
                                <input type="text" class="js-flatpickr daily-date form-control bg-white"
                                       id="date" name="day" data-min-date="today"
                                       placeholder="Yil-oy-kun">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group row">
                                <label class="col-12" for="meals">{{ __('dashboard.meals', ['section' => __('categories.daily')]) }}</label>
                                <div class="col-12">
                                    <select class="js-select2 form-control" id="meals"
                                            name="meals[]"
                                            data-placeholder="{{ __('dashboard.choose_meals') }}" multiple>
                                        @foreach($meals as $meal)
                                            <option value="{{ $meal->id }}">{{ $meal->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-success">{{ __('dashboard.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
