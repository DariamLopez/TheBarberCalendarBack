<?php

namespace App\Http\Controllers;

use App\Events\ServiceRecordsLote;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\StoreVisitRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ManageVisitsController extends Controller
{
    /**
     * Manage Visits: Create client if not exists, create a visit and create ServiceRecords for each service.
     * @param Request $request
     * $request->client: object with client data id or name, phone, address to crete new client.
     * $request->services_array: array of service IDs to create ServiceRecords.
     * $request->status: status of the visit.
     * $request->payment_method: payment method for the visit.
     * */
    public static function manageVisits(Request $request)
    {
        Log::info('ManageVisitsController - manageVisits called', ['request' => $request->all()]);
        $client = $request->client;
        Log::info("Booleano", $client);
        //Create client if not exists
        if (!empty($client) && empty($client['client_id'])) {
            $client_request = new StoreClientRequest();
            $client_request->replace([
                'name' => $client['name'] ?? $client->name ?? null,
                'phone' => $client['phone'] ?? $client->phone ?? null,
                'address' => $client['address'] ?? $client->address ?? null,
            ]);
            $client_request->setContainer(app())->setRedirector(app('redirect'));
            $client_request->validateResolved();
            $client_controller = new ClientController();
            $new_client = $client_controller->store($client_request);
            $client['client_id'] = $new_client->getData()->id;
        }
        Log::info('Client ID:', ['client_id' => $client['client_id']]);

        //Create visit
        $visit_request = new StoreVisitRequest();
        $visit_request->replace([
            'client_id' => $client['client_id'],
        ]);
        $visit_request->setContainer(app())->setRedirector(app('redirect'));
        $visit_request->validateResolved();
        $visit_controller = new VisitController();
        $visit = $visit_controller->store($visit_request);
        Log::info('Visit created:', ['visit' => $visit->getData()]);
        $visit_id = $visit->getData()->id;

        //Create ServiceRecords in lote
        $services_array = $request->services;
        $service_records_event = new ServiceRecordsLote($services_array, $visit_id);
        event($service_records_event);
        return $visit;
    }
}
