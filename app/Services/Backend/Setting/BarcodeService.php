<?php

namespace App\Services\Backend\Setting;

use App\Abstracts\Service\Service;
use App\Models\Backend\Setting\Barcode;
use App\Repositories\Eloquent\Backend\Setting\BarcodeRepository;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Core\Supports\Constant;
use Throwable;

/**
 * @class BarcodeService
 * @package App\Services\Backend\Setting
 */
class BarcodeService extends Service
{
/**
     * @var BarcodeRepository
     */
    private $barcodeRepository;

    /**
     * BarcodeService constructor.
     * @param BarcodeRepository $barcodeRepository
     */
    public function __construct(BarcodeRepository $barcodeRepository)
    {
        $this->barcodeRepository = $barcodeRepository;
        $this->barcodeRepository->itemsPerPage = 10;
    }

    /**
     * Get All Barcode models as collection
     * 
     * @param array $filters
     * @param array $eagerRelations
     * @return Builder[]|Collection
     * @throws Exception
     */
    public function getAllBarcodes(array $filters = [], array $eagerRelations = [])
    {
        return $this->barcodeRepository->getWith($filters, $eagerRelations, true);
    }

    /**
     * Create Barcode Model Pagination
     * 
     * @param array $filters
     * @param array $eagerRelations
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function barcodePaginate(array $filters = [], array $eagerRelations = []): LengthAwarePaginator
    {
        return $this->barcodeRepository->paginateWith($filters, $eagerRelations, true);
    }

    /**
     * Show Barcode Model
     * 
     * @param int $id
     * @param bool $purge
     * @return mixed
     * @throws Exception
     */
    public function getBarcodeById($id, bool $purge = false)
    {
        return $this->barcodeRepository->show($id, $purge);
    }

    /**
     * Save Barcode Model
     * 
     * @param array $inputs
     * @return array
     * @throws Exception
     * @throws Throwable
     */
    public function storeBarcode(array $inputs): array
    {
        DB::beginTransaction();
        try {
            $newBarcode = $this->barcodeRepository->create($inputs);
            if ($newBarcode instanceof Barcode) {
                DB::commit();
                return ['status' => true, 'message' => __('New Barcode Created'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('New Barcode Creation Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->barcodeRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Update Barcode Model
     * 
     * @param array $inputs
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function updateBarcode(array $inputs, $id): array
    {
        DB::beginTransaction();
        try {
            $barcode = $this->barcodeRepository->show($id);
            if ($barcode instanceof Barcode) {
                if ($this->barcodeRepository->update($inputs, $id)) {
                    DB::commit();
                    return ['status' => true, 'message' => __('Barcode Info Updated'),
                        'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
                } else {
                    DB::rollBack();
                    return ['status' => false, 'message' => __('Barcode Info Update Failed'),
                        'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
                }
            } else {
                return ['status' => false, 'message' => __('Barcode Model Not Found'),
                    'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->barcodeRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Destroy Barcode Model
     * 
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function destroyBarcode($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->barcodeRepository->delete($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('Barcode is Trashed'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('Barcode is Delete Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->barcodeRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Restore Barcode Model
     * 
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function restoreBarcode($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->barcodeRepository->restore($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('Barcode is Restored'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('Barcode is Restoration Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->barcodeRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Export Object for Export Download
     *
     * @param array $filters
     * @return BarcodeExport
     * @throws Exception
     */
    public function exportBarcode(array $filters = []): BarcodeExport
    {
        return (new BarcodeExport($this->barcodeRepository->getWith($filters)));
    }
}
