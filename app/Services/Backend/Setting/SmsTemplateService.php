<?php

namespace App\Services\Backend\Setting;

use App\Abstracts\Service\Service;
use App\Models\Backend\Setting\SmsTemplate;
use App\Repositories\Eloquent\Backend\Setting\SmsTemplateRepository;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Core\Supports\Constant;
use Throwable;

/**
 * @class SmsTemplateService
 * @package App\Services\Backend\Setting
 */
class SmsTemplateService extends Service
{
/**
     * @var SmsTemplateRepository
     */
    private $smstemplateRepository;

    /**
     * SmsTemplateService constructor.
     * @param SmsTemplateRepository $smstemplateRepository
     */
    public function __construct(SmsTemplateRepository $smstemplateRepository)
    {
        $this->smstemplateRepository = $smstemplateRepository;
        $this->smstemplateRepository->itemsPerPage = 10;
    }

    /**
     * Get All SmsTemplate models as collection
     * 
     * @param array $filters
     * @param array $eagerRelations
     * @return Builder[]|Collection
     * @throws Exception
     */
    public function getAllSmsTemplates(array $filters = [], array $eagerRelations = [])
    {
        return $this->smstemplateRepository->getWith($filters, $eagerRelations, true);
    }

    /**
     * Create SmsTemplate Model Pagination
     * 
     * @param array $filters
     * @param array $eagerRelations
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function smstemplatePaginate(array $filters = [], array $eagerRelations = []): LengthAwarePaginator
    {
        return $this->smstemplateRepository->paginateWith($filters, $eagerRelations, true);
    }

    /**
     * Show SmsTemplate Model
     * 
     * @param int $id
     * @param bool $purge
     * @return mixed
     * @throws Exception
     */
    public function getSmsTemplateById($id, bool $purge = false)
    {
        return $this->smstemplateRepository->show($id, $purge);
    }

    /**
     * Save SmsTemplate Model
     * 
     * @param array $inputs
     * @return array
     * @throws Exception
     * @throws Throwable
     */
    public function storeSmsTemplate(array $inputs): array
    {
        DB::beginTransaction();
        try {
            $newSmsTemplate = $this->smstemplateRepository->create($inputs);
            if ($newSmsTemplate instanceof SmsTemplate) {
                DB::commit();
                return ['status' => true, 'message' => __('New SmsTemplate Created'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('New SmsTemplate Creation Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->smstemplateRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Update SmsTemplate Model
     * 
     * @param array $inputs
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function updateSmsTemplate(array $inputs, $id): array
    {
        DB::beginTransaction();
        try {
            $smstemplate = $this->smstemplateRepository->show($id);
            if ($smstemplate instanceof SmsTemplate) {
                if ($this->smstemplateRepository->update($inputs, $id)) {
                    DB::commit();
                    return ['status' => true, 'message' => __('SmsTemplate Info Updated'),
                        'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
                } else {
                    DB::rollBack();
                    return ['status' => false, 'message' => __('SmsTemplate Info Update Failed'),
                        'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
                }
            } else {
                return ['status' => false, 'message' => __('SmsTemplate Model Not Found'),
                    'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->smstemplateRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Destroy SmsTemplate Model
     * 
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function destroySmsTemplate($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->smstemplateRepository->delete($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('SmsTemplate is Trashed'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('SmsTemplate is Delete Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->smstemplateRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Restore SmsTemplate Model
     * 
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function restoreSmsTemplate($id): array
    {
        DB::beginTransaction();
        try {
            if ($this->smstemplateRepository->restore($id)) {
                DB::commit();
                return ['status' => true, 'message' => __('SmsTemplate is Restored'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                DB::rollBack();
                return ['status' => false, 'message' => __('SmsTemplate is Restoration Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->smstemplateRepository->handleException($exception);
            DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Export Object for Export Download
     *
     * @param array $filters
     * @return SmsTemplateExport
     * @throws Exception
     */
    public function exportSmsTemplate(array $filters = []): SmsTemplateExport
    {
        return (new SmsTemplateExport($this->smstemplateRepository->getWith($filters)));
    }
}
