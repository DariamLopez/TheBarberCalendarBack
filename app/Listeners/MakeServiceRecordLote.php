<?php

namespace App\Listeners;

use App\Events\ServiceRecordsLote;
use App\Http\Controllers\ServiceRecordController;
use App\Http\Requests\StoreServiceRocordRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class MakeServiceRecordLote
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ServiceRecordsLote $event): void
    {
        Log::info('MakeServiceRecordLote - handle called', ['event' => $event]);
        $service_array = $event->services_array;
        $visit_id = $event->visit_id;

        foreach ($service_array as $service) {
            $service_record_request = new StoreServiceRocordRequest();
            $service_record_request->replace([
                'service_id' => $service,
                'visit_id' => $visit_id
            ]);
            $service_record_request->setContainer(app())->setRedirector(app('redirect'));
            $service_record_request->validateResolved();
            $controller = new ServiceRecordController;
            $controller->store($service_record_request);
        }
    }
}
