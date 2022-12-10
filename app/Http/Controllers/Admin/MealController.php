<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MealRequest;
use App\Models\Meal;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\Factory;
use Throwable;

class MealController extends HomeController
{
    public function index(): View|Application
    {
        $meals = Meal::all();
        return view('admin.meal.index', compact('meals'));
    }

    public function create(): Factory|View|Application
    {
        return view('admin.meal.create');
    }

    public function store(MealRequest $request): Factory|View|Application|RedirectResponse
    {
        try {
            $newImageName = null;
            if (isset($request->img)) {
                $newImageName = time() . '.' . $request->img->extension();
                $request->img->move(public_path('images'), $newImageName);
            }

            $params = $request->validated();
            $params['img'] = $newImageName;
            Meal::create($params);
            return redirect()->route('meal.index');
        } catch (Throwable $e) {
            report($e);
            return view('admin.meal.create');
        }
    }

    public function show(Meal $meal): Factory|View|Application
    {
        return view('admin.meal.show', compact('meal'));
    }

    public function edit(Meal $meal): Factory|View|Application
    {
        return view('admin.meal.edit', compact('meal'));
    }

    public function update(MealRequest $request, Meal $meal): Factory|View|Application|RedirectResponse
    {
        try {
            $newImageName = $meal->img;
            $file = 'images/' . $newImageName;
            if (isset($request->img)) {
                if ($newImageName !== null && file_exists($file)) {
                    unlink($file);
                }
                $newImageName = time() . '.' . $request->img->extension();
                $request->img->move(public_path('images'), $newImageName);
            }

            $request['img'] = $newImageName;
            $params = $request->validated();
            $params['img'] = $newImageName;
            Meal::where('id', $meal->id)->update([
                'title' => $params['title'],
                'price' => $params['price'],
                'img' => $params['img']
            ]);
            return redirect()->route('meal.show', compact('meal'));
        } catch (Throwable $e) {
            report($e);
            return view('admin.meal.update', compact('meal'));
        }
    }

    public function destroy(Meal $meal): RedirectResponse
    {
        $meal->toActive();
        return redirect()->back();
    }
}
