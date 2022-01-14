<?php

namespace App\Services\Backend\Organization;

use App\Abstracts\Service\Service;
use App\Models\Backend\Organization\Employee;
use App\Repositories\Eloquent\Backend\Organization\EmployeeRepository;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Core\Supports\Constant;
use Throwable;

/**
 * @class EmployeeService
 * @package App\Services\Backend\Organization
 */
class EmployeeService extends Service
{
/**
     * @var EmployeeRepository
     */
    private $employeeRepository;

    /**
     * EmployeeService constructor.
     * @param EmployeeRepository $employeeRepository
     */
    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
        $this->employeeRepository->itemsPerPage = 10;
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
        return $this->employeeRepository->getWith($filters, $eagerRelations, true);
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
        return $this->employeeRepository->paginateWith($filters, $eagerRelations, true);
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
        return $this->employeeRepository->show($id, $purge);
    }

    /**
     * Save Employee Model
     * 
     * @param array $inputs
     * @return array
     * @throws Exception
     * @throws Throwable
     */
    public function storeEmployee(array $inputs): array
    {
        DB::beginTransaction();
        try {
            $newEmployee = $this->employeeRepository->create($inputs);
            if ($newEmployee instanceof Employee) {
                DB::commit();
                return ['status' => true, 'message' => __('New Employee Created'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('New Employee Creation Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->employeeRepository->handleException($exception);
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
     * @return array
     * @throws Throwable
     */
    public function updateEmployee(array $inputs, $id): array
    {
        DB::beginTransaction();
        try {
            $employee = $this->employeeRepository->show($id);
            if ($employee instanceof Employee) {
                if ($this->employeeRepository->update($inputs, $id)) {
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
            $this->employeeRepository->handleException($exception);
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
            if ($this->employeeRepository->delete($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('Employee is Trashed'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('Employee is Delete Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->employeeRepository->handleException($exception);
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
            if ($this->employeeRepository->restore($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('Employee is Restored'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('Employee is Restoration Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->employeeRepository->handleException($exception);
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
        return (new EmployeeExport($this->employeeRepository->getWith($filters)));
    }
}
