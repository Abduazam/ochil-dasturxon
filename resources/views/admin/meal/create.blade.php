@extends('layouts.back')

@section('title', __('dashboard.add_section', ['section' => __('categories.meal')]))

@section('content')
    <div class="col-12 bg-white p-0">
        <!-- Normal Form -->
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">{{ __('categories.meal') }}</h3>
            </div>
            <div class="block-content">
                <form action="{{ route('meal.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <div class="col-4">
                            <label for="title">{{ __('dashboard.title', ['section' => __('categories.meal')]) }}</label>
                            <input type="text" class="form-control form-control" id="title" name="title" placeholder="{{ __('dashboard.enter_title') }}" required>
                        </div>
                        <div class="col-4">
                            <label for="price">{{ __('dashboard.price', ['section' => __('categories.meal')]) }}</label>
                            <input type="number" class="form-control form-control" id="price" name="price" placeholder="{{ __('dashboard.enter_price') }}" required>
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
