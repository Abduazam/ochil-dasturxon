<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Models\Organization;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): Factory|View|Application
    {
        $today = date('Y-m-d');
        $meals = Meal::all()->count();
        $daily = DB::table('daily_menu')
            ->join('days', 'daily_menu.day_id', '=', 'days.id')
            ->where('days.day', '=', $today)
            ->get()->count();
        $organs = Organization::all()->count();
        return view('admin.home.index', compact(['meals', 'daily', 'organs']));
    }
}
