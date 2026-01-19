<?php

namespace App\Http\Controllers;

use App\Events\ServiceRecordsLote;
use App\Models\Visit;
use App\Http\Requests\StoreVisitRequest;
use App\Http\Requests\UpdateVisitRequest;
use Illuminate\Http\Request;


class VisitController extends Controller
{
    /**
     * Display a listing of the resource.
     * Filters: client_id, status.
     * Items per page: per_page.
     * Order: order_by, order_dir.
     */
    public function index(Request $request)
    {
        $query = Visit::query()->with('client')->with('serviceRecords.service.category');
        if ($client_id = $request->query('client_id')){
            $query->where('client_id', $client_id);
        }
        if ($status = $request->query('status')){
            $query->where('status', $status);
        }
        if ($payment_status = $request->query('payment_status')) {
            $query->where('payment_status', $payment_status);
        }

        $order_by = $request->query('order_by', 'created_at');
        $order_dir = $request->query('order_dir', 'desc');

        if ($per_page = $request->query('per_page')) {
            $visits = $query->orderBy($order_by, $order_dir)->paginate($per_page);
        }
        else {
            $visits = $query->orderBy($order_by, $order_dir)->get();
        }

        return response()->json($visits);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVisitRequest $request)
    {
        $visit = new Visit;
        $visit->client_id = $request->validated('client_id');
        if ($request->has('notes')){
            $visit->notes = $request->validated('notes');
        }
        $visit->save();
        return response()->json($visit, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Visit $visit)
    {
        return response()->json($visit);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVisitRequest $request, Visit $visit)
    {
        $visit->update($request->validated());
        return response()->json($visit);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Visit $visit)
    {
        $visit->delete();
        return response()->json($visit, 204);
    }
}
