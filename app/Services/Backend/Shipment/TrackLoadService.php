<?php

namespace App\Services\Backend\Shipment;

use App\Abstracts\Service\Service;
use App\Models\Backend\Shipment\TrackLoad;
use App\Repositories\Eloquent\Backend\Shipment\TrackLoadRepository;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Core\Supports\Constant;
use Throwable;

/**
 * @class TrackLoadService
 * @package App\Services\Backend\Shipment
 */
class TrackLoadService extends Service
{
/**
     * @var TrackLoadRepository
     */
    private $trackloadRepository;

    /**
     * TrackLoadService constructor.
     * @param TrackLoadRepository $trackloadRepository
     */
    public function __construct(TrackLoadRepository $trackloadRepository)
    {
        $this->trackloadRepository = $trackloadRepository;
        $this->trackloadRepository->itemsPerPage = 10;
    }

    /**
     * Get All TrackLoad models as collection
     * 
     * @param array $filters
     * @param array $eagerRelations
     * @return Builder[]|Collection
     * @throws Exception
     */
    public function getAllTrackLoads(array $filters = [], array $eagerRelations = [])
    {
        return $this->trackloadRepository->getWith($filters, $eagerRelations, true);
    }

    /**
     * Create TrackLoad Model Pagination
     * 
     * @param array $filters
     * @param array $eagerRelations
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function trackloadPaginate(array $filters = [], array $eagerRelations = []): LengthAwarePaginator
    {
        return $this->trackloadRepository->paginateWith($filters, $eagerRelations, true);
    }

    /**
     * Show TrackLoad Model
     * 
     * @param int $id
     * @param bool $purge
     * @return mixed
     * @throws Exception
     */
    public function getTrackLoadById($id, bool $purge = false)
    {
        return $this->trackloadRepository->show($id, $purge);
    }

    /**
     * Save TrackLoad Model
     * 
     * @param array $inputs
     * @return array
     * @throws Exception
     * @throws Throwable
     */
    public function storeTrackLoad(array $inputs): array
    {
        DB::beginTransaction();
        try {
            $newTrackLoad = $this->trackloadRepository->create($inputs);
            if ($newTrackLoad instanceof TrackLoad) {
                DB::commit();
                return ['status' => true, 'message' => __('New TrackLoad Created'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('New TrackLoad Creation Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->trackloadRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Update TrackLoad Model
     * 
     * @param array $inputs
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function updateTrackLoad(array $inputs, $id): array
    {
        DB::beginTransaction();
        try {
            $trackload = $this->trackloadRepository->show($id);
            if ($trackload instanceof TrackLoad) {
                if ($this->trackloadRepository->update($inputs, $id)) {
                    DB::commit();
                    return ['status' => true, 'message' => __('TrackLoad Info Updated'),
                        'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
                } else {
                    DB::rollBack();
                    return ['status' => false, 'message' => __('TrackLoad Info Update Failed'),
                        'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
                }
            } else {
                return ['status' => false, 'message' => __('TrackLoad Model Not Found'),
                    'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->trackloadRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Destroy TrackLoad Model
     * 
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function destroyTrackLoad($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->trackloadRepository->delete($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('TrackLoad is Trashed'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('TrackLoad is Delete Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->trackloadRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Restore TrackLoad Model
     * 
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function restoreTrackLoad($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->trackloadRepository->restore($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('TrackLoad is Restored'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('TrackLoad is Restoration Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->trackloadRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Export Object for Export Download
     *
     * @param array $filters
     * @return TrackLoadExport
     * @throws Exception
     */
    public function exportTrackLoad(array $filters = []): TrackLoadExport
    {
        return (new TrackLoadExport($this->trackloadRepository->getWith($filters)));
    }
}
