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
        $query = Visit::query()->with('client');
        if ($client_id = $request->query('client_id')){
            $query->where('client_id', $client_id);
        }
        if ($status = $request->query('status')){
            $query->where('status', $status);
        }
        if ($payment_status = $request->query('payment_status')) {
            $query->where('payment_status', $payment_status);
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
        if($request->has('status')){
            $visit->status = $request->validated('status');
        }
        if($request->has('tax')){
            $visit->tax = $request->validated('tax');
        }
        if($request->has('discount')){
            $visit->discount = $request->validated('discount');
        }
        $visit->save();
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
