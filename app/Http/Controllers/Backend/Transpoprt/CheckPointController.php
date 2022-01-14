<?php

namespace App\Http\Controllers\Backend\Transpoprt;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Transpoprt\CheckPointRequest;
use App\Services\Auth\AuthenticatedSessionService;
use App\Services\Backend\Transpoprt\CheckPointService;
use App\Supports\Utility;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @class CheckPointController
 * @package $NAMESPACE$
 */
class CheckPointController extends Controller
{
    /**
     * @var AuthenticatedSessionService
     */
    private $authenticatedSessionService;
    
    /**
     * @var CheckPointService
     */
    private $checkpointService;

    /**
     * CheckPointController Constructor
     *
     * @param AuthenticatedSessionService $authenticatedSessionService
     * @param CheckPointService $checkpointService
     */
    public function __construct(AuthenticatedSessionService $authenticatedSessionService,
                                CheckPointService              $checkpointService)
    {

        $this->authenticatedSessionService = $authenticatedSessionService;
        $this->checkpointService = $checkpointService;
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
        $checkpoints = $this->checkpointService->checkpointPaginate($filters);

        return view('backend.transpoprt.checkpoint.index', [
            'checkpoints' => $checkpoints
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('backend.transpoprt.checkpoint.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CheckPointRequest $request
     * @return RedirectResponse
     * @throws Exception|\Throwable
     */
    public function store(CheckPointRequest $request): RedirectResponse
    {
        $confirm = $this->checkpointService->storeCheckPoint($request->except('_token'));
        if ($confirm['status'] == true) {
            notify($confirm['message'], $confirm['level'], $confirm['title']);
            return redirect()->route('contact.backend.transpoprt.checkpoints.index');
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
        if ($checkpoint = $this->checkpointService->getCheckPointById($id)) {
            return view('backend.transpoprt.checkpoint.show', [
                'checkpoint' => $checkpoint,
                'timeline' => Utility::modelAudits($checkpoint)
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
        if ($checkpoint = $this->checkpointService->getCheckPointById($id)) {
            return view('backend.transpoprt.checkpoint.edit', [
                'checkpoint' => $checkpoint
            ]);
        }

        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CheckPointRequest $request
     * @param  $id
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function update(CheckPointRequest $request, $id): RedirectResponse
    {
        $confirm = $this->checkpointService->updateCheckPoint($request->except('_token', 'submit', '_method'), $id);

        if ($confirm['status'] == true) {
            notify($confirm['message'], $confirm['level'], $confirm['title']);
            return redirect()->route('contact.backend.transpoprt.checkpoints.index');
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

            $confirm = $this->checkpointService->destroyCheckPoint($id);

            if ($confirm['status'] == true) {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            } else {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            }
            return redirect()->route('contact.backend.transpoprt.checkpoints.index');
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

            $confirm = $this->checkpointService->restoreCheckPoint($id);

            if ($confirm['status'] == true) {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            } else {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            }
            return redirect()->route('contact.backend.transpoprt.checkpoints.index');
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

        $checkpointExport = $this->checkpointService->exportCheckPoint($filters);

        $filename = 'CheckPoint-' . date('Ymd-His') . '.' . ($filters['format'] ?? 'xlsx');

        return $checkpointExport->download($filename, function ($checkpoint) use ($checkpointExport) {
            return $checkpointExport->map($checkpoint);
        });

    }

    /**
     * Return an Import view page
     *
     * @return Application|Factory|View
     */
    public function import()
    {
        return view('backend.transpoprt.checkpointimport');
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
        $checkpoints = $this->checkpointService->getAllCheckPoints($filters);

        return view('backend.transpoprt.checkpointindex', [
            'checkpoints' => $checkpoints
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

        $checkpointExport = $this->checkpointService->exportCheckPoint($filters);

        $filename = 'CheckPoint-' . date('Ymd-His') . '.' . ($filters['format'] ?? 'xlsx');

        return $checkpointExport->download($filename, function ($checkpoint) use ($checkpointExport) {
            return $checkpointExport->map($checkpoint);
        });

    }
}
