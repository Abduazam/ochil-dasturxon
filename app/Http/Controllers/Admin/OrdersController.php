<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderSearchRequest;
use App\Http\Requests\OrdersRequest;
use App\Models\Day;
use App\Models\Meal;
use App\Models\Orders;
use App\Models\Organization;
use App\Models\Users;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\Factory;
use Throwable;

class OrdersController extends Controller
{
    public function index(): View|Application
    {
        $orders = Orders::selectRaw('orders.meal_id, SUM(orders.count) as total')
            ->join('daily_menu', 'orders.meal_id', '=', 'daily_menu.meal_id')
            ->join('days', 'daily_menu.day_id', '=', 'days.id')
            ->where([['orders.status', 2], ['days.status', 1]])
            ->groupBy('orders.meal_id')->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function create(): Factory|View|Application
    {
        $users = Users::where('status', 1)->get();
        $today = date('Y-m-d');
        $days = Day::where([['status', 1], ['day', '=', $today]])->get();
        return view('admin.orders.create', compact(['users', 'days']));
    }

    public function store(OrdersRequest $request): View|Factory|Application|RedirectResponse
    {
        try {
            $params = $request->validated();

            $order = new Orders();
            $order->user_id = $params['user_id'];
            $order->meal_id = $params['meal_id'];
            $order->count = $params['count'];
            $order->date = date('Y-m-d');
            $order->created_date = date('Y-m-d H:i:s');
            $order->status = 1;
            $order->save();

            return redirect()->route('orders.index');
        } catch (Throwable $e) {
            report($e);

            $users = Users::where('status', 1)->get();
            $today = date('Y-m-d');
            $days = Day::where([['status', 1], ['day', '=', $today]])->get();
            return view('admin.orders.create', compact(['users', 'days']));
        }
    }

    public function show($id): Factory|View|Application
    {
        $meal = Meal::where('id', $id)->get();
        $orders = Orders::where('meal_id', $id)->get();
        return view('admin.orders.show', compact(['meal', 'orders']));
    }

    public function report(): View|Application
    {
        $organs = Organization::where('status', 1)->get();
        return view('admin.orders.report', compact(['organs']));
    }

    public function search(OrderSearchRequest $request): Factory|View|Application
    {
        try {
            $params = $request->validated();

            if ($params['organ_id'] == 0) {
                $orders = Orders::selectRaw('
                    orders.user_id,
                    SUM(orders.count) AS total,
                    SUM(meals.price) AS total_sum')
                    ->join('bot_users', 'orders.user_id', '=', 'bot_users.id')
                    ->join('user_organization', 'user_organization.user_id', '=', 'bot_users.id')
                    ->join('organizations', 'organizations.id', '=', 'user_organization.organ_id')
                    ->join('meals', 'meals.id', '=', 'orders.meal_id')
                    ->whereDate('orders.created_date', '>=', $params['first_date'])
                    ->whereDate('orders.created_date', '<=', $params['second_date'])
                    ->groupBy('orders.user_id')->get();
            } else {
                $orders = Orders::selectRaw('
                    orders.user_id,
                    SUM(orders.count) AS total,
                    SUM(meals.price) AS total_sum')
                    ->join('bot_users', 'orders.user_id', '=', 'bot_users.id')
                    ->join('user_organization', 'user_organization.user_id', '=', 'bot_users.id')
                    ->join('organizations', 'organizations.id', '=', 'user_organization.organ_id')
                    ->join('meals', 'meals.id', '=', 'orders.meal_id')
                    ->where('organizations.id', $params['organ_id'])
                    ->whereDate('orders.created_date', '>=', $params['first_date'])
                    ->whereDate('orders.created_date', '<=', $params['second_date'])
                    ->groupBy('orders.user_id')->get();
            }

            $organs = Organization::where('status', 1)->get();
            return view('admin.orders.report', compact(['organs', 'orders']));
        } catch (Throwable $e) {
            report($e);

            $organs = Organization::where('status', 1)->get();
            return view('admin.orders.report', compact(['organs', 'e']));
        }
    }
}
