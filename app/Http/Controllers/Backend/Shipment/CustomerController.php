<?php

namespace App\Http\Controllers\Backend\Shipment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Shipment\CustomerRequest;
use App\Services\Auth\AuthenticatedSessionService;
use App\Services\Backend\Setting\RoleService;
use App\Services\Backend\Setting\StateService;
use App\Services\Backend\Shipment\CustomerService;
use App\Supports\Constant;
use App\Supports\Utility;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @class CustomerController
 * @package App\Http\Controllers\Backend\Shipment
 */
class CustomerController extends Controller
{
    /**
     * @var AuthenticatedSessionService
     */
    private $authenticatedSessionService;

    /**
     * @var CustomerService
     */
    private $customerService;
    /**
     * @var RoleService
     */
    private $roleService;
    /**
     * @var StateService
     */
    private $stateService;

    /**
     * CustomerController Constructor
     *
     * @param AuthenticatedSessionService $authenticatedSessionService
     * @param CustomerService $customerService
     * @param RoleService $roleService
     * @param StateService $stateService
     */
    public function __construct(AuthenticatedSessionService $authenticatedSessionService,
                                CustomerService             $customerService,
                                RoleService                 $roleService,
                                StateService                $stateService)
    {

        $this->authenticatedSessionService = $authenticatedSessionService;
        $this->customerService = $customerService;
        $this->roleService = $roleService;
        $this->stateService = $stateService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     * @throws Exception
     */
    public function index(Request $request)
    {
        $filters = $request->except('page', 'sort', 'direction');
        $customers = $this->customerService->customerPaginate($filters);

        return view('backend.shipment.customer.index', [
            'customers' => $customers
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
        $roles = $this->roleService->roleDropdown([
            'id' => [Constant::SENDER_ROLE_ID, Constant::RECEIVER_ROLE_ID],
            'enabled' => Constant::ENABLED_OPTION
        ]);

        $states = $this->stateService->getStateDropdown([
            'country' => 19,
            'type' => 'district'
        ]);

        return view('backend.shipment.customer.create', [
            'roles' => $roles,
            'states' => $states
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CustomerRequest $request
     * @return RedirectResponse
     * @throws Exception|\Throwable
     */
    public function store(Request $request): RedirectResponse
    {
        $inputs = $request->except('_token');

        $photo = $request->file('photo');

        $confirm = $this->customerService->storeCustomer($inputs, $photo);
        if ($confirm['status'] == true) {
            notify($confirm['message'], $confirm['level'], $confirm['title']);
            return redirect()->route('backend.shipment.customers.index');
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
        if ($customer = $this->customerService->getCustomerById($id)) {
            return view('backend.shipment.customer.show', [
                'customer' => $customer,
                'timeline' => Utility::modelAudits($customer)
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
        if ($customer = $this->customerService->getCustomerById($id)) {
            return view('backend.shipment.customer.edit', [
                'customer' => $customer
            ]);
        }

        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CustomerRequest $request
     * @param  $id
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function update(CustomerRequest $request, $id): RedirectResponse
    {
        $confirm = $this->customerService->updateCustomer($request->except('_token', 'submit', '_method'), $id);

        if ($confirm['status'] == true) {
            notify($confirm['message'], $confirm['level'], $confirm['title']);
            return redirect()->route('backend.shipment.customers.index');
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

            $confirm = $this->customerService->destroyCustomer($id);

            if ($confirm['status'] == true) {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            } else {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            }
            return redirect()->route('backend.shipment.customers.index');
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

            $confirm = $this->customerService->restoreCustomer($id);

            if ($confirm['status'] == true) {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            } else {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            }
            return redirect()->route('backend.shipment.customers.index');
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

        $customerExport = $this->customerService->exportCustomer($filters);

        $filename = 'Customer-' . date('Ymd-His') . '.' . ($filters['format'] ?? 'xlsx');

        return $customerExport->download($filename, function ($customer) use ($customerExport) {
            return $customerExport->map($customer);
        });

    }

    /**
     * Return an Import view page
     *
     * @return Application|Factory|View
     */
    public function import()
    {
        return view('backend.shipment.customerimport');
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
        $customers = $this->customerService->getAllCustomers($filters);

        return view('backend.shipment.customerindex', [
            'customers' => $customers
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

        $customerExport = $this->customerService->exportCustomer($filters);

        $filename = 'Customer-' . date('Ymd-His') . '.' . ($filters['format'] ?? 'xlsx');

        return $customerExport->download($filename, function ($customer) use ($customerExport) {
            return $customerExport->map($customer);
        });

    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function ajax(Request $request): JsonResponse
    {
        $filters = $request->except('page', 'sort', 'direction');
        $customers = $this->customerService->getAllCustomers($filters, ['media', 'roles']);

        if(count($customers) > 0):
            $jsonReturn = ['status' => true, 'data' => $customers];
        else :
            $jsonReturn = ['status' => false, 'data' => []];
        endif;

        return response()->json($jsonReturn, 200);
    }
}
