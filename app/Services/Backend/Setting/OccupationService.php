<?php

namespace App\Services\Backend\Setting;

use App\Abstracts\Service\Service;
use App\Exports\Backend\Setting\OccupationExport;
use App\Models\Backend\Setting\Occupation;
use App\Repositories\Eloquent\Backend\Setting\OccupationRepository;
use App\Supports\Constant;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * @class OccupationService
 * @package App\Services\Setting
 */
class OccupationService extends Service
{
/**
     * @var OccupationRepository
     */
    private $occupationRepository;

    /**
     * OccupationService constructor.
     * @param OccupationRepository $occupationRepository
     */
    public function __construct(OccupationRepository $occupationRepository)
    {
        $this->occupationRepository = $occupationRepository;
        $this->occupationRepository->itemsPerPage = 10;
    }

    /**
     * Get All Occupation models as collection
     * 
     * @param array $filters
     * @param array $eagerRelations
     * @return Builder[]|Collection
     * @throws Exception
     */
    public function getAllOccupations(array $filters = [], array $eagerRelations = [])
    {
        return $this->occupationRepository->getWith($filters, $eagerRelations, true);
    }

    /**
     * Create Occupation Model Pagination
     * 
     * @param array $filters
     * @param array $eagerRelations
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function occupationPaginate(array $filters = [], array $eagerRelations = []): LengthAwarePaginator
    {
        return $this->occupationRepository->paginateWith($filters, $eagerRelations, true);
    }

    /**
     * Show Occupation Model
     * 
     * @param int $id
     * @param bool $purge
     * @return mixed
     * @throws Exception
     */
    public function getOccupationById($id, bool $purge = false)
    {
        return $this->occupationRepository->show($id, $purge);
    }

    /**
     * Save Occupation Model
     * 
     * @param array $inputs
     * @return array
     * @throws Exception
     * @throws Throwable
     */
    public function storeOccupation(array $inputs): array
    {
        DB::beginTransaction();
        try {
            $newOccupation = $this->occupationRepository->create($inputs);
            if ($newOccupation instanceof Occupation) {
                DB::commit();
                return ['status' => true, 'message' => __('New Occupation Created'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('New Occupation Creation Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->occupationRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Update Occupation Model
     * 
     * @param array $inputs
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function updateOccupation(array $inputs, $id): array
    {
        DB::beginTransaction();
        try {
            $occupation = $this->occupationRepository->show($id);
            if ($occupation instanceof Occupation) {
                if ($this->occupationRepository->update($inputs, $id)) {
                    DB::commit();
                    return ['status' => true, 'message' => __('Occupation Info Updated'),
                        'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
                } else {
                    DB::rollBack();
                    return ['status' => false, 'message' => __('Occupation Info Update Failed'),
                        'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
                }
            } else {
                return ['status' => false, 'message' => __('Occupation Model Not Found'),
                    'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->occupationRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Destroy Occupation Model
     * 
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function destroyOccupation($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->occupationRepository->delete($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('Occupation is Trashed'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('Occupation is Delete Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->occupationRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Restore Occupation Model
     * 
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function restoreOccupation($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->occupationRepository->restore($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('Occupation is Restored'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('Occupation is Restoration Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->occupationRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Export Object for Export Download
     *
     * @param array $filters
     * @return OccupationExport
     * @throws Exception
     */
    public function exportOccupation(array $filters = []): OccupationExport
    {
        return (new OccupationExport($this->occupationRepository->getWith($filters)));
    }
}
