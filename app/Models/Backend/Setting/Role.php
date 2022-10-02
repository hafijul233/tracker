<?php

namespace App\Models\Backend\Setting;

use App\Services\Auth\AuthenticatedSessionService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Traits\Conditionable;
use Kyslik\ColumnSortable\Sortable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole implements Auditable
{
    use AuditableTrait, HasFactory, SoftDeletes, Sortable;

    /**
     * The attributes that are mass assignable.
     * 'enabled' => to handle status,
     * ['created_by', 'updated_by', 'deleted_by'] => for audit
     *
     * @var array
     */
    protected $fillable = ['name', 'guard_name', 'remarks', 'enabled'];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * The model's default values for attributes when new instance created.
     *
     * @var array
     */
    protected $attributes = [
        'guard_name' => 'web',
        'enabled' => 'yes'
    ];

    /************************ Scopes ************************/
    /**
     * @param Builder $query
     * @param array $filters
     * @return Builder|Conditionable|mixed
     */
    public function scopeApplyFilter(Builder $query, array $filters = [])
    {
        return $query->when(!empty($filters['search']), function ($query) use (&$filters) {
            return $query->where('name', 'like', "%{$filters['search']}%")
                ->orWhere('guard_name', 'like', "%{$filters['search']}%")
                ->orWhere('enabled', '=', "%{$filters['search']}%");
        })->when(!empty($filters['enabled']), function ($query) use (&$filters) {
            return $query->where('enabled', '=', $filters['enabled']);
        })->when(!empty($filters['sort']) && !empty($filters['direction']), function ($query) use (&$filters) {
            return $query->sortable($filters['sort'], ($filters['direction'] ?? 'asc'));
        })->when(isset($filters['id']) && !empty($filters['id']), function ($query) use (&$filters) {
            if (is_array($filters['id'])):
                $query->whereIn('id', $filters['id']);
            else :
                $query->where('id', '=', $filters['id']);
            endif;
        })->when(AuthenticatedSessionService::isSuperAdmin(), function ($query) use (&$filters) {
            return $query->withTrashed();
        });

    }
    /************************ Audit Relations ************************/

    /**
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * @return BelongsTo
     */
    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Count Total Permission Assigned to this role
     *
     * @return int
     */
    public function getTotalPermissionsAttribute(): int
    {
        return $this->permissions->count() ?? 0;
    }

    /**
     * Count Total User Assigned to this role
     *
     * @return int
     */
    public function getTotalUsersAttribute(): int
    {
        return $this->users->count() ?? 0;
    }

}
