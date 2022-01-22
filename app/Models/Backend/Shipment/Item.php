<?php

namespace App\Models\Backend\Shipment;

use App\Models\Backend\Setting\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @class Item
 * @package App\Models\Backend\Shipment
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
    protected $fillable = ['user_id', 'name', 'dimension', 'rate', 'tax', 'description', 'enabled', 'created_by', 'updated_by', 'deleted_by'];

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


}
