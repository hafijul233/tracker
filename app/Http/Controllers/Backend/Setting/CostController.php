<?php

namespace App\Http\Controllers\Backend\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Setting\CostRequest;
use App\Services\Auth\AuthenticatedSessionService;
use App\Services\Backend\Setting\CostService;
use App\Supports\Utility;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @class CostController
 * @package $NAMESPACE$
 */
class CostController extends Controller
{
    /**
     * @var AuthenticatedSessionService
     */
    private $authenticatedSessionService;
    
    /**
     * @var CostService
     */
    private $costService;

    /**
     * CostController Constructor
     *
     * @param AuthenticatedSessionService $authenticatedSessionService
     * @param CostService $costService
     */
    public function __construct(AuthenticatedSessionService $authenticatedSessionService,
                                CostService              $costService)
    {

        $this->authenticatedSessionService = $authenticatedSessionService;
        $this->costService = $costService;
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
        $costs = $this->costService->costPaginate($filters);

        return view('backend.setting.cost.index', [
            'costs' => $costs
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('backend.setting.cost.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CostRequest $request
     * @return RedirectResponse
     * @throws Exception|\Throwable
     */
    public function store(CostRequest $request): RedirectResponse
    {
        $confirm = $this->costService->storeCost($request->except('_token'));
        if ($confirm['status'] == true) {
            notify($confirm['message'], $confirm['level'], $confirm['title']);
            return redirect()->route('contact.backend.setting.costs.index');
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
        if ($cost = $this->costService->getCostById($id)) {
            return view('backend.setting.cost.show', [
                'cost' => $cost,
                'timeline' => Utility::modelAudits($cost)
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
        if ($cost = $this->costService->getCostById($id)) {
            return view('backend.setting.cost.edit', [
                'cost' => $cost
            ]);
        }

        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CostRequest $request
     * @param  $id
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function update(CostRequest $request, $id): RedirectResponse
    {
        $confirm = $this->costService->updateCost($request->except('_token', 'submit', '_method'), $id);

        if ($confirm['status'] == true) {
            notify($confirm['message'], $confirm['level'], $confirm['title']);
            return redirect()->route('contact.backend.setting.costs.index');
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

            $confirm = $this->costService->destroyCost($id);

            if ($confirm['status'] == true) {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            } else {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            }
            return redirect()->route('contact.backend.setting.costs.index');
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

            $confirm = $this->costService->restoreCost($id);

            if ($confirm['status'] == true) {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            } else {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            }
            return redirect()->route('contact.backend.setting.costs.index');
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

        $costExport = $this->costService->exportCost($filters);

        $filename = 'Cost-' . date('Ymd-His') . '.' . ($filters['format'] ?? 'xlsx');

        return $costExport->download($filename, function ($cost) use ($costExport) {
            return $costExport->map($cost);
        });

    }

    /**
     * Return an Import view page
     *
     * @return Application|Factory|View
     */
    public function import()
    {
        return view('backend.setting.costimport');
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
        $costs = $this->costService->getAllCosts($filters);

        return view('backend.setting.costindex', [
            'costs' => $costs
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

        $costExport = $this->costService->exportCost($filters);

        $filename = 'Cost-' . date('Ymd-His') . '.' . ($filters['format'] ?? 'xlsx');

        return $costExport->download($filename, function ($cost) use ($costExport) {
            return $costExport->map($cost);
        });

    }
}
