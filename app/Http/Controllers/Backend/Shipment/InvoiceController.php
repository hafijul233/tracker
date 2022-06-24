<?php

namespace App\Http\Controllers\Backend\Shipment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Shipment\InvoiceRequest;
use App\Services\Auth\AuthenticatedSessionService;
use App\Services\Backend\Shipment\CustomerService;
use App\Services\Backend\Shipment\InvoiceService;
use App\Supports\Utility;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @class InvoiceController
 * @package $NAMESPACE$
 */
class InvoiceController extends Controller
{
    /**
     * @var AuthenticatedSessionService
     */
    private $authenticatedSessionService;

    /**
     * @var InvoiceService
     */
    private $invoiceService;

    /**
     * @var CustomerService
     */
    private $customerService;

    /**
     * InvoiceController Constructor
     *
     * @param AuthenticatedSessionService $authenticatedSessionService
     * @param InvoiceService $invoiceService
     * @param CustomerService $customerService
     */
    public function __construct(AuthenticatedSessionService $authenticatedSessionService,
                                InvoiceService              $invoiceService,
                                CustomerService             $customerService)
    {

        $this->authenticatedSessionService = $authenticatedSessionService;
        $this->invoiceService = $invoiceService;
        $this->customerService = $customerService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Application|Factory|View
     * @throws Exception
     */
    public function index(Request $request)
    {
        $filters = $request->except('page', 'sort', 'direction');
        $invoices = $this->invoiceService->invoicePaginate($filters);

        return view('backend.shipment.invoice.index', [
            'invoices' => $invoices
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {

        return view('backend.shipment.invoice.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param InvoiceRequest $request
     * @return RedirectResponse
     * @throws Exception|\Throwable
     */
    public function store(InvoiceRequest $request): RedirectResponse
    {
        dd($request->all());

        $confirm = $this->invoiceService->storeInvoice($request->except('_token'));
        if ($confirm['status'] == true) {
            notify($confirm['message'], $confirm['level'], $confirm['title']);
            return redirect()->route('backend.shipment.invoices.index');
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
        if ($invoice = $this->invoiceService->getInvoiceById($id)) {
            return view('backend.shipment.invoice.show', [
                'invoice' => $invoice,
                'timeline' => Utility::modelAudits($invoice)
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
        if ($invoice = $this->invoiceService->getInvoiceById($id)) {
            return view('backend.shipment.invoice.edit', [
                'invoice' => $invoice
            ]);
        }

        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param InvoiceRequest $request
     * @param  $id
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function update(InvoiceRequest $request, $id): RedirectResponse
    {
        $confirm = $this->invoiceService->updateInvoice($request->except('_token', 'submit', '_method'), $id);

        if ($confirm['status'] == true) {
            notify($confirm['message'], $confirm['level'], $confirm['title']);
            return redirect()->route('backend.shipment.invoices.index');
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

            $confirm = $this->invoiceService->destroyInvoice($id);

            if ($confirm['status'] == true) {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            } else {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            }
            return redirect()->route('backend.shipment.invoices.index');
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

            $confirm = $this->invoiceService->restoreInvoice($id);

            if ($confirm['status'] == true) {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            } else {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            }
            return redirect()->route('backend.shipment.invoices.index');
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
        $filters = $request->except('page', 'sort', 'direction');

        $invoiceExport = $this->invoiceService->exportInvoice($filters);

        $filename = 'Invoice-' . date('Ymd-His') . '.' . ($filters['format'] ?? 'xlsx');

        return $invoiceExport->download($filename, function ($invoice) use ($invoiceExport) {
            return $invoiceExport->map($invoice);
        });

    }

    /**
     * Return an Import view page
     *
     * @return Application|Factory|View
     */
    public function import()
    {
        return view('backend.shipment.invoiceimport');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     * @throws Exception
     */
    public function importBulk(Request $request)
    {
        $filters = $request->except('page', 'sort', 'direction');
        $invoices = $this->invoiceService->getAllInvoices($filters);

        return view('backend.shipment.invoiceindex', [
            'invoices' => $invoices
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
        $filters = $request->except('page', 'sort', 'direction');

        $invoiceExport = $this->invoiceService->exportInvoice($filters);

        $filename = 'Invoice-' . date('Ymd-His') . '.' . ($filters['format'] ?? 'xlsx');

        return $invoiceExport->download($filename, function ($invoice) use ($invoiceExport) {
            return $invoiceExport->map($invoice);
        });

    }
}
