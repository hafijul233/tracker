<?php

namespace App\Http\Controllers\Backend\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Setting\SmsTemplateRequest;
use App\Services\Auth\AuthenticatedSessionService;
use App\Services\Backend\Setting\SmsTemplateService;
use App\Supports\Utility;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @class SmsTemplateController
 * @package $NAMESPACE$
 */
class SmsTemplateController extends Controller
{
    /**
     * @var AuthenticatedSessionService
     */
    private $authenticatedSessionService;
    
    /**
     * @var SmsTemplateService
     */
    private $smstemplateService;

    /**
     * SmsTemplateController Constructor
     *
     * @param AuthenticatedSessionService $authenticatedSessionService
     * @param SmsTemplateService $smstemplateService
     */
    public function __construct(AuthenticatedSessionService $authenticatedSessionService,
                                SmsTemplateService              $smstemplateService)
    {

        $this->authenticatedSessionService = $authenticatedSessionService;
        $this->smstemplateService = $smstemplateService;
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
        $smstemplates = $this->smstemplateService->smstemplatePaginate($filters);

        return view('backend.setting.smstemplate.index', [
            'smstemplates' => $smstemplates
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('backend.setting.smstemplate.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SmsTemplateRequest $request
     * @return RedirectResponse
     * @throws Exception|\Throwable
     */
    public function store(SmsTemplateRequest $request): RedirectResponse
    {
        $confirm = $this->smstemplateService->storeSmsTemplate($request->except('_token'));
        if ($confirm['status'] == true) {
            notify($confirm['message'], $confirm['level'], $confirm['title']);
            return redirect()->route('contact.backend.setting.smstemplates.index');
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
        if ($smstemplate = $this->smstemplateService->getSmsTemplateById($id)) {
            return view('backend.setting.smstemplate.show', [
                'smstemplate' => $smstemplate,
                'timeline' => Utility::modelAudits($smstemplate)
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
        if ($smstemplate = $this->smstemplateService->getSmsTemplateById($id)) {
            return view('backend.setting.smstemplate.edit', [
                'smstemplate' => $smstemplate
            ]);
        }

        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SmsTemplateRequest $request
     * @param  $id
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function update(SmsTemplateRequest $request, $id): RedirectResponse
    {
        $confirm = $this->smstemplateService->updateSmsTemplate($request->except('_token', 'submit', '_method'), $id);

        if ($confirm['status'] == true) {
            notify($confirm['message'], $confirm['level'], $confirm['title']);
            return redirect()->route('contact.backend.setting.smstemplates.index');
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

            $confirm = $this->smstemplateService->destroySmsTemplate($id);

            if ($confirm['status'] == true) {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            } else {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            }
            return redirect()->route('contact.backend.setting.smstemplates.index');
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

            $confirm = $this->smstemplateService->restoreSmsTemplate($id);

            if ($confirm['status'] == true) {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            } else {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            }
            return redirect()->route('contact.backend.setting.smstemplates.index');
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

        $smstemplateExport = $this->smstemplateService->exportSmsTemplate($filters);

        $filename = 'SmsTemplate-' . date('Ymd-His') . '.' . ($filters['format'] ?? 'xlsx');

        return $smstemplateExport->download($filename, function ($smstemplate) use ($smstemplateExport) {
            return $smstemplateExport->map($smstemplate);
        });

    }

    /**
     * Return an Import view page
     *
     * @return Application|Factory|View
     */
    public function import()
    {
        return view('backend.setting.smstemplateimport');
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
        $smstemplates = $this->smstemplateService->getAllSmsTemplates($filters);

        return view('backend.setting.smstemplateindex', [
            'smstemplates' => $smstemplates
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

        $smstemplateExport = $this->smstemplateService->exportSmsTemplate($filters);

        $filename = 'SmsTemplate-' . date('Ymd-His') . '.' . ($filters['format'] ?? 'xlsx');

        return $smstemplateExport->download($filename, function ($smstemplate) use ($smstemplateExport) {
            return $smstemplateExport->map($smstemplate);
        });

    }
}
