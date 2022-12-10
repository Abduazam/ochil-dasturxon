@extends('layouts.back')

@section('title', __('dashboard.show_section', ['section' => __('categories.users'), 'title' => $user->title]))

@section('content')
    <div class="col-12 bg-white p-0">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">{{ __('categories.users') }}: {{ $user->id }}</h3>
                <a href="{{ route('users.edit', ['user' => $user->id]) }}" type="button" class="btn btn-warning mr-2">{{ __('dashboard.edit') }}</a>
                <form action="{{ route('users.destroy', ['user' => $user->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-{{ $user->status === 1 ? 'danger' : 'success' }}">{{ $user->status === 1 ? __('dashboard.delete') : __('dashboard.activate') }}</button>
                </form>
            </div>
            <div class="block-content">
                <table class="table table-bordered table-vcenter">
                    <tr>
                        <th class="text-uppercase col-2">{{ __('dashboard.name', ['section' => __('categories.users')]) }}</th>
                        <td>{{ $user->first_name }}</td>
                    </tr>
                    <tr>
                        <th class="text-uppercase">{{ __('dashboard.phone_number', ['section' => __('categories.users')]) }}</th>
                        <td>{{ $user->phone_number }}</td>
                    </tr>
                    <tr>
                        <th class="text-uppercase">{{ __('dashboard.balance', ['section' => __('categories.users')]) }}</th>
                        <td>{{ $user->balance }}</td>
                    </tr>
                    <tr>
                        <th class="text-uppercase">{{ __('dashboard.status') }}</th>
                        <td>
                            @if($user->status === 1)
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
