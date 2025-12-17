<?php

namespace App\Listeners;

use App\Events\ServiceRecordsLote;
use App\Http\Controllers\ServiceRecordController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;

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
        $service_array = $event->services_array;
        $visit_id = $event->visit_id;

        foreach ($service_array as $service) {
            $data = [
                'service_id' => $service,
                'visit_id' => $visit_id
            ];
            $request = Request::create("/service-records", 'POST', $data);
            $controller = new ServiceRecordController;
            $controller->store($request);
        }
    }
}
