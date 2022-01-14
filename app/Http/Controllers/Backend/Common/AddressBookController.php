<?php

namespace Modules\Contact\Http\Controllers\Backend\Common;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Auth\Services\AuthenticatedSessionService;
use Modules\Core\Supports\Utility;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Modules\Contact\Services\Backend\Common\AddressBookService;
use Modules\Contact\Http\Requests\Backend\Common\AddressBookRequest;

/**
 * @class AddressBookController
 * @package $NAMESPACE$
 */
class AddressBookController extends Controller
{
    /**
     * @var AuthenticatedSessionService
     */
    private $authenticatedSessionService;
    
    /**
     * @var AddressBookService
     */
    private $addressbookService;

    /**
     * AddressBookController Constructor
     *
     * @param AuthenticatedSessionService $authenticatedSessionService
     * @param AddressBookService $addressbookService
     */
    public function __construct(AuthenticatedSessionService $authenticatedSessionService,
                                AddressBookService              $addressbookService)
    {

        $this->authenticatedSessionService = $authenticatedSessionService;
        $this->addressbookService = $addressbookService;
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
        $addressbooks = $this->addressbookService->addressbookPaginate($filters);

        return view('contact::backend.common.addressbook.index', [
            'addressbooks' => $addressbooks
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('contact::backend.common.addressbook.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AddressBookRequest $request
     * @return RedirectResponse
     * @throws Exception|\Throwable
     */
    public function store(AddressBookRequest $request): RedirectResponse
    {
        $confirm = $this->addressbookService->storeAddressBook($request->except('_token'));
        if ($confirm['status'] == true) {
            notify($confirm['message'], $confirm['level'], $confirm['title']);
            return redirect()->route('contact.backend.common.addressbooks.index');
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
        if ($addressbook = $this->addressbookService->getAddressBookById($id)) {
            return view('contact::backend.common.addressbook.show', [
                'addressbook' => $addressbook,
                'timeline' => Utility::modelAudits($addressbook)
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
        if ($addressbook = $this->addressbookService->getAddressBookById($id)) {
            return view('contact::backend.common.addressbook.edit', [
                'addressbook' => $addressbook
            ]);
        }

        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AddressBookRequest $request
     * @param  $id
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function update(AddressBookRequest $request, $id): RedirectResponse
    {
        $confirm = $this->addressbookService->updateAddressBook($request->except('_token', 'submit', '_method'), $id);

        if ($confirm['status'] == true) {
            notify($confirm['message'], $confirm['level'], $confirm['title']);
            return redirect()->route('contact.backend.common.addressbooks.index');
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

            $confirm = $this->addressbookService->destroyAddressBook($id);

            if ($confirm['status'] == true) {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            } else {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            }
            return redirect()->route('contact.backend.common.addressbooks.index');
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

            $confirm = $this->addressbookService->restoreAddressBook($id);

            if ($confirm['status'] == true) {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            } else {
                notify($confirm['message'], $confirm['level'], $confirm['title']);
            }
            return redirect()->route('contact.backend.common.addressbooks.index');
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

        $addressbookExport = $this->addressbookService->exportAddressBook($filters);

        $filename = 'AddressBook-' . date('Ymd-His') . '.' . ($filters['format'] ?? 'xlsx');

        return $addressbookExport->download($filename, function ($addressbook) use ($addressbookExport) {
            return $addressbookExport->map($addressbook);
        });

    }

    /**
     * Return an Import view page
     *
     * @return Application|Factory|View
     */
    public function import()
    {
        return view('contact::backend.common.addressbookimport');
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
        $addressbooks = $this->addressbookService->getAllAddressBooks($filters);

        return view('contact::backend.common.addressbookindex', [
            'addressbooks' => $addressbooks
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

        $addressbookExport = $this->addressbookService->exportAddressBook($filters);

        $filename = 'AddressBook-' . date('Ymd-His') . '.' . ($filters['format'] ?? 'xlsx');

        return $addressbookExport->download($filename, function ($addressbook) use ($addressbookExport) {
            return $addressbookExport->map($addressbook);
        });

    }
}
