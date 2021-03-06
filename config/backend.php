<?php

use App\Supports\Constant;

return [
    'name' => 'Core',
    'guard' => [
        'web' => 'WEB',
        'api' => 'API'
    ],
    /*
    |--------------------------------------------------------------------------
    | Application Preloader
    |--------------------------------------------------------------------------
    |
    | Preloader that will display when as webpage is very slow.
    | Null if we don't want preloader default enabled
    |
    | @var string|null
    */

    'preloader' => 'assets/img/AdminLTELogo.png',

    /*
    |--------------------------------------------------------------------------
    | Application Default Date & Time Format
    |--------------------------------------------------------------------------
    |
    | Format for date and time setting
    |
    | @var string|null
    */

    'datetime' => 'd M Y h:i a',

    'date' => 'd M Y',

    'time' => 'h:i a',

    /*
    |--------------------------------------------------------------------------
    | Application JavaScript Default Date & Time Format
    |--------------------------------------------------------------------------
    |
    | Null if we don't want preloader default enabled
    |
    | @ref use 'js' as prefix for every field
    | @ref follow moment.js for details
    |
    | @var string|null
    */

    'js_datetime' => 'DD MMM YYYY hh:mm a',

    'js_date' => 'DD MMM YYYY',

    'js_time' => 'hh:mm a',

    /*
    |--------------------------------------------------------------------------
    | Application JavaScript Default Date & Time Format
    |--------------------------------------------------------------------------
    |
    | Null if we don't want preloader default enabled
    |
    | @ref use 'js' as prefix for every field
    | @ref follow moment.js for details
    |
    | @var string|null
    */
    'settings' => [
        'country' => [
            'module' => 'Contact',
            'name' => 'Country',
            'icon' => 'fas fa-globe',
            'route' => 'contact.settings.countries.index',
            'color' => '#007bff',
            'description' => 'Countries list on this system',
            'enabled' => Constant::ENABLED_OPTION
        ],
        'state' => [
            'module' => 'Contact',
            'name' => 'State',
            'icon' => 'fas fa-mountain',
            'route' => 'contact.settings.states.index',
            'color' => '#007bff',
            'description' => 'states available on countries',
            'enabled' => Constant::ENABLED_OPTION
        ],
        'city' => [
            'module' => 'Contact',
            'name' => 'City',
            'icon' => 'fas fa-building',
            'route' => 'contact.settings.cities.index',
            'color' => '#007bff',
            'description' => 'user who can access this system',
            'enabled' => Constant::ENABLED_OPTION
        ],
        'blood-group' => [
            'module' => 'Contact',
            'name' => 'Blood Group',
            'icon' => 'fas fa-object-group',
            'route' => 'contact.settings.blood-groups.index',
            'color' => '#007bff',
            'description' => 'user who can access this system',
            'enabled' => Constant::ENABLED_OPTION
        ],
        'gender' => [
            'module' => 'Contact',
            'name' => 'Gender',
            'icon' => 'fas fa-venus-mars',
            'route' => 'contact.settings.genders.index',
            'color' => '#007bff',
            'description' => 'user who can access this system',
            'enabled' => Constant::ENABLED_OPTION
        ],
        'occupation' => [
            'module' => 'Contact',
            'name' => 'Occupation',
            'icon' => 'fas fa-user-md',
            'route' => 'contact.settings.occupations.index',
            'color' => '#007bff',
            'description' => 'user who can access this system',
            'enabled' => Constant::ENABLED_OPTION
        ],
        'relation' => [
            'module' => 'Contact',
            'name' => 'Relation',
            'icon' => 'fas fa-people-arrows',
            'route' => 'contact.settings.relations.index',
            'color' => '#007bff',
            'description' => 'user who can access this system',
            'enabled' => Constant::ENABLED_OPTION
        ],
        'religion' => [
            'module' => 'Contact',
            'name' => 'Religion',
            'icon' => 'fas fa-place-of-worship',
            'route' => 'contact.settings.religions.index',
            'color' => '#007bff',
            'description' => 'user who can access this system',
            'enabled' => Constant::ENABLED_OPTION
        ],
        'user' => [
            'module' => 'Core',
            'name' => 'User',
            'icon' => 'fas fa-users',
            'route' => 'core.settings.users.index',
            'color' => '#007bff',
            'description' => 'user who can access this system',
            'enabled' => Constant::ENABLED_OPTION
        ],
        'role' => [
            'module' => 'Core',
            'name' => 'Role',
            'icon' => 'fas fa-address-card',
            'route' => 'core.settings.roles.index',
            'color' => '#007bff',
            'description' => 'user who can access this system',
            'enabled' => Constant::ENABLED_OPTION
        ],
        'permission' => [
            'module' => 'Core',
            'name' => 'Permission',
            'icon' => 'fas fa-list-alt',
            'route' => 'core.settings.permissions.index',
            'color' => '#007bff',
            'description' => 'user who can access this system',
            'enabled' => Constant::ENABLED_OPTION
        ],
        'catalog' => [
            'module' => 'Core',
            'name' => 'Catalog',
            'icon' => 'fas fa-clipboard-list',
            'route' => 'core.settings.catalogs.index',
            'color' => '#007bff',
            'description' => 'user who can access this system',
            'enabled' => Constant::ENABLED_OPTION
        ],
    ],

];
