<?php

namespace App\Http\Controllers;

use App\Models\ServiceRecord;
use App\Http\Requests\StoreServiceRocordRequest;
use App\Http\Requests\UpdateServiceRocordRequest;
use App\Models\Worker;
use Illuminate\Http\Request;

class ServiceRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ServiceRecord::query()->with(['service', 'worker', 'visit']);
        if ($service_id = $request->query('service_id')) {
            $query->where('service_id', $service_id);
        }
        if ($worker_id = $request->query('worker_id')) {
            $query->where('worker_id', $worker_id);
        }
        if ($visit_id = $request->query('visit_id')) {
            $query->where('visit_id', $visit_id);
        }

        $per_page = (int) $request->query('per_page', 15);
        $order_by = $request->query('order_by');
        $order_dir = $request->query('order_dir', 'desc');

        $service_records = $query->orderBy($order_by, $order_dir)->paginate($per_page);

        return response()->json($$service_records);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRocordRequest $request)
    {
        $service_records = ServiceRecord::create([
            'visit_id' => $request->visit_id,
            'service_id' => $request->service_id,
            'worker_id' => $request->worker_id ? $request->worker_id : null,
            'notes' => $request->notes ? $request->notes : ''
        ]);

        return response()->json($service_records);
    }

    /**
     * Display the specified resource.
     */
    public function show(ServiceRecord $service_record)
    {
        return response()->json($service_record);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRocordRequest $request, ServiceRecord $service_record)
    {
        if ($request->has('worker_id')) {
            $service_record->worker_id = $request->validated('worker_id');
            $worker = Worker::find($service_record->worker_id);
            $service_record->commision_rate = $worker ? $worker->commission_rate : 0;
        }
        if ($request->has('notes')) {
            $service_record->notes = $request->validated('notes');
        }
        if ($request->has('status')) {
            $service_record->status = $request->validated('status');
        }
        $service_record->save();
        return response()->json($service_record);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceRecord $service_record)
    {
        $service_record->delete();
        return response()->json($service_record, 204);
    }
}
