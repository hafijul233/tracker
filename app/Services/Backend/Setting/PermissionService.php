<?php

namespace App\Services\Backend\Setting;


use App\Abstracts\Service\Service;
use App\Exports\Backend\Setting\PermissionExport;
use App\Models\Backend\Setting\Permission;
use App\Supports\Constant;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Throwable;


class PermissionService extends Service
{
    /**
     * PermissionService constructor.
     */
    public function __construct()
    {
        $this->setModel(Permission::class);
    }

    /**
     * Get All Permission models as collection
     *
     * @param array $filters
     * @param array $eagerRelations
     * @return Builder[]|Collection
     * @throws Exception
     */
    public function getAllPermissions(array $filters = [], array $eagerRelations = [])
    {
        return $this->model
            ->applyFilter($filters)
            ->with($eagerRelations)
            ->get();
    }

    /**
     * Create Permission Model Pagination
     *
     * @param array $filters
     * @param array $eagerRelations
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function permissionPaginate(array $filters = [], array $eagerRelations = [])
    {
        return $this->model
            ->applyFilter($filters)
            ->with($eagerRelations)
            ->paginate();
    }

    /**
     * Show Permission Model
     *
     * @param int $id
     * @param bool $purge
     * @return mixed
     * @throws Exception
     */
    public function getPermissionById($id, bool $purge = false)
    {
        return $this->show($id, $purge);
    }

    /**
     * Save Permission Model
     *
     * @param array $inputs
     * @return array
     * @throws Exception
     * @throws Throwable
     */
    public function storePermission(array $inputs): array
    {
        \DB::beginTransaction();
        try {
            $newPermission = $this->create($inputs);

            if ($newPermission instanceof Permission) {
                \DB::commit();
                return ['status' => true, 'message' => __('New Permission Created'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
            } else {
                \DB::rollBack();
                return ['status' => false, 'message' => __('New Permission Creation Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->handleException($exception);
            \DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Update Permission Model
     *
     * @param array $inputs
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function updatePermission(array $inputs, $id): array
    {
        \DB::beginTransaction();
        try {
            $permission = $this->getPermissionById($id);
            if ($permission instanceof Permission) {
                if ($this->update($inputs, $id)) {
                    \DB::commit();
                    return ['status' => true, 'message' => __('Permission Info Updated'),
                        'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
                } else {
                    \DB::rollBack();
                    return ['status' => false, 'message' => __('Permission Info Update Failed'),
                        'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
                }
            } else {
                return ['status' => false, 'message' => __('Permission Model Not Found'),
                    'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->handleException($exception);
            \DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Destroy Permission Model
     *
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function destroyPermission($id): array
    {
        \DB::beginTransaction();
        try {
            if ($this->delete($id)) {
                \DB::commit();
                return ['status' => true, 'message' => __('Permission is Trashed'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                \DB::rollBack();
                return ['status' => false, 'message' => __('Permission is Delete Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->handleException($exception);
            \DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Restore Permission Model
     *
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function restorePermission($id): array
    {
        \DB::beginTransaction();
        try {
            if ($this->restore($id)) {
                \DB::commit();
                return ['status' => true, 'message' => __('Permission is Restored'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                \DB::rollBack();
                return ['status' => false, 'message' => __('Permission is Restoration Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (Exception $exception) {
            $this->handleException($exception);
            \DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * Export Object for Export Download
     *
     * @param array $filters
     * @return PermissionExport
     * @throws Exception
     */
    public function exportPermission(array $filters = []): PermissionExport
    {
        return (new PermissionExport($this->getAllPermissions($filters)));
    }
}
