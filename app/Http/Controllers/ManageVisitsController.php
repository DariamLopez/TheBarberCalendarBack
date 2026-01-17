<?php

namespace App\Http\Controllers;

use App\Events\ServiceRecordsLote;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\StoreVisitRequest;
use App\Models\ServiceRecord;
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
    /**
     * Change Services of a Visit: create ServiceRecords for each service in the request.
     * @param Request $request
     * $request->services: array of service IDs to create ServiceRecords.
     * @param int $visit_id
     * */
    public static function changeVisitsServices(Request $request)
    {
        Log::info('ManageVisitsController - changeVisitsServices called', ['request' => $request->all()]);


        $new_services_array = $request->services; //
        $visit_id = $request->visit_id;

        //Obtener service_records asociados a la visit
        $current_services = ServiceRecord::where('visit_id', $visit_id)->pluck('service_id')->toArray();

        //Eliminar service_records que no esten en el nuevo arreglo
        $services_to_delete = array_diff($current_services, $new_services_array);
        ServiceRecord::where('visit_id', $visit_id)->whereIn('service_id', $services_to_delete)->delete();

        //Crear los nuevos service_records
        $services_to_add = array_diff($new_services_array, $current_services);
        $service_records_event = new ServiceRecordsLote($services_to_add, $visit_id);
        event($service_records_event);
        return response()->json(['message' => 'Service records are being updated.'], 200);
    }
}
