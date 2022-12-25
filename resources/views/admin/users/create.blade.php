@extends('layouts.back')

@section('title', __('dashboard.add_section', ['section' => __('categories.users')]))

@section('content')
    <div class="col-12 bg-white p-0">
        <!-- Normal Form -->
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">{{ __('categories.users') }}</h3>
            </div>
            <div class="block-content">
                <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-4 pb-4">
                            <label for="first_name">{{ __('dashboard.name', ['section' => __('categories.users')]) }}</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="{{ __('dashboard.enter_name') }}" required>
                            @error('first_name')
                            <div class="alert alert-danger mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 pb-4">
                            <label for="phone_number">{{ __('dashboard.phone_number', ['section' => __('categories.users')]) }}</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="{{ __('dashboard.enter_phone_number') }}" required>
                            @error('phone_number')
                            <div class="alert alert-danger mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 pb-4">
                            <label for="organ_id">{{ __('dashboard.organ_id', ['section' => __('categories.users')]) }}</label>
                            <select class="form-control" id="organ_id" name="organ_id">
                                <option value="0">{{ __('dashboard.enter_organ_id') }}</option>
                                @foreach($organs as $organ)
                                <option value="{{ $organ->id }}">{{ $organ->title }}</option>
                                @endforeach
                            </select>
                            @error('organ_id')
                            <div class="alert alert-danger mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-8">
                            <!-- <label for="location">{{ __('dashboard.location', ['section' => __('categories.users')]) }}</label> -->
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
