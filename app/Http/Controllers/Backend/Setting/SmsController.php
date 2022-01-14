<?php

namespace App\Http\Controllers\Backend\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Setting\SmsRequest;
use App\Services\Auth\AuthenticatedSessionService;
use App\Services\Backend\Setting\SmsService;
use App\Supports\Utility;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @class SmsController
 * @package $NAMESPACE$
 */
class SmsController extends Controller
{
    /**
     * @var AuthenticatedSessionService
     */
    private $authenticatedSessionService;
    
    /**
     * @var SmsService
     */
    private $smsService;

    /**
     * SmsController Constructor
     *
     * @param AuthenticatedSessionService $authenticatedSessionService
     * @param SmsService $smsService
     */
    public function __construct(AuthenticatedSessionService $authenticatedSessionService,
                                SmsService              $smsService)
    {

        $this->authenticatedSessionService = $authenticatedSessionService;
        $this->smsService = $smsService;
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
        $smss = $this->smsService->smsPaginate($filters);

        return view('backend.setting.sms.index', [
            'smss' => $smss
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('backend.setting.sms.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SmsRequest $request
     * @return RedirectResponse
     * @throws Exception|\Throwable
     */
    public function store(SmsRequest $request): RedirectResponse
    {
        $confirm = $this->smsService->storeSms($request->except('_token'));
        if ($confirm['status'] == true) {
            notify($confirm['message'], $confirm['level'], $confirm['title']);
            return redirect()->route('contact.backend.setting.smss.index');
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
        if ($sms = $this->smsService->getSmsById($id)) {
            return view('backend.setting.sms.show', [
                'sms' => $sms,
                'timeline' => Utility::modelAudits($sms)
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
        if ($sms = $this->smsService->getSmsById($id)) {
            return view('backend.setting.sms.edit', [
                'sms' => $sms
            ]);
        }

        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SmsRequest $request
     * @param  $id
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function update(SmsRequest $request, $id): RedirectResponse
    {
        $confirm = $this->smsService->updateSms($request->except('_token', 'submit', '_method'), $id);

        if ($confirm['status'] == true) {
            notify($confirm['message'], $confirm['level'], $confirm['title']);
            return redirect()->route('contact.backend.setting.smss.index');
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

            $confirm = $this->smsService->destroySms($id);

            if ($confirm['status'] == true) {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            } else {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            }
            return redirect()->route('contact.backend.setting.smss.index');
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

            $confirm = $this->smsService->restoreSms($id);

            if ($confirm['status'] == true) {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            } else {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            }
            return redirect()->route('contact.backend.setting.smss.index');
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

        $smsExport = $this->smsService->exportSms($filters);

        $filename = 'Sms-' . date('Ymd-His') . '.' . ($filters['format'] ?? 'xlsx');

        return $smsExport->download($filename, function ($sms) use ($smsExport) {
            return $smsExport->map($sms);
        });

    }

    /**
     * Return an Import view page
     *
     * @return Application|Factory|View
     */
    public function import()
    {
        return view('backend.setting.smsimport');
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
        $smss = $this->smsService->getAllSmss($filters);

        return view('backend.setting.smsindex', [
            'smss' => $smss
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

        $smsExport = $this->smsService->exportSms($filters);

        $filename = 'Sms-' . date('Ymd-His') . '.' . ($filters['format'] ?? 'xlsx');

        return $smsExport->download($filename, function ($sms) use ($smsExport) {
            return $smsExport->map($sms);
        });

    }
}
