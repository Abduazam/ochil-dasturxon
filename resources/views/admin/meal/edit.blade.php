@extends('layouts.back')

@section('title', __('dashboard.edit_section', ['section' => __('categories.meal')]))

@section('content')
    <div class="col-12 bg-white p-0">
        <!-- Normal Form -->
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">{{ __('categories.meal') }}: {{ $meal->id }}</h3>
            </div>
            <div class="block-content">
                <form action="{{ route('meal.update', ['meal' => $meal->id]) }}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="form-group row">
                        <div class="col-4">
                            <label for="title">{{ __('dashboard.title', ['section' => __('categories.meal')]) }}</label>
                            <input type="text" class="form-control form-control" id="title" name="title" value="{{ $meal->title }}" required>
                            @error('title')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-4">
                            <label for="price">{{ __('dashboard.price', ['section' => __('categories.meal')]) }}</label>
                            <input type="number" class="form-control form-control" id="price" name="price" value="{{ $meal->price }}" required>
                            @error('price')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-4">
                            <div class="form-group row">
                                <label class="col-12">{{ __('dashboard.image', ['section' => __('categories.meal')]) }}</label>
                                <div class="col-12">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="img"
                                               name="img" data-toggle="custom-file-input">
                                        <label class="custom-file-label" for="img">{{ __('dashboard.enter_file') }}</label>
                                    </div>
                                </div>
                                @error('img')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        @if(isset($meal->img))
                            <div class="col-8"></div>
                            <div class="col-4">
                                <label for="">{{ __('dashboard.current_file') }}</label>
                                <img src="{{ asset('images/' . $meal->img) }}" alt="Portfolio" class="w-100">
                            </div>
                        @endif
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-success">{{ __('dashboard.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
