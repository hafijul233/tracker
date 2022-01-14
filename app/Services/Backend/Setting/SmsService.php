<?php

namespace App\Services\Backend\Setting;

use App\Abstracts\Service\Service;
use App\Models\Backend\Setting\Sms;
use App\Repositories\Eloquent\Backend\Setting\SmsRepository;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Core\Supports\Constant;
use Throwable;

/**
 * @class SmsService
 * @package App\Services\Backend\Setting
 */
class SmsService extends Service
{
/**
     * @var SmsRepository
     */
    private $smsRepository;

    /**
     * SmsService constructor.
     * @param SmsRepository $smsRepository
     */
    public function __construct(SmsRepository $smsRepository)
    {
        $this->smsRepository = $smsRepository;
        $this->smsRepository->itemsPerPage = 10;
    }

    /**
     * Get All Sms models as collection
     * 
     * @param array $filters
     * @param array $eagerRelations
     * @return Builder[]|Collection
     * @throws Exception
     */
    public function getAllSmss(array $filters = [], array $eagerRelations = [])
    {
        return $this->smsRepository->getWith($filters, $eagerRelations, true);
    }

    /**
     * Create Sms Model Pagination
     * 
     * @param array $filters
     * @param array $eagerRelations
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function smsPaginate(array $filters = [], array $eagerRelations = []): LengthAwarePaginator
    {
        return $this->smsRepository->paginateWith($filters, $eagerRelations, true);
    }

    /**
     * Show Sms Model
     * 
     * @param int $id
     * @param bool $purge
     * @return mixed
     * @throws Exception
     */
    public function getSmsById($id, bool $purge = false)
    {
        return $this->smsRepository->show($id, $purge);
    }

    /**
     * Save Sms Model
     * 
     * @param array $inputs
     * @return array
     * @throws Exception
     * @throws Throwable
     */
    public function storeSms(array $inputs): array
    {
        DB::beginTransaction();
        try {
            $newSms = $this->smsRepository->create($inputs);
            if ($newSms instanceof Sms) {
                DB::commit();
                return ['status' => true, 'message' => __('New Sms Created'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('New Sms Creation Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->smsRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Update Sms Model
     * 
     * @param array $inputs
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function updateSms(array $inputs, $id): array
    {
        DB::beginTransaction();
        try {
            $sms = $this->smsRepository->show($id);
            if ($sms instanceof Sms) {
                if ($this->smsRepository->update($inputs, $id)) {
                    DB::commit();
                    return ['status' => true, 'message' => __('Sms Info Updated'),
                        'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
                } else {
                    DB::rollBack();
                    return ['status' => false, 'message' => __('Sms Info Update Failed'),
                        'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
                }
            } else {
                return ['status' => false, 'message' => __('Sms Model Not Found'),
                    'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->smsRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Destroy Sms Model
     * 
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function destroySms($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->smsRepository->delete($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('Sms is Trashed'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('Sms is Delete Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->smsRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Restore Sms Model
     * 
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function restoreSms($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->smsRepository->restore($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('Sms is Restored'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('Sms is Restoration Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->smsRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Export Object for Export Download
     *
     * @param array $filters
     * @return SmsExport
     * @throws Exception
     */
    public function exportSms(array $filters = []): SmsExport
    {
        return (new SmsExport($this->smsRepository->getWith($filters)));
    }
}
