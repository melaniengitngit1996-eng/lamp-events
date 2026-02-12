<?php

namespace App\Http\Controllers;

use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Show the form for creating a new registration.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Event $event, Registration $registration, Request $request) {
        $balance = floatval($registration->rate);
        $balance-= floatval(array_sum(array_column($registration->payments->toArray(), 'amount')));
        
        return view('payments.create', [
            'registration' => $registration,
            'balance' => $balance,
            'user' => Auth::user(),
            'event' => $event,
            'search' => $request->search ?? ''
        ]);
    }

    /**
     * store payments.
     *
     * @param  String $uuid
     * @param  Object $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Registration $registration) {
        $registration->payments()->create([
            'amount' => $request->amount,
            'user_id' => Auth::user()->id,
            'date_paid' => date("Y-m-d", strtotime($request->date))
        ]);
        
        return $this->updatePaymentStatus($registration->id, true);
    }

    public function destroy(Payment $payment) {     
        $payment->delete();

        return $this->updatePaymentStatus($payment->registration_id, false);
    }
}
