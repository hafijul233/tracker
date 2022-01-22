<?php

namespace App\Http\Controllers\Backend\Shipment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Shipment\ItemRequest;
use App\Services\Auth\AuthenticatedSessionService;
use App\Services\Backend\Shipment\CustomerService;
use App\Services\Backend\Shipment\ItemService;
use App\Supports\Utility;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @class ItemController
 * @package $NAMESPACE$
 */
class ItemController extends Controller
{
    /**
     * @var AuthenticatedSessionService
     */
    private $authenticatedSessionService;

    /**
     * @var ItemService
     */
    private $itemService;
    /**
     * @var CustomerService
     */
    private $customerService;

    /**
     * ItemController Constructor
     *
     * @param AuthenticatedSessionService $authenticatedSessionService
     * @param ItemService $itemService
     * @param CustomerService $customerService
     */
    public function __construct(AuthenticatedSessionService $authenticatedSessionService,
                                ItemService                 $itemService,
                                CustomerService             $customerService)
    {

        $this->authenticatedSessionService = $authenticatedSessionService;
        $this->itemService = $itemService;
        $this->customerService = $customerService;
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
        $items = $this->itemService->itemPaginate($filters);

        return view('backend.shipment.item.index', [
            'items' => $items
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     * @throws Exception
     */
    public function create()
    {
        $customers = $this->customerService->getAllCustomers();

        return view('backend.shipment.item.create', [
            'customers' => $customers
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ItemRequest $request
     * @return RedirectResponse
     * @throws Exception|\Throwable
     */
    public function store(ItemRequest $request): RedirectResponse
    {
        $confirm = $this->itemService->storeItem($request->except(['_token', 'submit']));

        if ($confirm['status'] == true) {
            notify($confirm['message'], $confirm['level'], $confirm['title']);
            return redirect()->route('backend.shipment.items.index');
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
        if ($item = $this->itemService->getItemById($id)) {
            return view('backend.shipment.item.show', [
                'item' => $item,
                'timeline' => Utility::modelAudits($item)
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
        if ($item = $this->itemService->getItemById($id)) {
            return view('backend.shipment.item.edit', [
                'item' => $item
            ]);
        }

        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ItemRequest $request
     * @param  $id
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function update(ItemRequest $request, $id): RedirectResponse
    {
        $confirm = $this->itemService->updateItem($request->except('_token', 'submit', '_method'), $id);

        if ($confirm['status'] == true) {
            notify($confirm['message'], $confirm['level'], $confirm['title']);
            return redirect()->route('backend.shipment.items.index');
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

            $confirm = $this->itemService->destroyItem($id);

            if ($confirm['status'] == true) {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            } else {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            }
            return redirect()->route('backend.shipment.items.index');
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

            $confirm = $this->itemService->restoreItem($id);

            if ($confirm['status'] == true) {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            } else {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            }
            return redirect()->route('backend.shipment.items.index');
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

        $itemExport = $this->itemService->exportItem($filters);

        $filename = 'Item-' . date('Ymd-His') . '.' . ($filters['format'] ?? 'xlsx');

        return $itemExport->download($filename, function ($item) use ($itemExport) {
            return $itemExport->map($item);
        });

    }

    /**
     * Return an Import view page
     *
     * @return Application|Factory|View
     */
    public function import()
    {
        return view('backend.shipment.itemimport');
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
        $items = $this->itemService->getAllItems($filters);

        return view('backend.shipment.itemindex', [
            'items' => $items
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

        $itemExport = $this->itemService->exportItem($filters);

        $filename = 'Item-' . date('Ymd-His') . '.' . ($filters['format'] ?? 'xlsx');

        return $itemExport->download($filename, function ($item) use ($itemExport) {
            return $itemExport->map($item);
        });

    }
}
