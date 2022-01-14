<?php

namespace App\Models\Backend\Setting;

use Illuminate\Database\Eloquent\Builder;

/**
 * @class Occupation
 * @package App\Models\Backend\Setting
 */
class Occupation extends Catalog
{
    /**
     * The attributes that are mass assignable.
     * 'enabled' => to handle status,
     * ['created_by', 'updated_by', 'deleted_by'] => for audit
     *
     * @var array
     */
    protected $fillable = [ 'name', 'remarks', 'additional_info', 'enabled', 'created_by', 'updated_by', 'deleted_by'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['model_type'];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'model_type' => Occupation::class,
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('bloodGroup', function (Builder $builder) {
            $builder->where('model_type', '=', Occupation::class);
        });
    }
}
