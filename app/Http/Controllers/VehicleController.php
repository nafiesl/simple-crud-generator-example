<?php

namespace App\Http\Controllers;

use App\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the vehicle.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $editableVehicle = null;
        $vehicleQuery = Vehicle::query();
        $vehicleQuery->where('name', 'like', '%'.request('q').'%');
        $vehicles = $vehicleQuery->paginate(25);

        if (in_array(request('action'), ['edit', 'delete']) && request('id') != null) {
            $editableVehicle = Vehicle::find(request('id'));
        }

        return view('vehicles.index', compact('vehicles', 'editableVehicle'));
    }

    /**
     * Store a newly created vehicle in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->authorize('create', new Vehicle);

        $newVehicle = $request->validate([
            'name'        => 'required|max:60',
            'description' => 'nullable|max:255',
        ]);
        $newVehicle['creator_id'] = auth()->id();

        Vehicle::create($newVehicle);

        return redirect()->route('vehicles.index');
    }

    /**
     * Update the specified vehicle in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vehicle  $vehicle
     * @return \Illuminate\Routing\Redirector
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $this->authorize('update', $vehicle);

        $vehicleData = $request->validate([
            'name'        => 'required|max:60',
            'description' => 'nullable|max:255',
        ]);
        $vehicle->update($vehicleData);

        $routeParam = request()->only('page', 'q');

        return redirect()->route('vehicles.index', $routeParam);
    }

    /**
     * Remove the specified vehicle from storage.
     *
     * @param  \App\Vehicle  $vehicle
     * @return \Illuminate\Routing\Redirector
     */
    public function destroy(Vehicle $vehicle)
    {
        $this->authorize('delete', $vehicle);

        request()->validate([
            'vehicle_id' => 'required',
        ]);

        if (request('vehicle_id') == $vehicle->id && $vehicle->delete()) {
            $routeParam = request()->only('page', 'q');

            return redirect()->route('vehicles.index', $routeParam);
        }

        return back();
    }
}
