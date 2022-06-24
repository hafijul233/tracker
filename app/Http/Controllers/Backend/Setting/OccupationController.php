<?php

namespace App\Http\Controllers\Backend\Setting;

use App\Http\Requests\Backend\Setting\OccupationRequest;
use App\Services\Auth\AuthenticatedSessionService;
use App\Services\Backend\Setting\OccupationService;
use App\Supports\Utility;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;


/**
 * @class OccupationController
 * @package Contact
 */
class OccupationController extends Controller
{
    /**
     * @var AuthenticatedSessionService
     */
    private $authenticatedSessionService;

    /**
     * @var OccupationService
     */
    private $occupationService;

    /**
     * @param AuthenticatedSessionService $authenticatedSessionService
     * @param OccupationService $occupationService
     */
    public function __construct(AuthenticatedSessionService $authenticatedSessionService,
                                OccupationService              $occupationService)
    {

        $this->authenticatedSessionService = $authenticatedSessionService;
        $this->occupationService = $occupationService;
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
        $occupations = $this->occupationService->occupationPaginate($filters);

        return view('setting.occupation.index', [
            'occupations' => $occupations
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('setting.occupation.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param OccupationRequest $request
     * @return RedirectResponse
     * @throws Exception|Throwable
     */
    public function store(OccupationRequest $request): RedirectResponse
    {
        $confirm = $this->occupationService->storeOccupation($request->except('_token'));
        if ($confirm['status'] == true) {
            notify($confirm['message'], $confirm['level'], $confirm['title']);
            return redirect()->route('contact.settings.occupations.index');
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
        if ($occupation = $this->occupationService->getOccupationById($id)) {
            return view('setting.occupation.show', [
                'occupation' => $occupation,
                'timeline' => Utility::modelAudits($occupation)
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
        if ($occupation = $this->occupationService->getOccupationById($id)) {
            return view('setting.occupation.edit', [
                'occupation' => $occupation
            ]);
        }

        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param OccupationRequest $request
     * @param  $id
     * @return RedirectResponse
     * @throws Throwable
     */
    public function update(OccupationRequest $request, $id): RedirectResponse
    {
        $confirm = $this->occupationService->updateOccupation($request->except('_token', 'submit', '_method'), $id);

        if ($confirm['status'] == true) {
            notify($confirm['message'], $confirm['level'], $confirm['title']);
            return redirect()->route('contact.settings.occupations.index');
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
     * @throws Throwable
     */
    public function destroy($id, Request $request)
    {
        if ($this->authenticatedSessionService->validate($request)) {

            $confirm = $this->occupationService->destroyOccupation($id);

            if ($confirm['status'] == true) {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            } else {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            }
            return redirect()->route('contact.settings.occupations.index');
        }
        abort(403, 'Wrong user credentials');
    }

    /**
     * Restore a Soft Deleted Resource
     *
     * @param $id
     * @param Request $request
     * @return RedirectResponse|void
     * @throws Throwable
     */
    public function restore($id, Request $request)
    {
        if ($this->authenticatedSessionService->validate($request)) {

            $confirm = $this->occupationService->restoreOccupation($id);

            if ($confirm['status'] == true) {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            } else {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            }
            return redirect()->route('contact.settings.occupations.index');
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

        $occupationExport = $this->occupationService->exportOccupation($filters);

        $filename = 'Occupation-' . date('Ymd-His') . '.' . ($filters['format'] ?? 'xlsx');

        return $occupationExport->download($filename, function ($occupation) use ($occupationExport) {
            return $occupationExport->map($occupation);
        });

    }

    /**
     * Return an Import view page
     *
     * @return Application|Factory|View
     */
    public function import()
    {
        return view('setting.occupation.import');
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
        $occupations = $this->occupationService->getAllOccupations($filters);

        return view('setting.occupation.index', [
            'occupations' => $occupations
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

        $occupationExport = $this->occupationService->exportOccupation($filters);

        $filename = 'Occupation-' . date('Ymd-His') . '.' . ($filters['format'] ?? 'xlsx');

        return $occupationExport->download($filename, function ($occupation) use ($occupationExport) {
            return $occupationExport->map($occupation);
        });

    }
}
