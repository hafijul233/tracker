<?php

namespace App\Http\Controllers\Backend\Transport;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Transport\DriverRequest;
use App\Services\Auth\AuthenticatedSessionService;
use App\Services\Backend\Transport\DriverService;
use App\Supports\Utility;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @class DriverController
 * @package $NAMESPACE$
 */
class DriverController extends Controller
{
    /**
     * @var AuthenticatedSessionService
     */
    private $authenticatedSessionService;
    
    /**
     * @var DriverService
     */
    private $driverService;

    /**
     * DriverController Constructor
     *
     * @param AuthenticatedSessionService $authenticatedSessionService
     * @param DriverService $driverService
     */
    public function __construct(AuthenticatedSessionService $authenticatedSessionService,
                                DriverService              $driverService)
    {

        $this->authenticatedSessionService = $authenticatedSessionService;
        $this->driverService = $driverService;
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
        $drivers = $this->driverService->driverPaginate($filters);

        return view('backend.transport.driver.index', [
            'drivers' => $drivers
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('backend.transport.driver.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DriverRequest $request
     * @return RedirectResponse
     * @throws Exception|\Throwable
     */
    public function store(DriverRequest $request): RedirectResponse
    {
        $confirm = $this->driverService->storeDriver($request->except('_token'));
        if ($confirm['status'] == true) {
            notify($confirm['message'], $confirm['level'], $confirm['title']);
            return redirect()->route('backend.transport.drivers.index');
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
        if ($driver = $this->driverService->getDriverById($id)) {
            return view('backend.transport.driver.show', [
                'driver' => $driver,
                'timeline' => Utility::modelAudits($driver)
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
        if ($driver = $this->driverService->getDriverById($id)) {
            return view('backend.transport.driver.edit', [
                'driver' => $driver
            ]);
        }

        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param DriverRequest $request
     * @param  $id
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function update(DriverRequest $request, $id): RedirectResponse
    {
        $confirm = $this->driverService->updateDriver($request->except('_token', 'submit', '_method'), $id);

        if ($confirm['status'] == true) {
            notify($confirm['message'], $confirm['level'], $confirm['title']);
            return redirect()->route('backend.transport.drivers.index');
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

            $confirm = $this->driverService->destroyDriver($id);

            if ($confirm['status'] == true) {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            } else {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            }
            return redirect()->route('backend.transport.drivers.index');
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

            $confirm = $this->driverService->restoreDriver($id);

            if ($confirm['status'] == true) {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            } else {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            }
            return redirect()->route('backend.transport.drivers.index');
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

        $driverExport = $this->driverService->exportDriver($filters);

        $filename = 'Driver-' . date('Ymd-His') . '.' . ($filters['format'] ?? 'xlsx');

        return $driverExport->download($filename, function ($driver) use ($driverExport) {
            return $driverExport->map($driver);
        });

    }

    /**
     * Return an Import view page
     *
     * @return Application|Factory|View
     */
    public function import()
    {
        return view('backend.transport.driverimport');
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
        $drivers = $this->driverService->getAllDrivers($filters);

        return view('backend.transport.driverindex', [
            'drivers' => $drivers
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

        $driverExport = $this->driverService->exportDriver($filters);

        $filename = 'Driver-' . date('Ymd-His') . '.' . ($filters['format'] ?? 'xlsx');

        return $driverExport->download($filename, function ($driver) use ($driverExport) {
            return $driverExport->map($driver);
        });

    }
}
