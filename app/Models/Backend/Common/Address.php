<?php

namespace App\Models\Backend\Common;

use App\Models\Backend\Setting\City;
use App\Models\Backend\Setting\Country;
use App\Models\Backend\Setting\State;
use App\Models\Backend\Setting\User;
use App\Services\Auth\AuthenticatedSessionService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @class Address
 * @package App\Models\Backend\Common
 * @method Builder applyFilter(array $filters = [])
 */
class Address extends Model implements Auditable
{
    use AuditableTrait, HasFactory, SoftDeletes, Sortable;

    /**
     * @var string $table
     */
    protected $table = 'addresses';

    /**
     * @var string $primaryKey
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     * 'enabled' => to handle status,
     * ['created_by', 'updated_by', 'deleted_by'] => for audit
     *
     * @var array
     */
    protected $fillable = ['addressable_type', 'addressable_id', 'type', 'phone', 'name', 'street_1', 'street_2', 'url', 'longitude', 'latitude', 'post_code', 'fallback', 'enabled', 'remark', 'city_id', 'state_id', 'country_id', 'created_by', 'updated_by', 'deleted_by'];

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
        'enabled' => 'yes'
    ];

    /************************ Scopes ************************/

    public function scopeApplyFilter(Builder $query, array $filters = [])
    {
        return $query->when(!empty($filters['search']), function ($query) use (&$filters) {
            return $query->where('name', 'like', "%{$filters['search']}%")
                ->orWhere('enabled', '=', "%{$filters['search']}%");
        })
            ->when(!empty($filters['enabled']), function ($query) use (&$filters) {
            return $query->where('enabled', '=', $filters['enabled']);
        })
            ->when(!empty($filters['user_id']), function ($query) use (&$filters) {
            return $query->where('user_id', '=', $filters['user_id']);
        })
            ->when(!empty($filters['user_id_distinct']), function ($query) use (&$filters) {
            return $query->distinct();
        })
            ->when(!empty($filters['only_fallback']), function ($query) use (&$filters) {
            return $query->where('fallback', '=', strtolower($filters['only_fallback']));
        })
            ->when(!empty($filters['sort']), function ($query) use (&$filters) {
            $query->sortable($filters['sort'], ($filters['direction'] ?? 'asc'));
        })
            ->when(AuthenticatedSessionService::isSuperAdmin(), function ($query) use (&$filters) {
            $query->withTrashed();
        });
    }

    /************************ Relations ************************/

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
     * @return MorphTo
     */
    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * @return BelongsTo
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    /**
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
