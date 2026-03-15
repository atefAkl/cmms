<?php

namespace App\Http\Controllers;

use App\Models\TemperatureReading;
use App\Http\Requests\StoreTemperatureReadingRequest;
use App\Http\Requests\UpdateTemperatureReadingRequest;

class TemperatureReadingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTemperatureReadingRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(TemperatureReading $temperatureReading)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TemperatureReading $temperatureReading)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTemperatureReadingRequest $request, TemperatureReading $temperatureReading)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TemperatureReading $temperatureReading)
    {
        //
    }
}
