<?php

namespace App\Http\Controllers\Backend\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Organization\EmployeeRequest;
use App\Services\Auth\AuthenticatedSessionService;
use App\Services\Backend\Organization\EmployeeService;
use App\Supports\Utility;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @class EmployeeController
 * @package $NAMESPACE$
 */
class EmployeeController extends Controller
{
    /**
     * @var AuthenticatedSessionService
     */
    private $authenticatedSessionService;
    
    /**
     * @var EmployeeService
     */
    private $employeeService;

    /**
     * EmployeeController Constructor
     *
     * @param AuthenticatedSessionService $authenticatedSessionService
     * @param EmployeeService $employeeService
     */
    public function __construct(AuthenticatedSessionService $authenticatedSessionService,
                                EmployeeService              $employeeService)
    {

        $this->authenticatedSessionService = $authenticatedSessionService;
        $this->employeeService = $employeeService;
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
        $employees = $this->employeeService->employeePaginate($filters);

        return view('backend.organization.employee.index', [
            'employees' => $employees
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('backend.organization.employee.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EmployeeRequest $request
     * @return RedirectResponse
     * @throws Exception|\Throwable
     */
    public function store(EmployeeRequest $request): RedirectResponse
    {
        $confirm = $this->employeeService->storeEmployee($request->except('_token'));
        if ($confirm['status'] == true) {
            notify($confirm['message'], $confirm['level'], $confirm['title']);
            return redirect()->route('backend.organization.employees.index');
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
        if ($employee = $this->employeeService->getEmployeeById($id)) {
            return view('backend.organization.employee.show', [
                'employee' => $employee,
                'timeline' => Utility::modelAudits($employee)
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
        if ($employee = $this->employeeService->getEmployeeById($id)) {
            return view('backend.organization.employee.edit', [
                'employee' => $employee
            ]);
        }

        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EmployeeRequest $request
     * @param  $id
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function update(EmployeeRequest $request, $id): RedirectResponse
    {
        $confirm = $this->employeeService->updateEmployee($request->except('_token', 'submit', '_method'), $id);

        if ($confirm['status'] == true) {
            notify($confirm['message'], $confirm['level'], $confirm['title']);
            return redirect()->route('backend.organization.employees.index');
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

            $confirm = $this->employeeService->destroyEmployee($id);

            if ($confirm['status'] == true) {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            } else {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            }
            return redirect()->route('backend.organization.employees.index');
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

            $confirm = $this->employeeService->restoreEmployee($id);

            if ($confirm['status'] == true) {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            } else {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            }
            return redirect()->route('backend.organization.employees.index');
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

        $employeeExport = $this->employeeService->exportEmployee($filters);

        $filename = 'Employee-' . date('Ymd-His') . '.' . ($filters['format'] ?? 'xlsx');

        return $employeeExport->download($filename, function ($employee) use ($employeeExport) {
            return $employeeExport->map($employee);
        });

    }

    /**
     * Return an Import view page
     *
     * @return Application|Factory|View
     */
    public function import()
    {
        return view('backend.organization.employeeimport');
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
        $employees = $this->employeeService->getAllEmployees($filters);

        return view('backend.organization.employeeindex', [
            'employees' => $employees
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

        $employeeExport = $this->employeeService->exportEmployee($filters);

        $filename = 'Employee-' . date('Ymd-His') . '.' . ($filters['format'] ?? 'xlsx');

        return $employeeExport->download($filename, function ($employee) use ($employeeExport) {
            return $employeeExport->map($employee);
        });

    }
}
