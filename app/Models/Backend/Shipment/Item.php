<?php

namespace App\Models\Backend\Shipment;

use App\Models\Backend\Setting\User;
use App\Services\Auth\AuthenticatedSessionService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Traits\Conditionable;
use Kyslik\ColumnSortable\Sortable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @class Item
 * @package App\Models\Backend\Shipment
 * @method Builder applyFilter(array $filters = [])
 */
class Item extends Model implements Auditable
{
    use AuditableTrait, HasFactory, SoftDeletes, Sortable;

    /**
     * @var string $table
     */
    protected $table = 'items';

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
    protected $fillable = ['user_id', 'name', 'dimension', 'rate', 'currency', 'tax', 'description', 'enabled', 'created_by', 'updated_by', 'deleted_by'];

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

    /************************ Static Function ************************/

    /**
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::retrieved(function ($item) {
            $dimension = $item->dimension ?? '';
            $dimensionArray = explode("X", $dimension);
            $item->length = $dimensionArray[0] ?? null;
            $item->width = $dimensionArray[1] ?? null;
            $item->height = $dimensionArray[2] ?? null;
        });

        static::saving(function ($item) {
            if (isset($item->length) || isset($item->width) || isset($item->height)) {
                $dimension[0] = $item->length ?? null;
                $dimension[1] = $item->width ?? null;
                $dimension[2] = $item->height ?? null;
                $item->dimension = implode("X", $dimension);
            }
        });

    }

    /************************ Scope Method ************************/

    /**
     * @param Builder $query
     * @param array $filters
     * @return Builder|Conditionable|mixed
     */
    public function scopeApplyFilter(Builder $query, array $filters = [])
    {
        return $query->when(!empty($filters['search']), function ($query) use (&$filters) {
            return $query->where('name', 'like', "%{$filters['search']}%")
                ->orWhere('currency', 'like', "%{$filters['search']}%")
                ->orWhere('description', 'like', "%{$filters['search']}%")
                ->orWhere('enabled', '=', "%{$filters['search']}%");
        })
            ->when(!empty($filters['enabled']), function ($query) use (&$filters) {
                return $query->where('enabled', '=', $filters['enabled']);
            })
            ->when(!empty($filters['user']), function ($query) use (&$filters) {
                return $query->where('user_id', '=', $filters['user']);
            })
            ->when(!empty($filters['sort']), function ($query) use (&$filters) {
                return $query->sortable($filters['sort'], ($filters['direction'] ?? 'asc'));
            })
            ->when(AuthenticatedSessionService::isSuperAdmin(), function ($query) use (&$filters) {
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
     * @return
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
