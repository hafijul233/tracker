<?php

namespace App\Services\Backend\Common;

use App\Abstracts\Service\Service;
use App\Exports\Backend\Common\AddressBookExport;
use App\Models\Backend\Common\AddressBook;
use App\Repositories\Eloquent\Backend\Common\AddressBookRepository;
use App\Supports\Constant;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * @class AddressBookService
 * @package App\Services\Backend\Common
 */
class AddressBookService extends Service
{
/**
     * @var AddressBookRepository
     */
    private $addressBookRepository;

    /**
     * AddressBookService constructor.
     * @param AddressBookRepository $addressBookRepository
     */
    public function __construct(AddressBookRepository $addressBookRepository)
    {
        $this->addressBookRepository = $addressBookRepository;
        $this->addressBookRepository->itemsPerPage = 10;
    }

    /**
     * Get All AddressBook models as collection
     * 
     * @param array $filters
     * @param array $eagerRelations
     * @return Builder[]|Collection
     * @throws Exception
     */
    public function getAllAddressBooks(array $filters = [], array $eagerRelations = [])
    {
        return $this->addressBookRepository->getWith($filters, $eagerRelations, true);
    }

    /**
     * Create AddressBook Model Pagination
     * 
     * @param array $filters
     * @param array $eagerRelations
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function addressBookPaginate(array $filters = [], array $eagerRelations = []): LengthAwarePaginator
    {
        return $this->addressBookRepository->paginateWith($filters, $eagerRelations, true);
    }

    /**
     * Show AddressBook Model
     * 
     * @param int $id
     * @param bool $purge
     * @return mixed
     * @throws Exception
     */
    public function getAddressBookById($id, bool $purge = false)
    {
        return $this->addressBookRepository->show($id, $purge);
    }

    /**
     * Save AddressBook Model
     * 
     * @param array $inputs
     * @return array
     * @throws Exception
     * @throws Throwable
     */
    public function storeAddressBook(array $inputs): array
    {
        DB::beginTransaction();
        try {
            $newAddressBook = $this->addressBookRepository->create($inputs);
            if ($newAddressBook instanceof AddressBook) {
                DB::commit();
                return ['status' => true, 'message' => __('New AddressBook Created'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('New AddressBook Creation Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->addressBookRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Update AddressBook Model
     * 
     * @param array $inputs
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function updateAddressBook(array $inputs, $id): array
    {
        DB::beginTransaction();
        try {
            $addressBook = $this->addressBookRepository->show($id);
            if ($addressBook instanceof AddressBook) {
                if ($this->addressBookRepository->update($inputs, $id)) {
                    DB::commit();
                    return ['status' => true, 'message' => __('AddressBook Info Updated'),
                        'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
                } else {
                    DB::rollBack();
                    return ['status' => false, 'message' => __('AddressBook Info Update Failed'),
                        'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
                }
            } else {
                return ['status' => false, 'message' => __('AddressBook Model Not Found'),
                    'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->addressBookRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Destroy AddressBook Model
     * 
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function destroyAddressBook($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->addressBookRepository->delete($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('AddressBook is Trashed'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('AddressBook is Delete Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->addressBookRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Restore AddressBook Model
     * 
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function restoreAddressBook($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->addressBookRepository->restore($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('AddressBook is Restored'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('AddressBook is Restoration Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->addressBookRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Export Object for Export Download
     *
     * @param array $filters
     * @return AddressBookExport
     * @throws Exception
     */
    public function exportAddressBook(array $filters = []): AddressBookExport
    {
        return (new AddressBookExport($this->addressBookRepository->getWith($filters)));
    }
}
