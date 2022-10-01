<?php

namespace App\Models\Backend\Setting;

use App\Models\Backend\Common\Address;
use App\Models\Backend\Shipment\Item;
use App\Services\Auth\AuthenticatedSessionService;
use App\Supports\Constant;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Kyslik\ColumnSortable\Sortable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class Preference
 * @package App\Models\Backend\Auth
 * @method Builder applyFilter(array $filters = [])
 * @property Collection $roles
 */
class User extends Authenticatable implements HasMedia, Auditable
{
    use AuditableTrait, HasFactory, Notifiable, InteractsWithMedia, HasRoles, Sortable, SoftDeletes;

    /**
     * @var string $table
     */
    protected $table = 'users';

    /**
     * @var string $primaryKey
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'mobile',
        'password',
        'remarks',
        'home_page',
        'locale',
        'enabled',
        'force_pass_reset',
        'email_verified_at',
        'parent_id',
        'created_by',
        'updated_by',
        'deleted_by'

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'force_pass_reset' => 'bool'
    ];

    /************************ Scopes ************************/

    /**
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    public function scopeApplyFilter(Builder $query, array $filters = [])
    {
        return $query->when((isset($filters['search']) && !empty($filters['search'])), function ($query) use (&$filters) {
            return $query->where('name', 'like', "%{$filters['search']}%")
                ->orWhere('username', 'like', "%{$filters['search']}%")
                ->orWhere('email', '=', "%{$filters['search']}%")
                ->orWhere('mobile', '=', "%{$filters['search']}%")
                ->orWhere('enabled', '=', "%{$filters['search']}%");
        })->when(isset($filters['enabled']) && !empty($filters['enabled']), function ($query) use (&$filters) {
            return $query->where('enabled', '=', $filters['enabled']);
        })->when(isset($filters['parent_id']) && !empty($filters['parent_id']), function ($query) use (&$filters) {
            return $query->where('parent_id', '=', $filters['parent_id']);
        })->when(isset($filters['sort']) && !empty($filters['direction']), function ($query) use (&$filters) {
            return $query->sortable($filters['sort'], ($filters['direction'] ?? 'asc'));
        })->when(isset($filters['role']) && !empty($filters['role']), function ($query) use (&$filters) {
            return $query->whereHas('roles', function (Builder $subQuery) use (&$filters) {
                if (!is_array($filters['role'])) {
                    $filters['role'][] = $filters['role'];
                }
                $subQuery->whereIn('id', $filters['role']);
            });
        })->when(isset($filters['role_name']) && !empty($filters['role_name']), function ($query) use (&$filters) {
            return $query->whereHas('roles', function (Builder $subQuery) use (&$filters) {
                $subQuery->whereIn('name', $filters['role_name']);
            });
        })->when(AuthenticatedSessionService::isSuperAdmin(), function ($query) use (&$filters) {
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



    /************************ Utility Methods ************************/

    /**
     * @param string $username
     * @return bool
     */
    public static function verifyUniqueUsername(string $username): bool
    {
        return ((new self())->newQuery()->where('username', '=', $username)->first() == null);
    }


    /**
     * Create a unique random username with given having input
     * As prefix text and a random number
     *
     * @param string $name
     * @return string
     * @throws Exception
     */
    public static function generateUsername(string $name): string
    {
        //removed white space from name
        $firstPart = preg_replace("([\s]+)", '-', Str::lower($name));

        //add a random number to end
        $username = trim($firstPart) . random_int(100, 1000);

        //verify generated username is unique
        return (self::verifyUniqueUsername($username)) ? $username : self::generateUsername($name);

    }


    /************************ Other Methods ************************/
    /**
     * Register profile Image Media Collection
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatars')
            ->useDisk('avatar')
            ->useFallbackUrl(Constant::USER_PROFILE_IMAGE)
            ->acceptsMimeTypes(['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/svg'])
            ->singleFile();
    }

    /**
     * Verify if current user as super admin role
     *
     * @return bool
     */
    public function getIsAdminAttribute(): bool
    {
        return $this->hasRole('Super Administrator');
    }

    /**
     * Return all Role ID's of a user
     *
     * @return array
     */
    public function getRoleIdsAttribute(): array
    {
        return $this->roles()->pluck('id')->toArray();
    }

    /**
     * Return all Permission ID's of a user
     *
     * @return array
     */
    public function getPermissionIdsAttribute(): array
    {
        return $this->permissions()->pluck('id')->toArray();
    }

    /**
     * @return HasMany
     */
    public function receivers(): HasMany
    {
        return $this->hasMany(User::class, 'parent_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function senders(): HasMany
    {
        return $this->hasMany(User::class, 'id', 'parent_id');
    }

    /**
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'user_id', 'id');
    }

    /**
     * @return MorphMany
     */
    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable', 'addressable_type', 'addressable_id');
    }
}
