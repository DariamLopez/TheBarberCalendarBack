<?php

namespace App\Http\Controllers;

use App\Models\Payout;
use App\Http\Requests\StorePayoutRequest;
use App\Http\Requests\UpdatePayoutRequest;

class PayoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePayoutRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Payout $payout)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePayoutRequest $request, Payout $payout)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payout $payout)
    {
        //
    }
}
