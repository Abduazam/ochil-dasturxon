<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payments;
use App\Models\Users;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\Factory;
use Throwable;

class PaymentController extends Controller
{
    public function index(): View|Application
    {
        $payments = Payments::orderBy('id', 'DESC')->get();
        return view('admin.payment.index', compact('payments'));
    }

    public function show($id)
    {
        return Payments::findOrFail($id);
    }

    public function edit(Payments $payment): Payments
    {
        return $payment;
    }

    public function accept($id, $sum): bool
    {
        $payment = Payments::findOrFail($id);
        $payment->minus_sum = $sum;
        $payment->status = Payments::STATUS_ACCEPTED;
        $payment->save();

        $user = Users::findOrFail($payment->user_id);
        $user->balance += $sum;
        $user->save();

        return true;
    }

    public function destroy(Payments $payment): RedirectResponse
    {
        $payment->toNotAccept();
        return redirect()->back();
    }
}
