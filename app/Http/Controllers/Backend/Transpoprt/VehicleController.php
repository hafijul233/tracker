<?php

namespace App\Http\Controllers\Backend\Transpoprt;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Transpoprt\VehicleRequest;
use App\Services\Auth\AuthenticatedSessionService;
use App\Services\Backend\Transpoprt\VehicleService;
use App\Supports\Utility;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @class VehicleController
 * @package $NAMESPACE$
 */
class VehicleController extends Controller
{
    /**
     * @var AuthenticatedSessionService
     */
    private $authenticatedSessionService;
    
    /**
     * @var VehicleService
     */
    private $vehicleService;

    /**
     * VehicleController Constructor
     *
     * @param AuthenticatedSessionService $authenticatedSessionService
     * @param VehicleService $vehicleService
     */
    public function __construct(AuthenticatedSessionService $authenticatedSessionService,
                                VehicleService              $vehicleService)
    {

        $this->authenticatedSessionService = $authenticatedSessionService;
        $this->vehicleService = $vehicleService;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     * @throws Exception
     */
    public function index(Request $request)
    {
        $filters = $request->except('page');
        $vehicles = $this->vehicleService->vehiclePaginate($filters);

        return view('backend.transpoprt.vehicle.index', [
            'vehicles' => $vehicles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('backend.transpoprt.vehicle.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param VehicleRequest $request
     * @return RedirectResponse
     * @throws Exception|\Throwable
     */
    public function store(VehicleRequest $request): RedirectResponse
    {
        $confirm = $this->vehicleService->storeVehicle($request->except('_token'));
        if ($confirm['status'] == true) {
            notify($confirm['message'], $confirm['level'], $confirm['title']);
            return redirect()->route('contact.backend.transpoprt.vehicles.index');
        }

        notify($confirm['message'], $confirm['level'], $confirm['title']);
        return redirect()->back()->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return Application|Factory|View
     * @throws Exception
     */
    public function show($id)
    {
        if ($vehicle = $this->vehicleService->getVehicleById($id)) {
            return view('backend.transpoprt.vehicle.show', [
                'vehicle' => $vehicle,
                'timeline' => Utility::modelAudits($vehicle)
            ]);
        }

        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return Application|Factory|View
     * @throws Exception
     */
    public function edit($id)
    {
        if ($vehicle = $this->vehicleService->getVehicleById($id)) {
            return view('backend.transpoprt.vehicle.edit', [
                'vehicle' => $vehicle
            ]);
        }

        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param VehicleRequest $request
     * @param  $id
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function update(VehicleRequest $request, $id): RedirectResponse
    {
        $confirm = $this->vehicleService->updateVehicle($request->except('_token', 'submit', '_method'), $id);

        if ($confirm['status'] == true) {
            notify($confirm['message'], $confirm['level'], $confirm['title']);
            return redirect()->route('contact.backend.transpoprt.vehicles.index');
        }

        notify($confirm['message'], $confirm['level'], $confirm['title']);
        return redirect()->back()->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @param Request $request
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function destroy($id, Request $request)
    {
        if ($this->authenticatedSessionService->validate($request)) {

            $confirm = $this->vehicleService->destroyVehicle($id);

            if ($confirm['status'] == true) {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            } else {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            }
            return redirect()->route('contact.backend.transpoprt.vehicles.index');
        }
        abort(403, 'Wrong user credentials');
    }

    /**
     * Restore a Soft Deleted Resource
     *
     * @param $id
     * @param Request $request
     * @return RedirectResponse|void
     * @throws \Throwable
     */
    public function restore($id, Request $request)
    {
        if ($this->authenticatedSessionService->validate($request)) {

            $confirm = $this->vehicleService->restoreVehicle($id);

            if ($confirm['status'] == true) {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            } else {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            }
            return redirect()->route('contact.backend.transpoprt.vehicles.index');
        }
        abort(403, 'Wrong user credentials');
    }

    /**
     * Display a listing of the resource.
     *
     * @return string|StreamedResponse
     * @throws Exception
     */
    public function export(Request $request)
    {
        $filters = $request->except('page');

        $vehicleExport = $this->vehicleService->exportVehicle($filters);

        $filename = 'Vehicle-' . date('Ymd-His') . '.' . ($filters['format'] ?? 'xlsx');

        return $vehicleExport->download($filename, function ($vehicle) use ($vehicleExport) {
            return $vehicleExport->map($vehicle);
        });

    }

    /**
     * Return an Import view page
     *
     * @return Application|Factory|View
     */
    public function import()
    {
        return view('backend.transpoprt.vehicleimport');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     * @throws Exception
     */
    public function importBulk(Request $request)
    {
        $filters = $request->except('page');
        $vehicles = $this->vehicleService->getAllVehicles($filters);

        return view('backend.transpoprt.vehicleindex', [
            'vehicles' => $vehicles
        ]);
    }

    /**
     * Display a detail of the resource.
     *
     * @return StreamedResponse|string
     * @throws Exception
     */
    public function print(Request $request)
    {
        $filters = $request->except('page');

        $vehicleExport = $this->vehicleService->exportVehicle($filters);

        $filename = 'Vehicle-' . date('Ymd-His') . '.' . ($filters['format'] ?? 'xlsx');

        return $vehicleExport->download($filename, function ($vehicle) use ($vehicleExport) {
            return $vehicleExport->map($vehicle);
        });

    }
}
