<?php

namespace App\Models\Backend\Shipment;

use App\Models\Backend\Setting\User;
use App\Supports\Constant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Kyslik\ColumnSortable\Sortable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Permission\Traits\HasRoles;

/**
 * @class Customer
 * @package App\Models\Backend\Shipment
 */
class Customer extends User implements HasMedia
{
    use AuditableTrait, HasFactory, Notifiable, HasRoles, Sortable, SoftDeletes;

    protected $table = 'users';
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('customer', function (Builder $builder) {

        });
    }

    /**
     * Register profile Image Media Collection
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatars')
            ->useDisk('avatar')
            ->useFallbackUrl(Constant::USER_PROFILE_IMAGE)
            ->acceptsMimeTypes(['image/jpeg','image/jpg','image/png','image/gif','image/svg'])
            ->singleFile();
    }
}
