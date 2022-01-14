<?php

namespace App\Http\Controllers\Backend\Transport;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Shipment\TruckLoadRequest;
use App\Services\Auth\AuthenticatedSessionService;
use App\Services\Backend\Transport\TruckLoadService;
use App\Supports\Utility;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @class TruckLoadController
 * @package $NAMESPACE$
 */
class TruckLoadController extends Controller
{
    /**
     * @var AuthenticatedSessionService
     */
    private $authenticatedSessionService;
    
    /**
     * @var TruckLoadService
     */
    private $truckloadService;

    /**
     * TruckLoadController Constructor
     *
     * @param AuthenticatedSessionService $authenticatedSessionService
     * @param TruckLoadService $truckloadService
     */
    public function __construct(AuthenticatedSessionService $authenticatedSessionService,
                                TruckLoadService            $truckloadService)
    {

        $this->authenticatedSessionService = $authenticatedSessionService;
        $this->truckloadService = $truckloadService;
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
        $truckloads = $this->truckloadService->truckloadPaginate($filters);

        return view('backend.shipment.truckload.index', [
            'truckloads' => $truckloads
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('backend.shipment.truckload.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TruckLoadRequest $request
     * @return RedirectResponse
     * @throws Exception|\Throwable
     */
    public function store(TruckLoadRequest $request): RedirectResponse
    {
        $confirm = $this->truckloadService->storeTrackLoad($request->except('_token'));
        if ($confirm['status'] == true) {
            notify($confirm['message'], $confirm['level'], $confirm['title']);
            return redirect()->route('contact.backend.shipment.truckloads.index');
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
        if ($truckload = $this->truckloadService->getTrackLoadById($id)) {
            return view('backend.shipment.truckload.show', [
                'truckload' => $truckload,
                'timeline' => Utility::modelAudits($truckload)
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
        if ($truckload = $this->truckloadService->getTrackLoadById($id)) {
            return view('backend.shipment.truckload.edit', [
                'truckload' => $truckload
            ]);
        }

        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TruckLoadRequest $request
     * @param  $id
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function update(TruckLoadRequest $request, $id): RedirectResponse
    {
        $confirm = $this->truckloadService->updateTrackLoad($request->except('_token', 'submit', '_method'), $id);

        if ($confirm['status'] == true) {
            notify($confirm['message'], $confirm['level'], $confirm['title']);
            return redirect()->route('contact.backend.shipment.truckloads.index');
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

            $confirm = $this->truckloadService->destroyTrackLoad($id);

            if ($confirm['status'] == true) {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            } else {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            }
            return redirect()->route('contact.backend.shipment.truckloads.index');
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

            $confirm = $this->truckloadService->restoreTrackLoad($id);

            if ($confirm['status'] == true) {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            } else {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            }
            return redirect()->route('contact.backend.shipment.truckloads.index');
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

        $truckloadExport = $this->truckloadService->exportTrackLoad($filters);

        $filename = 'TruckLoad-' . date('Ymd-His') . '.' . ($filters['format'] ?? 'xlsx');

        return $truckloadExport->download($filename, function ($truckload) use ($truckloadExport) {
            return $truckloadExport->map($truckload);
        });

    }

    /**
     * Return an Import view page
     *
     * @return Application|Factory|View
     */
    public function import()
    {
        return view('backend.shipment.truckloadimport');
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
        $truckloads = $this->truckloadService->getAllTrackLoads($filters);

        return view('backend.shipment.truckloadindex', [
            'truckloads' => $truckloads
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

        $truckloadExport = $this->truckloadService->exportTrackLoad($filters);

        $filename = 'TruckLoad-' . date('Ymd-His') . '.' . ($filters['format'] ?? 'xlsx');

        return $truckloadExport->download($filename, function ($truckload) use ($truckloadExport) {
            return $truckloadExport->map($truckload);
        });

    }
}
