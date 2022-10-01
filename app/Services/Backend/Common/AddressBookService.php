<?php

namespace App\Services\Backend\Common;

use App\Abstracts\Service\Service;
use App\Exports\Backend\Common\AddressBookExport;
use App\Models\Backend\Common\Address;
use App\Services\Auth\AuthenticatedSessionService;
use App\Supports\Constant;
use Box\Spout\Common\Exception\InvalidArgumentException;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * @class AddressBookService
 * @package App\Services\Backend\Common
 */
class AddressBookService extends Service
{
    /**
     * AddressBookService constructor.
     */
    public function __construct()
    {
        $this->setModel(Address::class);
    }

    /**
     * Get All Address models as collection
     *
     * @param array $filters
     * @param array $eagerRelations
     * @return Builder[]|Collection
     */
    public function getAllAddressBooks(array $filters = [], array $eagerRelations = [])
    {
        return $this->model
            ->applyFilter($filters)
            ->with($eagerRelations)
            ->get();
    }

    /**
     * Create Address Model Pagination
     *
     * @param array $filters
     * @param array $eagerRelations
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function addressBookPaginate(array $filters = [], array $eagerRelations = []): LengthAwarePaginator
    {
        if (!AuthenticatedSessionService::isSuperAdmin()):
            $filters['user_id'] = Auth::user()->id;
        else :
            $filters['user_id_distinct'] = true;
            $filters['only_fallback'] = Constant::ENABLED_OPTION;
        endif;

        return $this->model
            ->applyFilter($filters)
            ->with($eagerRelations)
            ->paginate();
    }

    /**
     * Show Address Model
     *
     * @param int $id
     * @param bool $purge
     * @return mixed
     * @throws Exception
     */
    public function getAddressBookById($id, bool $purge = false)
    {
        return $this->show($id, $purge);
    }

    /**
     * Save Address Model
     *
     * @param array $inputs
     * @return array
     * @throws Exception
     */
    public function storeAddressBook(array $inputs): array
    {
        DB::beginTransaction();
        try {
            $newAddressBook = $this->create($inputs);
            if ($newAddressBook instanceof Address) {
                DB::commit();
                return ['status' => true, 'message' => __('New Address Created'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
            }

            DB::rollBack();
            return ['status' => false, 'message' => __('New Address Creation Failed'),
                'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
        } catch (Exception $exception) {
            $this->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Update Address Model
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
            $addressBook = $this->show($id);
            if ($addressBook instanceof Address) {
                if ($this->update($inputs, $id)) {
                    DB::commit();
                    return ['status' => true, 'message' => __('Address Info Updated'),
                        'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
                } else {
                    DB::rollBack();
                    return ['status' => false, 'message' => __('Address Info Update Failed'),
                        'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
                }
            } else {
                return ['status' => false, 'message' => __('Address Model Not Found'),
                    'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Destroy Address Model
     *
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function destroyAddressBook($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->delete($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('Address is Trashed'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('Address is Delete Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Restore Address Model
     *
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function restoreAddressBook($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->restore($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('Address is Restored'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('Address is Restoration Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Export Object for Export Download
     *
     * @param array $filters
     * @param array $eagerRelations
     * @return AddressBookExport
     * @throws InvalidArgumentException
     */
    public function exportAddressBook(array $filters = [], array $eagerRelations = []): AddressBookExport
    {
        return (new AddressBookExport($this->getAllAddressBooks($filters, $eagerRelations)));
    }
}
