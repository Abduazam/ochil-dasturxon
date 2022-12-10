@extends('layouts.back')

@section('title', __('categories.organs'))

@section('content')
    <div class="col-12 bg-white p-0">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">{{ __('categories.organs') }}</h3>
                <div class="block-options">
                    <a href="{{ route('organization.create') }}" class="btn btn-success">{{ __('dashboard.add') }}</a>
                </div>
            </div>
            <div class="block-content">
                @if(count($organs) > 0)
                    <table class="table table-bordered table-vcenter">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th>Title</th>
                            <th class="d-none d-sm-table-cell" style="width: 15%;">Status</th>
                            <th class="text-center" style="width: 100px;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($organs as $organ)
                            <tr>
                                <th class="text-center" scope="row">{{ $organ->id }}</th>
                                <td>{{ $organ->title }}</td>
                                <td class="d-none d-sm-table-cell">
                                    @if($organ->status === 1)
                                        <span class="badge badge-success">{{ __('dashboard.active') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ __('dashboard.inactive') }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('organization.edit', ['organization' => $organ->id]) }}" type="button" class="btn btn-sm btn-secondary js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="Edit">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <form action="{{ route('organization.destroy', ['organization' => $organ->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-secondary">
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
                    <p class="text-center mb-3">{{ __('dashboard.empty', ['section' => __('categories.organs')]) }}</p>
                @endif
            </div>
        </div>
    </div>
@endsection
