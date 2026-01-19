<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use function Symfony\Component\String\s;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Service::query()->with('category');
        if ($service_id = $request->query('service_id')) {
            $query->where('id', $service_id);
        }
        if ($service_name = $request->query('service_name')) {
            $query->where('name', 'like', '%' . $service_name . '%');
        }
        if ($service_code = $request->query('service_code')) {
            $query->where('code', $service_code);
        }
        if (!is_null($is_active = $request->query('is_active'))) {
            $query->where('is_active', filter_var($is_active, FILTER_VALIDATE_BOOLEAN));
        }

        $order_by = $request->query('order_by', 'name');
        $order_dir = $request->query('order_dir', 'desc');

        if ($per_page = $request->query('per_page')) {
            $service = $query->orderBy($order_by, $order_dir)->paginate($per_page);
        }
        else {
            $service = $query->orderBy($order_by, $order_dir)->get();
        }
        //$service = Service::all();
        return response()->json($service);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request)
    {
        Log::info(json_encode($request->validated('service_category_id')));
        $service = Service::create([
            'name' => $request->validated('name'),
            'default_price' => $request->validated('default_price'),
            'is_active' => $request->validated('is_active'),
            'code' => $request->validated('code'),
            'duration_minutes' => $request->validated('duration_minutes'),
            'cost_estimate' => $request->validated('cost_estimate'),
            'service_category_id' => $request->validated('service_category_id'),
        ]);

        return response()->json($service);
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        return response()->json($service);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        Log::info(json_encode($request->validated()));
        $service->update($request->validated());
        return response()->json($service);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->delete();
        return response()->json('Deleted', 204);
    }
}
