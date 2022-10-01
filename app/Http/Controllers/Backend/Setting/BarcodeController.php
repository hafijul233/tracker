<?php

namespace App\Http\Controllers\Backend\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Setting\BarcodeRequest;
use App\Services\Auth\AuthenticatedSessionService;
use App\Services\Backend\Setting\BarcodeService;
use App\Supports\Utility;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @class BarcodeController
 * @package $NAMESPACE$
 */
class BarcodeController extends Controller
{
    /**
     * @var AuthenticatedSessionService
     */
    private $authenticatedSessionService;
    
    /**
     * @var BarcodeService
     */
    private $barcodeService;

    /**
     * BarcodeController Constructor
     *
     * @param AuthenticatedSessionService $authenticatedSessionService
     * @param BarcodeService $barcodeService
     */
    public function __construct(AuthenticatedSessionService $authenticatedSessionService,
                                BarcodeService              $barcodeService)
    {

        $this->authenticatedSessionService = $authenticatedSessionService;
        $this->barcodeService = $barcodeService;
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
        $barcodes = $this->barcodeService->barcodePaginate($filters);

        return view('backend.setting.barcode.index', [
            'barcodes' => $barcodes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('backend.setting.barcode.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BarcodeRequest $request
     * @return RedirectResponse
     * @throws Exception|\Throwable
     */
    public function store(BarcodeRequest $request): RedirectResponse
    {
        $confirm = $this->barcodeService->storeBarcode($request->except('_token'));
        if ($confirm['status'] == true) {
            notify($confirm['message'], $confirm['level'], $confirm['title']);
            return redirect()->route('backend.setting.barcodes.index');
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
        if ($barcode = $this->barcodeService->getBarcodeById($id)) {
            return view('backend.setting.barcode.show', [
                'barcode' => $barcode,
                'timeline' => Utility::modelAudits($barcode)
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
        if ($barcode = $this->barcodeService->getBarcodeById($id)) {
            return view('backend.setting.barcode.edit', [
                'barcode' => $barcode
            ]);
        }

        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BarcodeRequest $request
     * @param  $id
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function update(BarcodeRequest $request, $id): RedirectResponse
    {
        $confirm = $this->barcodeService->updateBarcode($request->except('_token', 'submit', '_method'), $id);

        if ($confirm['status'] == true) {
            notify($confirm['message'], $confirm['level'], $confirm['title']);
            return redirect()->route('backend.setting.barcodes.index');
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

            $confirm = $this->barcodeService->destroyBarcode($id);

            if ($confirm['status'] == true) {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            } else {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            }
            return redirect()->route('backend.setting.barcodes.index');
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

            $confirm = $this->barcodeService->restoreBarcode($id);

            if ($confirm['status'] == true) {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            } else {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            }
            return redirect()->route('backend.setting.barcodes.index');
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

        $barcodeExport = $this->barcodeService->exportBarcode($filters);

        $filename = 'Barcode-' . date('Ymd-His') . '.' . ($filters['format'] ?? 'xlsx');

        return $barcodeExport->download($filename, function ($barcode) use ($barcodeExport) {
            return $barcodeExport->map($barcode);
        });

    }

    /**
     * Return an Import view page
     *
     * @return Application|Factory|View
     */
    public function import()
    {
        return view('backend.setting.barcodeimport');
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
        $barcodes = $this->barcodeService->getAllBarcodes($filters);

        return view('backend.setting.barcodeindex', [
            'barcodes' => $barcodes
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

        $barcodeExport = $this->barcodeService->exportBarcode($filters);

        $filename = 'Barcode-' . date('Ymd-His') . '.' . ($filters['format'] ?? 'xlsx');

        return $barcodeExport->download($filename, function ($barcode) use ($barcodeExport) {
            return $barcodeExport->map($barcode);
        });

    }
}
