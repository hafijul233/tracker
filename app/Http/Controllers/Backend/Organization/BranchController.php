<?php

namespace App\Http\Controllers\Backend\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Organization\BranchRequest;
use App\Services\Auth\AuthenticatedSessionService;
use App\Services\Backend\Organization\BranchService;
use App\Supports\Utility;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @class BranchController
 * @package $NAMESPACE$
 */
class BranchController extends Controller
{
    /**
     * @var AuthenticatedSessionService
     */
    private $authenticatedSessionService;
    
    /**
     * @var BranchService
     */
    private $branchService;

    /**
     * BranchController Constructor
     *
     * @param AuthenticatedSessionService $authenticatedSessionService
     * @param BranchService $branchService
     */
    public function __construct(AuthenticatedSessionService $authenticatedSessionService,
                                BranchService              $branchService)
    {

        $this->authenticatedSessionService = $authenticatedSessionService;
        $this->branchService = $branchService;
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
        $branchs = $this->branchService->branchPaginate($filters);

        return view('backend.organization.branch.index', [
            'branchs' => $branchs
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('backend.organization.branch.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BranchRequest $request
     * @return RedirectResponse
     * @throws Exception|\Throwable
     */
    public function store(BranchRequest $request): RedirectResponse
    {
        $confirm = $this->branchService->storeBranch($request->except('_token'));
        if ($confirm['status'] == true) {
            notify($confirm['message'], $confirm['level'], $confirm['title']);
            return redirect()->route('backend.organization.branchs.index');
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
        if ($branch = $this->branchService->getBranchById($id)) {
            return view('backend.organization.branch.show', [
                'branch' => $branch,
                'timeline' => Utility::modelAudits($branch)
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
        if ($branch = $this->branchService->getBranchById($id)) {
            return view('backend.organization.branch.edit', [
                'branch' => $branch
            ]);
        }

        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BranchRequest $request
     * @param  $id
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function update(BranchRequest $request, $id): RedirectResponse
    {
        $confirm = $this->branchService->updateBranch($request->except('_token', 'submit', '_method'), $id);

        if ($confirm['status'] == true) {
            notify($confirm['message'], $confirm['level'], $confirm['title']);
            return redirect()->route('backend.organization.branchs.index');
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

            $confirm = $this->branchService->destroyBranch($id);

            if ($confirm['status'] == true) {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            } else {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            }
            return redirect()->route('backend.organization.branchs.index');
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

            $confirm = $this->branchService->restoreBranch($id);

            if ($confirm['status'] == true) {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            } else {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            }
            return redirect()->route('backend.organization.branchs.index');
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

        $branchExport = $this->branchService->exportBranch($filters);

        $filename = 'Branch-' . date('Ymd-His') . '.' . ($filters['format'] ?? 'xlsx');

        return $branchExport->download($filename, function ($branch) use ($branchExport) {
            return $branchExport->map($branch);
        });

    }

    /**
     * Return an Import view page
     *
     * @return Application|Factory|View
     */
    public function import()
    {
        return view('backend.organization.branchimport');
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
        $branchs = $this->branchService->getAllBranchs($filters);

        return view('backend.organization.branchindex', [
            'branchs' => $branchs
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

        $branchExport = $this->branchService->exportBranch($filters);

        $filename = 'Branch-' . date('Ymd-His') . '.' . ($filters['format'] ?? 'xlsx');

        return $branchExport->download($filename, function ($branch) use ($branchExport) {
            return $branchExport->map($branch);
        });

    }
}
