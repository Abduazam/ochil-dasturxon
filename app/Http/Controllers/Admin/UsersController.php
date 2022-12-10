<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UsersRequest;
use App\Models\Organization;
use App\Models\UserOrganization;
use App\Models\Users;
use Illuminate\View\Factory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Throwable;

class UsersController extends Controller
{
    public function index(): View|Application
    {
        $users = Users::all();
        return view('admin.users.index', compact('users'));
    }

    public function create(): Factory|View|Application
    {
        $organs = Organization::where('status', 1)->get();
        return view('admin.users.create', compact('organs'));
    }

    public function store(UsersRequest $request): RedirectResponse
    {
        try {
            $params = $request->validated();
            $users = new Users();
            $users->first_name = $params['first_name'];
            $users->phone_number = $params['phone_number'];
            $users->save();

            if ($params['organ_id'] > 0)
            {
                $user_organization = new UserOrganization();
                $user_organization->user_id = $users->id;
                $user_organization->organ_id = $params['organ_id'];
                $user_organization->save();
            }

            return redirect()->route('users.index');
        } catch (Throwable $e) {
            report($e);
            return redirect()->back();
        }
    }

    public function show(Users $user): Factory|View|Application
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(Users $user): Factory|View|Application
    {
        $organs = Organization::where('status', 1)->get();
        return view('admin.users.edit', compact(['organs', 'user']));
    }

    public function update(UsersRequest $request, Users $user): bool|RedirectResponse
    {
        try {
            $params = $request->validated();
            Users::where('id', $user->id)->update([
                'first_name' => $params['first_name'],
                'phone_number' => $params['phone_number']
            ]);


            $checkUser = UserOrganization::where('user_id', $user->id)->get();
            if ($params['organ_id'] > 0)
            {
                if (count($checkUser) > 0) {
                    UserOrganization::where('user_id', $user->id)->update([
                        'organ_id' => $params['organ_id']
                    ]);
                } else {
                    $user_organization = new UserOrganization();
                    $user_organization->user_id = $user->id;
                    $user_organization->organ_id = $params['organ_id'];
                    $user_organization->save();
                }
            } else if ($params['organ_id'] == 0) {
                UserOrganization::where('user_id', $user->id)->delete();
            }

            return redirect()->route('users.index');
        } catch (Throwable $e) {
            report($e);
            return redirect()->back();
        }
    }

    public function destroy(Users $user): RedirectResponse
    {
        $user->toActive();
        return redirect()->back();
    }
}
