<?php

namespace App\Services\Backend\Shipment;

use App\Abstracts\Service\Service;
use App\Exports\Backend\Shipment\CustomerExport;
use App\Models\Backend\Setting\User;
use App\Services\Auth\AuthenticatedSessionService;
use App\Services\Backend\Common\AddressBookService;
use App\Services\Backend\Setting\UserService;
use App\Supports\Constant;
use App\Supports\Utility;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * @class CustomerService
 * @package App\Services\Backend\Shipment
 */
class CustomerService extends Service
{
    /**
     * @var UserService
     */
    private $userService;
    /**
     * @var AddressBookService
     */
    private $addressBookService;

    /**
     * CustomerService constructor.
     *
     * @param UserService $userService
     * @param AddressBookService $addressBookService
     */
    public function __construct(
        UserService $userService,
        AddressBookService $addressBookService)
    {
        $this->userService = $userService;
        $this->addressBookService = $addressBookService;
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
        $filters['role'] = [Constant::SENDER_ROLE_ID, Constant::RECEIVER_ROLE_ID];

        if (!AuthenticatedSessionService::isSuperAdmin()) :
            $filters['parent_id'] = Auth::user()->id;
        endif;

        return $this->userService->getAllUsers($filters, $eagerRelations);
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
        $filters['role'] = [Constant::SENDER_ROLE_ID, Constant::RECEIVER_ROLE_ID];

        if (!AuthenticatedSessionService::isSuperAdmin()) :
            $filters['parent_id'] = Auth::user()->id;
        endif;

        return $this->userService->userPaginate($filters, $eagerRelations);
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
        return $this->userService->show($id, $purge);
    }

    /**
     * Save Customer Model
     *
     * @param array $requestData
     * @param UploadedFile|null $photo
     * @return array
     * @throws Exception
     */
    public function storeCustomer(array $requestData, UploadedFile $photo = null): array
    {
        $requestData['role_id'] = $requestData['role_id'] ?? [Constant::SENDER_ROLE_ID];

        //hash user password
        $requestData['password'] = Utility::hashPassword(($requestData['password'] ?? Constant::PASSWORD));

        //force password reset
        $requestData['force_pass_reset'] = true;

        DB::beginTransaction();
        try {
            if ($newCustomer = $this->userService->storeUser($requestData, $photo)) {
                if (($newCustomer instanceof User) &&
                    $this->storeCustomerAddress($newCustomer, $requestData)) {
                    DB::commit();
                    return ['status' => true, 'message' => __('New Customer Created'),
                        'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
                }

                DB::rollBack();
                return ['status' => false, 'message' => __('Role or Avatar image Failed'),
                    'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Warning!'];
            }

            DB::rollBack();
            return ['status' => false, 'message' => __('New Customer Creation Failed'),
                'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
        } catch (Exception $exception) {
            DB::rollBack();
            $this->handleException($exception);
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }


    /**
     * @param User $customer
     * @param array $requestData
     * @return bool
     * @throws Exception
     */
    public function storeCustomerAddress(User $customer, array $requestData): bool
    {
        foreach ($requestData['address']['type'] as $type):
            try {
                $address = [
                    'user_id' => $customer->id,
                    'type' => $type,
                    'phone' => $requestData['address']['phone'] ?? null,
                    'name' => $requestData['name'] ?? null,
                    'address' => $requestData['address']['address'] ?? null,
                    'post_code' => $requestData['address']['post_code'] ?? null,
                    'remark' => $requestData['remarks'] ?? null,
                    'enabled' => Constant::ENABLED_OPTION,
                    'city_id' => $requestData['address']['city_id'] ?? config('contact.default.city'),
                    'state_id' => $requestData['address']['state_id'] ?? config('contact.default.state'),
                    'country_id' => $requestData['address']['country_id'] ?? config('contact.default.country')
                ];

                if (!$this->addressBookService->storeAddressBook($address)):
                    return false;
                endif;

            } catch (Exception $exception) {
                $this->handleException($exception);
                return false;
            }
        endforeach;

        return true;
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
            $user = $this->getCustomerById($id);
            if ($user instanceof User) {
                if ($this->userService->updateUser($inputs, $id)) {
                    DB::commit();
                    return ['status' => true, 'message' => __('Customer Info Updated'),
                        'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
                }

                DB::rollBack();
                return ['status' => false, 'message' => __('Customer Info Update Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }

            return ['status' => false, 'message' => __('Customer Model Not Found'),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Alert!'];

        } catch (Exception $exception) {
            $this->handleException($exception);
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
            if ($this->userService->destroyUser($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('Customer is Trashed'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            }

            DB::rollBack();
            return ['status' => false, 'message' => __('Customer is Delete Failed'),
                'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];

        } catch (Exception $exception) {
            $this->handleException($exception);
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
            if ($this->userService->restoreUser($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('Customer is Restored'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            }

            DB::rollBack();
            return ['status' => false, 'message' => __('Customer is Restoration Failed'),
                'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];

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
     * @return CustomerExport
     * @throws Exception
     */
    public function exportCustomer(array $filters = []): CustomerExport
    {
        return (new CustomerExport($this->userService->getAllUsers($filters)));
    }
}
