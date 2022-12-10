<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrgansRequest;
use App\Models\Organization;
use Illuminate\View\Factory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Throwable;

class OrganizationController extends Controller
{
    public function index(): View|Application
    {
        $organs = Organization::all();
        return view('admin.organs.index', compact('organs'));
    }

    public function create(): Factory|View|Application
    {
        return view('admin.organs.create');
    }

    public function store(OrgansRequest $request): RedirectResponse
    {
        try {
            $params = $request->validated();
            Organization::create($params);
            return redirect()->route('organization.index');
        } catch (Throwable $e) {
            report($e);
            return redirect()->back();
        }
    }

    public function edit(Organization $organization): Factory|View|Application
    {
        return view('admin.organs.edit', compact('organization'));
    }

    public function update(OrgansRequest $request, Organization $organization): RedirectResponse
    {
        try {
            $params = $request->validated();
            Organization::where('id', $organization->id)->update([
                'title' => $params['title']
            ]);
            return redirect()->route('organization.index');
        } catch (Throwable $e) {
            report($e);
            return redirect()->back();
        }
    }

    public function destroy(Organization $organization): RedirectResponse
    {
        $organization->toActive();
        return redirect()->back();
    }
}
