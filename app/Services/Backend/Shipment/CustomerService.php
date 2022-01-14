<?php

namespace App\Services\Backend\Shipment;

use App\Abstracts\Service\Service;
use App\Models\Backend\Shipment\Customer;
use App\Repositories\Eloquent\Backend\Shipment\CustomerRepository;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Core\Supports\Constant;
use Throwable;

/**
 * @class CustomerService
 * @package App\Services\Backend\Shipment
 */
class CustomerService extends Service
{
/**
     * @var CustomerRepository
     */
    private $customerRepository;

    /**
     * CustomerService constructor.
     * @param CustomerRepository $customerRepository
     */
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->customerRepository->itemsPerPage = 10;
    }

    /**
     * Get All Customer models as collection
     * 
     * @param array $filters
     * @param array $eagerRelations
     * @return Builder[]|Collection
     * @throws Exception
     */
    public function getAllCustomers(array $filters = [], array $eagerRelations = [])
    {
        return $this->customerRepository->getWith($filters, $eagerRelations, true);
    }

    /**
     * Create Customer Model Pagination
     * 
     * @param array $filters
     * @param array $eagerRelations
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function customerPaginate(array $filters = [], array $eagerRelations = []): LengthAwarePaginator
    {
        return $this->customerRepository->paginateWith($filters, $eagerRelations, true);
    }

    /**
     * Show Customer Model
     * 
     * @param int $id
     * @param bool $purge
     * @return mixed
     * @throws Exception
     */
    public function getCustomerById($id, bool $purge = false)
    {
        return $this->customerRepository->show($id, $purge);
    }

    /**
     * Save Customer Model
     * 
     * @param array $inputs
     * @return array
     * @throws Exception
     * @throws Throwable
     */
    public function storeCustomer(array $inputs): array
    {
        DB::beginTransaction();
        try {
            $newCustomer = $this->customerRepository->create($inputs);
            if ($newCustomer instanceof Customer) {
                DB::commit();
                return ['status' => true, 'message' => __('New Customer Created'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('New Customer Creation Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->customerRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Update Customer Model
     * 
     * @param array $inputs
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function updateCustomer(array $inputs, $id): array
    {
        DB::beginTransaction();
        try {
            $customer = $this->customerRepository->show($id);
            if ($customer instanceof Customer) {
                if ($this->customerRepository->update($inputs, $id)) {
                    DB::commit();
                    return ['status' => true, 'message' => __('Customer Info Updated'),
                        'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
                } else {
                    DB::rollBack();
                    return ['status' => false, 'message' => __('Customer Info Update Failed'),
                        'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
                }
            } else {
                return ['status' => false, 'message' => __('Customer Model Not Found'),
                    'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->customerRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Destroy Customer Model
     * 
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function destroyCustomer($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->customerRepository->delete($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('Customer is Trashed'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('Customer is Delete Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->customerRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Restore Customer Model
     * 
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function restoreCustomer($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->customerRepository->restore($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('Customer is Restored'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('Customer is Restoration Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->customerRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Export Object for Export Download
     *
     * @param array $filters
     * @return CustomerExport
     * @throws Exception
     */
    public function exportCustomer(array $filters = []): CustomerExport
    {
        return (new CustomerExport($this->customerRepository->getWith($filters)));
    }
}
