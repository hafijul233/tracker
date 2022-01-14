<?php

namespace App\Http\Controllers\Backend\Shipment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Shipment\TransactionRequest;
use App\Services\Auth\AuthenticatedSessionService;
use App\Services\Backend\Shipment\TransactionService;
use App\Supports\Utility;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @class TransactionController
 * @package $NAMESPACE$
 */
class TransactionController extends Controller
{
    /**
     * @var AuthenticatedSessionService
     */
    private $authenticatedSessionService;
    
    /**
     * @var TransactionService
     */
    private $transactionService;

    /**
     * TransactionController Constructor
     *
     * @param AuthenticatedSessionService $authenticatedSessionService
     * @param TransactionService $transactionService
     */
    public function __construct(AuthenticatedSessionService $authenticatedSessionService,
                                TransactionService              $transactionService)
    {

        $this->authenticatedSessionService = $authenticatedSessionService;
        $this->transactionService = $transactionService;
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
        $transactions = $this->transactionService->transactionPaginate($filters);

        return view('backend.shipment.transaction.index', [
            'transactions' => $transactions
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('backend.shipment.transaction.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TransactionRequest $request
     * @return RedirectResponse
     * @throws Exception|\Throwable
     */
    public function store(TransactionRequest $request): RedirectResponse
    {
        $confirm = $this->transactionService->storeTransaction($request->except('_token'));
        if ($confirm['status'] == true) {
            notify($confirm['message'], $confirm['level'], $confirm['title']);
            return redirect()->route('backend.shipment.transactions.index');
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
        if ($transaction = $this->transactionService->getTransactionById($id)) {
            return view('backend.shipment.transaction.show', [
                'transaction' => $transaction,
                'timeline' => Utility::modelAudits($transaction)
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
        if ($transaction = $this->transactionService->getTransactionById($id)) {
            return view('backend.shipment.transaction.edit', [
                'transaction' => $transaction
            ]);
        }

        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TransactionRequest $request
     * @param  $id
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function update(TransactionRequest $request, $id): RedirectResponse
    {
        $confirm = $this->transactionService->updateTransaction($request->except('_token', 'submit', '_method'), $id);

        if ($confirm['status'] == true) {
            notify($confirm['message'], $confirm['level'], $confirm['title']);
            return redirect()->route('backend.shipment.transactions.index');
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

            $confirm = $this->transactionService->destroyTransaction($id);

            if ($confirm['status'] == true) {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            } else {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            }
            return redirect()->route('backend.shipment.transactions.index');
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

            $confirm = $this->transactionService->restoreTransaction($id);

            if ($confirm['status'] == true) {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            } else {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            }
            return redirect()->route('backend.shipment.transactions.index');
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

        $transactionExport = $this->transactionService->exportTransaction($filters);

        $filename = 'Transaction-' . date('Ymd-His') . '.' . ($filters['format'] ?? 'xlsx');

        return $transactionExport->download($filename, function ($transaction) use ($transactionExport) {
            return $transactionExport->map($transaction);
        });

    }

    /**
     * Return an Import view page
     *
     * @return Application|Factory|View
     */
    public function import()
    {
        return view('backend.shipment.transactionimport');
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
        $transactions = $this->transactionService->getAllTransactions($filters);

        return view('backend.shipment.transactionindex', [
            'transactions' => $transactions
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

        $transactionExport = $this->transactionService->exportTransaction($filters);

        $filename = 'Transaction-' . date('Ymd-His') . '.' . ($filters['format'] ?? 'xlsx');

        return $transactionExport->download($filename, function ($transaction) use ($transactionExport) {
            return $transactionExport->map($transaction);
        });

    }
}
