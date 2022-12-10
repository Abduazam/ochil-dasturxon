@extends('layouts.back')

@section('title', __('categories.users'))

@section('content')
    <div class="col-12 bg-white p-0">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">{{ __('categories.users') }}</h3>
                <div class="block-options">
                    <a href="{{ route('users.create') }}" class="btn btn-success">{{ __('dashboard.add') }}</a>
                </div>
            </div>
            <div class="block-content">
                @if(count($users) > 0)
                    <table class="table table-bordered table-vcenter">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th style="width: 150px;">{{ __('dashboard.name', ['section' => __('categories.users')]) }}</th>
                            <th style="width: 200px;">{{ __('dashboard.phone_number', ['section' => __('categories.users')]) }}</th>
                            <th style="width: 200px;">{{ __('dashboard.organ_id', ['section' => __('categories.users')]) }}</th>
                            <th style="width: 200px;">{{ __('dashboard.balance', ['section' => __('categories.users')]) }}</th>
                            <th class="d-none d-sm-table-cell" style="width: 15%;">{{ __('dashboard.status') }}</th>
                            <th class="text-center" style="width: 100px;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <th class="text-center" scope="row">{{ $user->id }}</th>
                                <td>{{ $user->first_name }}</td>
                                <td>{{ $user->phone_number }}</td>
                                <td>
                                    @if($user->organization)
                                        {{ $user->organization->title }}
                                    @endif
                                </td>
                                <td>
                                    {{ $user->balance }} {{ __('dashboard.currency') }}
                                    @if($user->balance < 0)
                                        <span class="badge badge-danger ml-2">{{ __('dashboard.debt') }}</span>
                                    @endif
                                </td>
                                <td class="d-none d-sm-table-cell">
                                    @if($user->status === 1)
                                        <span class="badge badge-success">{{ __('dashboard.active') }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ __('dashboard.inactive') }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('users.edit', ['user' => $user->id]) }}" type="button" class="btn btn-sm btn-secondary js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="Edit">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <a href="{{ route('users.show', ['user' => $user->id]) }}" type="button" class="btn btn-sm btn-secondary js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="Show">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <form action="{{ route('users.destroy', ['user' => $user->id]) }}" method="POST">
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
                    <p class="text-center mb-3">{{ __('dashboard.empty', ['section' => __('categories.users')]) }}</p>
                @endif
            </div>
        </div>
    </div>
@endsection
