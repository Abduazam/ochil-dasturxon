<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrdersRequest;
use App\Models\Day;
use App\Models\Orders;
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
        $date = date('Y-m-d');
        $orders = Orders::where([['status', 1], ['date', $date]])->orderBy('id', 'DESC')->get();
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


    public function show(Orders $order): Factory|View|Application
    {
        return view('admin.orders.show', compact('order'));
    }
}
