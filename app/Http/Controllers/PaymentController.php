<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     * Filters: visit_id, method, start_date, end_date
     *
     */
    public function index(Request $request)
    {
        $query = Payment::query()->with('visit');
        if ($visit_id = $request->has('visit_id')){
            $query->where('visit_id', $visit_id);
        }
        if ($method = $request->has('method')){
            $query->where('method', $method);
        }
        if ($start_date = $request->has('start_date') && $end_date = $request->has('end_date')){
            $query->raw(Payment::scopeBetweenDates($query, $start_date, $end_date));
        }
        else if ($start_date = $request->has('start_date')){
            $query->where('paid_at', $start_date);
        }

        $per_page = (int) $request->query('per_page', 15);
        $order_by = $request->query('order_by', 'created_at');
        $order_dir = $request->query('order_dir', 'desc');

        $visits = $query->orderBy($order_by, $order_dir)->paginate($per_page);

        return response()->json($visits);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentRequest $request)
    {
        $payment = new Payment;
        $payment->visit_id = $request->validated('visit_id');
        $payment->method = $request->validated('method');
        $payment->amount = $request->validated('amount');
        if ($request->has('tip_amount')){
            $payment->tip_amount = $request->validated('tip_amount');
        }
        if ($request->has('notes')){
            $payment->notes = $request->validated('notes');
        }
        $payment->save();
        return response()->json($payment, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        return response()->json($payment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        if ($request->has('method')){
            $payment->method = $request->validated('method');
        }
        if ($request->has('amount')){
            $payment->amount = $request->validated('amount');
        }
        if ($request->has('tip_amount')){
            $payment->tip_amount = $request->validated('tip_amount');
        }
        if ($request->has('notes')){
            $payment->notes = $request->validated('notes');
        }
        if ($request->has('visit_id')){
            $payment->visit = $request->validated('visit_id');
        }
        $payment->save();
        return response()->json($payment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();
        return response()->json($payment, 204);
    }
}
