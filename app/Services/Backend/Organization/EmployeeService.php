<?php

namespace App\Services\Backend\Organization;

use App\Abstracts\Service\Service;
use App\Exports\Backend\Organization\EmployeeExport;
use App\Models\Backend\Setting\User;
use App\Services\Backend\Setting\UserService;
use App\Supports\Constant;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * @class EmployeeService
 * @package App\Services\Backend\Organization
 */
class EmployeeService extends Service
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * EmployeeService constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Get All Employee models as collection
     *
     * @param array $filters
     * @param array $eagerRelations
     * @return Builder[]|Collection
     * @throws Exception
     */
    public function getAllEmployees(array $filters = [], array $eagerRelations = [])
    {
        return $this->userService
            ->getAllUsers($filters)
            ->with($eagerRelations)
            ->get();
    }

    /**
     * Create Employee Model Pagination
     *
     * @param array $filters
     * @param array $eagerRelations
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function employeePaginate(array $filters = [], array $eagerRelations = []): LengthAwarePaginator
    {
        return $this->userService
            ->getAllUsers($filters)
            ->with($eagerRelations)
            ->paginate();
    }

    /**
     * Show Employee Model
     *
     * @param int $id
     * @param bool $purge
     * @return mixed
     * @throws Exception
     */
    public function getEmployeeById($id, bool $purge = false)
    {
        return $this->userService->getUserById($id, $purge);
    }

    /**
     * Save Employee Model
     *
     * @param array $inputs
     * @param UploadedFile|null $photo
     * @return array
     * @throws Exception
     */
    public function storeEmployee(array $inputs, UploadedFile $photo = null): array
    {
        DB::beginTransaction();
        try {
            $newEmployee = $this->userService->storeUser($inputs, $photo);
            if ($newEmployee instanceof User) {
                DB::commit();
                return ['status' => true, 'message' => __('New Employee Created'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('New Employee Creation Failed'),
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
     * Update Employee Model
     *
     * @param array $inputs
     * @param $id
     * @param UploadedFile|null $photo
     * @return array
     * @throws Exception
     */
    public function updateEmployee(array $inputs, $id, UploadedFile $photo = null): array
    {
        DB::beginTransaction();
        try {
            $employee = $this->userService->getUserById($id);
            if ($employee instanceof User) {
                if ($this->userService->updateUser($inputs, $id, $photo)) {
                    DB::commit();
                    return ['status' => true, 'message' => __('Employee Info Updated'),
                        'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
                } else {
                    DB::rollBack();
                    return ['status' => false, 'message' => __('Employee Info Update Failed'),
                        'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
                }
            } else {
                return ['status' => false, 'message' => __('Employee Model Not Found'),
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
     * Destroy Employee Model
     *
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function destroyEmployee($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->userService->destroyUser($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('Employee is Trashed'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('Employee is Delete Failed'),
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
     * Restore Employee Model
     *
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function restoreEmployee($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->userService->restoreUser($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('Employee is Restored'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('Employee is Restoration Failed'),
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
     * @return EmployeeExport
     * @throws Exception
     */
    public function exportEmployee(array $filters = []): EmployeeExport
    {
        return (new EmployeeExport($this->getAllEmployees($filters)));
    }
}
