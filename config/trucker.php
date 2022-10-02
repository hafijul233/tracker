<?php

return [
    'menu' => [
        'dashboard' => [
            'name' => 'menu-sidebar.Dashboard',
            'route' => 'backend.dashboard',
            'group' => 'backend.dashboard',
            'icon' => 'fas fa-tachometer-alt',
            'enabled' => true,
            'permissions' => [],
            'children' => []
        ],
        'address-book' => [
            'name' => 'menu-sidebar.Address Book',
            'route' => 'backend.common.address-books.index',
            'group' => 'backend.common.address-books.*',
            'icon' => 'fas fa-address-book',
            'enabled' => true,
            'permissions' => [],
            'children' => []
        ],
        'shipment' => [
            'name' => 'menu-sidebar.Address Book',
            'route' => 'backend.common.address-books.index',
            'group' => 'backend.common.address-books.*',
            'icon' => 'fas fa-tachometer-alt',
            'enabled' => true,
            'permissions' => [
                'backend.shipment.customers.index',
                'backend.shipment.items.index',
                'backend.shipment.invoices.index',
                'backend.shipment.transactions.index',
                'backend.shipment.truck-loads.index'],
            'children' => []
        ],

    ],
];