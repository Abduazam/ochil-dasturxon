@extends('layouts.back')

@section('title', __('dashboard.add_section', ['section' => __('categories.organs')]))

@section('content')
    <div class="col-12 bg-white p-0">
        <!-- Normal Form -->
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">{{ __('categories.organs') }}</h3>
            </div>
            <div class="block-content">
                <form action="{{ route('organization.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="title">{{ __('dashboard.title', ['section' => __('categories.organs')]) }}</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="{{ __('dashboard.enter_title') }}" required>
                        @error('title')
                        <div class="alert alert-danger mt-2 small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-success">{{ __('dashboard.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
