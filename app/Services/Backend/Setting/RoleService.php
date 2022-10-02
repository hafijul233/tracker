<?php


namespace App\Services\Backend\Setting;


use App\Abstracts\Service\Service;
use App\Exports\Backend\Setting\RoleExport;
use App\Models\Backend\Setting\Role;
use App\Services\Auth\AuthenticatedSessionService;
use App\Supports\Constant;
use Box\Spout\Common\Exception\InvalidArgumentException;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\PermissionRegistrar;
use Throwable;
use function __;
use function app;

class RoleService extends Service
{
    /**
     * PermissionService constructor.
     */
    public function __construct()
    {
        $this->setModel(Role::class);
    }


    /**
     * @param array $filters
     * @param array $eagerRelations
     * @return Builder[]|Collection
     * @throws \Exception
     */
    public function getAllRoles(array $filters = [], array $eagerRelations = [])
    {
        return $this->model
            ->applyFilter($filters)
            ->with($eagerRelations)
            ->get();
    }

    /**
     * @param array $filters
     * @param array $eagerRelations
     * @return mixed
     * @throws \Exception
     */
    public function rolePaginate(array $filters = [], array $eagerRelations = [])
    {
        return $this->model
            ->applyFilter($filters)
            ->with($eagerRelations)
            ->paginate();
    }

    /**
     * @param int $id
     * @param bool $purge
     * @return mixed
     * @throws \Exception
     */
    public function getRoleById(int $id, bool $purge = false)
    {
        if ($purge == false) {
            $purge = AuthenticatedSessionService::isSuperAdmin();
        }

        return $this->show($id, $purge);
    }

    /**
     * @param array $inputs
     * @return array
     * @throws \Exception|\Throwable
     */
    public function storeRole(array $inputs): array
    {
        \DB::beginTransaction();

        try {
            $newRole = $this->create($inputs);
            if ($newRole instanceof Role) {
                \DB::commit();
                return ['status' => true, 'message' => __('New Role Created'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
            } else {
                \DB::rollBack();
                return ['status' => false, 'message' => __('New Role Creation Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (\Exception $exception) {
            $this->handleException($exception);
            \DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * @param array $inputs
     * @param $id
     * @return array
     * @throws \Throwable
     */
    public function updateRole(array $inputs, $id): array
    {
        \DB::beginTransaction();
        try {
            if ($this->update($inputs, $id)) {
                \DB::commit();
                return ['status' => true, 'message' => __('Role Info Updated'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
            } else {
                \DB::rollBack();
                return ['status' => false, 'message' => __('Role Info Update Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (\Exception $exception) {
            $this->handleException($exception);
            \DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }


    /**
     * @param array $filters
     * @return array
     * @throws Exception
     */
    public function roleDropdown(array $filters = []): array
    {
        $roleCollection = $this->getAllRoles($filters);
        $roles = [];
        foreach ($roleCollection as $role) {
            $roles[$role->id] = $role['name'];
        }

        return $roles;
    }

    /**
     * @param $id
     * @return array
     * @throws \Throwable
     */
    public function destroyRole($id): array
    {
        \DB::beginTransaction();
        try {
            if ($this->detachPermissions([], $id)
                && $this->delete($id)) {
                \DB::commit();
                return ['status' => true, 'message' => __('Role is Trashed'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
            } else {
                \DB::rollBack();
                return ['status' => false, 'message' => __('Role is Delete Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (\Exception $exception) {
            $this->handleException($exception);
            \DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    public function syncPermission($id, array $permissions = []): array
    {
        \DB::beginTransaction();
        try {
            $role = $this->getRoleById($id);
            if ($role instanceof Role) {
                if ($role->permissions()->sync($permissions)) {
                    \DB::commit();

                    //Update Permission Cache for Roles
                    $this->clearPermissionCache();

                    return ['status' => true, 'message' => __('Role Permissions Updated'),
                        'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];
                } else {
                    \DB::rollBack();
                    return ['status' => false, 'message' => __('Role Permissions Update Failed'),
                        'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
                }
            }
        } catch (\Exception $exception) {
            $this->handleException($exception);
            \DB::rollBack();
            return ['status' => false, 'message' => $exception->getMessage(),
                'level' => Constant::MSG_TOASTR_WARNING, 'title' => 'Error!'];
        }
    }

    /**
     * @return mixed
     */
    protected function clearPermissionCache()
    {
        return app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * @param $id
     * @return array
     * @throws Throwable
     */
    public function restoreRole($id): array
    {
        \DB::beginTransaction();
        try {
            if ($this->restore($id)) {
                \DB::commit();
                return ['status' => true, 'message' => __('Role is Restored'),
                    'level' => Constant::MSG_TOASTR_SUCCESS, 'title' => 'Notification!'];

            } else {
                \DB::rollBack();
                return ['status' => false, 'message' => __('Role is Restoration Failed'),
                    'level' => Constant::MSG_TOASTR_ERROR, 'title' => 'Alert!'];
            }
        } catch (\Exception $exception) {
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
     * @return RoleExport
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function exportRole(array $filters = []): RoleExport
    {
        return (new RoleExport($this->getAllRoles($filters)));
    }

    /**
     * @param array $permissions
     * @param $id
     * @return bool
     */
    public function attachPermissions(array $permissions, $id): bool
    {
        try {
            /**
             * @var Role $role
             */
            $role = $this->show($id);
            $role->permissions()->attach($permissions);
            return true;
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            return false;
        }
    }

    /**
     * @param array $permissions
     * @param $id
     * @return bool
     */
    public function detachPermissions(array $permissions, $id): bool
    {
        try {
            /**
             * @var Role $role
             */
            $role = $this->show($id);
            $existingPermissionIds = $role->permissions()->pluck('id');

            //Remove All
            if (empty($existingPermissionIds))
                $role->permissions()->detach($existingPermissionIds);

            //Remove Selected
            else
                $role->permissions()->detach($permissions);

            return true;
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            return false;
        }
    }
}
