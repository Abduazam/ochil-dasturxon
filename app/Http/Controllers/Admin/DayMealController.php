<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DailyMenuRequest;
use App\Models\DailyMenu;
use App\Models\Day;
use App\Models\Meal;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\Factory;
use Throwable;

class DayMealController extends Controller
{
    public function index(): View|Application
    {
        $today = date('Y-m-d');
        $days = Day::where([['status', 1], ['day', '>=', $today]])->get();
        return view('admin.day.index', compact('days'));
    }

    public function create(): Factory|View|Application
    {
        $meals = Meal::where('status', 1)->get();
        return view('admin.day.create', compact('meals'));
    }

    public function store(DailyMenuRequest $request): Factory|View|Application|RedirectResponse
    {
        try {
            $params = $request->validated();

            $day = new Day();
            $day->day = $params['day'];
            $day->save();

            foreach ($params['meals'] as $meal_id)
            {
                $meal = new DailyMenu();
                $meal->day_id = $day->id;
                $meal->meal_id = $meal_id;
                $meal->save();
            }
            return redirect()->route('day.index');
        } catch (Throwable $e) {
            report($e);

            $meals = Meal::where('status', 1)->get();
            return view('admin.day.create', compact('meals'));
        }
    }

    public function edit(Day $day): Factory|View|Application
    {
        $meals = Meal::where('status', 1)->get();
        return view('admin.day.edit', compact(['day', 'meals']));
    }

    public function update(DailyMenuRequest $request, Day $day): Factory|View|Application|RedirectResponse
    {
        try {
            $params = $request->validated();

            DailyMenu::where('day_id', $day->id)->delete();

            foreach ($params['meals'] as $meal_id)
            {
                $meal = new DailyMenu();
                $meal->day_id = $day->id;
                $meal->meal_id = $meal_id;
                $meal->save();
            }
            return redirect()->route('day.index');
        } catch (Throwable $e) {
            report($e);

            $meals = Meal::where('status', 1)->get();
            return view('admin.day.edit', compact(['day', 'meals']));
        }
    }

    public function report(): View|Application
    {
        $days = Day::where('status', 1)->get();
        return view('admin.day.index', compact('days'));
    }

    public function inactivate($day, $meal): RedirectResponse
    {
        DailyMenu::inactivate($day, $meal);
        return redirect()->back();
    }
}
