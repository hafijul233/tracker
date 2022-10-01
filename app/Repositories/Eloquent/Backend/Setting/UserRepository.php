<?php


namespace App\Repositories\Eloquent\Backend\Setting;


use App\Abstracts\Repository\EloquentRepository;
use App\Models\Backend\Setting\User;
use App\Services\Auth\AuthenticatedSessionService;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;


class UserRepository extends EloquentRepository
{
    /**
     * UserRepository constructor.
     */
    public function __construct($model = null)
    {
        /**
         * Set the model that will be used for repo
         */
        $model = $model ?? new User();

        parent::__construct($model);
    }

    /**
     * Search Function for Permissions
     *
     * @param array $filters
     * @param bool $is_sortable
     * @return Builder
     */
    private function filterData(array $filters = [], bool $is_sortable = false): Builder
    {
        $query = $this->getQueryBuilder();

        if (isset($filters['search']) && !empty($filters['search'])) :
            $query->where('name', 'like', "%{$filters['search']}%")
                ->orWhere('username', 'like', "%{$filters['search']}%")
                ->orWhere('email', '=', "%{$filters['search']}%")
                ->orWhere('mobile', '=', "%{$filters['search']}%")
                ->orWhere('enabled', '=', "%{$filters['search']}%");
        endif;

        if (isset($filters['enabled']) && !empty($filters['enabled'])) :
            $query->where('enabled', '=', $filters['enabled']);
        endif;

        if (isset($filters['parent_id']) && !empty($filters['parent_id'])) :
            $query->where('parent_id', '=', $filters['parent_id']);
        endif;

        if (isset($filters['sort']) && !empty($filters['direction'])) :
            $query->orderBy($filters['sort'], $filters['direction']);
        endif;

        //Role may be int, string, array
        if (isset($filters['role']) && !empty($filters['role'])) :
            $query->whereHas('roles', function ($subQuery) use ($filters) {

                if (!is_array($filters['role'])):
                    $filters['role'][] = $filters['role'];
                endif;

                $firstRole = array_shift($filters['role']);
                $subQuery->where('id', '=', $firstRole);

                if (!empty($filters['role'])) :
                    foreach ($filters['role'] as $role):
                        $subQuery->orWhere('id', '=', $role);
                    endforeach;
                endif;
            });
        endif;


        if ($is_sortable == true) :
            $query->sortable();
        endif;

        if (AuthenticatedSessionService::isSuperAdmin()) :
            $query->withTrashed();
        endif;


        return $query;
    }

    /**
     * Pagination Generator
     * @param array $filters
     * @param array $eagerRelations
     * @param bool $is_sortable
     * @return LengthAwarePaginator
     * @throws Exception
     */
    public function paginateWith(array $filters = [], array $eagerRelations = [], bool $is_sortable = false): LengthAwarePaginator
    {
        $query = $this->getQueryBuilder();
        try {
            $query = $this->filterData($filters, $is_sortable);
        } catch (Exception $exception) {
            $this->handleException($exception);
        } finally {
            return $query->with($eagerRelations)->paginate($this->itemsPerPage);
        }
    }

    /**
     * @param array $filters
     * @param array $eagerRelations
     * @param bool $is_sortable
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     * @throws Exception
     */
    public function getWith(array $filters = [], array $eagerRelations = [], bool $is_sortable = false)
    {
        $query = $this->getQueryBuilder();
        try {
            $query = $this->filterData($filters, $is_sortable);
        } catch (Exception $exception) {
            $this->handleException($exception);
        } finally {
            return $query->with($eagerRelations)->get();
        }
    }


}
