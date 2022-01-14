<?php

namespace App\Services\Backend\Setting;

use App\Abstracts\Service\Service;
use App\Models\Backend\Setting\Cost;
use App\Repositories\Eloquent\Backend\Setting\CostRepository;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Core\Supports\Constant;
use Throwable;

/**
 * @class CostService
 * @package App\Services\Backend\Setting
 */
class CostService extends Service
{
/**
     * @var CostRepository
     */
    private $costRepository;

    /**
     * CostService constructor.
     * @param CostRepository $costRepository
     */
    public function __construct(CostRepository $costRepository)
    {
        $this->costRepository = $costRepository;
        $this->costRepository->itemsPerPage = 10;
    }

    /**
     * Get All Cost models as collection
     * 
     * @param array $filters
     * @param array $eagerRelations
     * @return Builder[]|Collection
     * @throws Exception
     */
    public function getAllCosts(array $filters = [], array $eagerRelations = [])
    {
        return $this->costRepository->getWith($filters, $eagerRelations, true);
    }

    /**
     * Create Cost Model Pagination
     * 
     * @param array $filters
     * @param array $eagerRelations
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function costPaginate(array $filters = [], array $eagerRelations = []): LengthAwarePaginator
    {
        return $this->costRepository->paginateWith($filters, $eagerRelations, true);
    }

    /**
     * Show Cost Model
     * 
     * @param int $id
     * @param bool $purge
     * @return mixed
     * @throws Exception
     */
    public function getCostById($id, bool $purge = false)
    {
        return $this->costRepository->show($id, $purge);
    }

    /**
     * Save Cost Model
     * 
     * @param array $inputs
     * @return array
     * @throws Exception
     * @throws Throwable
     */
    public function storeCost(array $inputs): array
    {
        DB::beginTransaction();
        try {
            $newCost = $this->costRepository->create($inputs);
            if ($newCost instanceof Cost) {
                DB::commit();
                return ['status' => true, 'message' => __('New Cost Created'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('New Cost Creation Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->costRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Update Cost Model
     * 
     * @param array $inputs
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function updateCost(array $inputs, $id): array
    {
        DB::beginTransaction();
        try {
            $cost = $this->costRepository->show($id);
            if ($cost instanceof Cost) {
                if ($this->costRepository->update($inputs, $id)) {
                    DB::commit();
                    return ['status' => true, 'message' => __('Cost Info Updated'),
                        'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
                } else {
                    DB::rollBack();
                    return ['status' => false, 'message' => __('Cost Info Update Failed'),
                        'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
                }
            } else {
                return ['status' => false, 'message' => __('Cost Model Not Found'),
                    'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->costRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Destroy Cost Model
     * 
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function destroyCost($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->costRepository->delete($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('Cost is Trashed'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('Cost is Delete Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->costRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Restore Cost Model
     * 
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function restoreCost($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->costRepository->restore($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('Cost is Restored'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('Cost is Restoration Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->costRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Export Object for Export Download
     *
     * @param array $filters
     * @return CostExport
     * @throws Exception
     */
    public function exportCost(array $filters = []): CostExport
    {
        return (new CostExport($this->costRepository->getWith($filters)));
    }
}
