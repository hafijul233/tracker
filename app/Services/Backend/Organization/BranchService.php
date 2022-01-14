<?php

namespace App\Services\Backend\Organization;

use App\Abstracts\Service\Service;
use App\Models\Backend\Organization\Branch;
use App\Repositories\Eloquent\Backend\Organization\BranchRepository;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Core\Supports\Constant;
use Throwable;

/**
 * @class BranchService
 * @package App\Services\Backend\Organization
 */
class BranchService extends Service
{
/**
     * @var BranchRepository
     */
    private $branchRepository;

    /**
     * BranchService constructor.
     * @param BranchRepository $branchRepository
     */
    public function __construct(BranchRepository $branchRepository)
    {
        $this->branchRepository = $branchRepository;
        $this->branchRepository->itemsPerPage = 10;
    }

    /**
     * Get All Branch models as collection
     * 
     * @param array $filters
     * @param array $eagerRelations
     * @return Builder[]|Collection
     * @throws Exception
     */
    public function getAllBranchs(array $filters = [], array $eagerRelations = [])
    {
        return $this->branchRepository->getWith($filters, $eagerRelations, true);
    }

    /**
     * Create Branch Model Pagination
     * 
     * @param array $filters
     * @param array $eagerRelations
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function branchPaginate(array $filters = [], array $eagerRelations = []): LengthAwarePaginator
    {
        return $this->branchRepository->paginateWith($filters, $eagerRelations, true);
    }

    /**
     * Show Branch Model
     * 
     * @param int $id
     * @param bool $purge
     * @return mixed
     * @throws Exception
     */
    public function getBranchById($id, bool $purge = false)
    {
        return $this->branchRepository->show($id, $purge);
    }

    /**
     * Save Branch Model
     * 
     * @param array $inputs
     * @return array
     * @throws Exception
     * @throws Throwable
     */
    public function storeBranch(array $inputs): array
    {
        DB::beginTransaction();
        try {
            $newBranch = $this->branchRepository->create($inputs);
            if ($newBranch instanceof Branch) {
                DB::commit();
                return ['status' => true, 'message' => __('New Branch Created'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('New Branch Creation Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->branchRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Update Branch Model
     * 
     * @param array $inputs
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function updateBranch(array $inputs, $id): array
    {
        DB::beginTransaction();
        try {
            $branch = $this->branchRepository->show($id);
            if ($branch instanceof Branch) {
                if ($this->branchRepository->update($inputs, $id)) {
                    DB::commit();
                    return ['status' => true, 'message' => __('Branch Info Updated'),
                        'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
                } else {
                    DB::rollBack();
                    return ['status' => false, 'message' => __('Branch Info Update Failed'),
                        'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
                }
            } else {
                return ['status' => false, 'message' => __('Branch Model Not Found'),
                    'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->branchRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Destroy Branch Model
     * 
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function destroyBranch($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->branchRepository->delete($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('Branch is Trashed'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('Branch is Delete Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->branchRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Restore Branch Model
     * 
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function restoreBranch($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->branchRepository->restore($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('Branch is Restored'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('Branch is Restoration Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->branchRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Export Object for Export Download
     *
     * @param array $filters
     * @return BranchExport
     * @throws Exception
     */
    public function exportBranch(array $filters = []): BranchExport
    {
        return (new BranchExport($this->branchRepository->getWith($filters)));
    }
}
