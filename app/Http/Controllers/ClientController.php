<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     * Filtros de busqueda: id, phone, name.
     * Keys: client_id, client_phone, client_name.
     */
    public function index(Request $request)
    {
        $query = Client::query();
        if ($client_id = $request->query('client_id')) {
            $query->where('id', $client_id);
        }
        if ($client_phone = $request->query('client_phone')) {
            $query->where('phone', $client_phone);
        }
        if ($client_name = $request->query('client_name')) {
            $query->where('name', $client_name);
        }

        $per_page = (int) $request->query('per_page', 15);
        $order_by = $request->query('order_by', 'name');
        $order_dir = $request->query('order_dir', 'desc');

        $clients = $query->orderBy($order_by, $order_dir)->paginate($per_page);
        //$clients = Client::all();
        return response()->json($clients);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        $client = Client::create($request->validated());
        return response()->json($client, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        return response()->json($client);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        $client->update($request->validated());
        return response()->json($client);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();
        return response()->json(null, 204);
    }
}
