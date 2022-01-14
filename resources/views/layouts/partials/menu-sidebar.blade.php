<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link">
        <img src="{{ asset(\App\Supports\Constant::USER_PROFILE_IMAGE) }}" alt="AdminLTE Logo"
             class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">
            <strong>{{ config('app.name') }}</strong>
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 d-flex border-bottom-0">
            <div class="image">
                <img src="{{ \Auth::user()->getFirstMediaUrl('avatars') }}" class="img-circle elevation-2"
                     alt="{{ \Auth::user()->name }}">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ \Auth::user()->name }}</a>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-3">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('backend.dashboard') }}"
                       class="nav-link  @if(\Route::is('backend.dashboard')) active @endif">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                @can('backend.common.address-books.index')
                    <li class="nav-item">
                        <a href="{{ route('backend.common.address-books.index') }}"
                           class="nav-link @if(\Route::is('backend.common.address-books.*')) active @endif">
                            <i class="fas fa-address-book nav-icon"></i>
                            <p>Address Book</p>
                        </a>
                    </li>
                @endcan

                @canany([
    'backend.shipment.customers.index',
    'backend.shipment.items.index',
    'backend.shipment.invoices.index',
    'backend.shipment.transactions.index',
    'backend.shipment.truck-loads.index'])
                    <li class="nav-item @if(\Route::is('backend.shipment.*')) menu-open @endif">
                        <a href="#" class="nav-link @if(\Route::is('backend.shipment.*')) active @endif">
                            <i class="nav-icon fas fa-ship"></i>
                            <p>Shipment
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview"
                            @if(\Route::is('backend.shipment.*')) style="display: block;" @endif>
                            @can('backend.shipment.customers.index')
                                <li class="nav-item">
                                    <a href="{{ route('backend.shipment.customers.index') }}"
                                       class="nav-link @if(\Route::is('backend.settings.customers.*')) active @endif">
                                        <i class="fas fa-user-tie nav-icon"></i>
                                        <p>Customers</p>
                                    </a>
                                </li>
                            @endcan

                            @can('backend.shipment.items.index')
                                <li class="nav-item">
                                    <a href="{{ route('backend.shipment.items.index') }}"
                                       class="nav-link @if(\Route::is('backend.shipment.items.*')) active @endif">
                                        <i class="fas fa-boxes nav-icon"></i>
                                        <p>Items</p>
                                    </a>
                                </li>
                            @endcan

                            @can('backend.shipment.invoices.index')
                                <li class="nav-item">
                                    <a href="{{ route('backend.shipment.invoices.index') }}"
                                       class="nav-link @if(\Route::is('backend.shipment.invoices.*')) active @endif">
                                        <i class="fas fa-file-invoice-dollar nav-icon"></i>
                                        <p>Invoices</p>
                                    </a>
                                </li>
                            @endcan

                            @can('backend.shipment.transactions.index')
                                <li class="nav-item">
                                    <a href="{{ route('backend.shipment.transactions.index') }}"
                                       class="nav-link @if(\Route::is('backend.shipment.transactions.*')) active @endif">
                                        <i class="fas fa-dollar-sign nav-icon"></i>
                                        <p>Transactions</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan


                @canany([
    'backend.transport.vehicles.index',
    'backend.transport.drivers.index',
    'backend.transport.truck-loads.index',
    'backend.transport.check-points.index'])
                    <li class="nav-item @if(\Route::is('backend.transport.*')) menu-open @endif">
                        <a href="#" class="nav-link @if(\Route::is('backend.transport.*')) active @endif">
                            <i class="nav-icon fas fa-shipping-fast"></i>
                            <p>Transport
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" @if(\Route::is('backend.transport.*')) style="display: block;" @endif>
                            @can('backend.transport.vehicles.index')
                                <li class="nav-item">
                                    <a href="{{ route('backend.transport.vehicles.index') }}"
                                       class="nav-link @if(\Route::is('backend.transport.vehicles.*')) active @endif">
                                        <i class="fas fa-truck nav-icon"></i>
                                        <p>Vehicles</p>
                                    </a>
                                </li>
                            @endcan

                            @can('backend.transport.drivers.index')
                                <li class="nav-item">
                                    <a href="{{ route('backend.transport.drivers.index') }}"
                                       class="nav-link @if(\Route::is('backend.transport.drivers.*')) active @endif">
                                        <i class="fas fa-user-tag nav-icon"></i>
                                        <p>Drivers</p>
                                    </a>
                                </li>
                            @endcan

                            @can('backend.transport.check-points.index')
                                <li class="nav-item">
                                    <a href="{{ route('backend.transport.check-points.index') }}"
                                       class="nav-link @if(\Route::is('backend.transport.check-points.*')) active @endif">
                                        <i class="fas fa-map-marked nav-icon"></i>
                                        <p>Check Points</p>
                                    </a>
                                </li>
                            @endcan

                            @can('backend.transport.truck-loads.index')
                                <li class="nav-item">
                                    <a href="{{ route('backend.transport.truck-loads.index') }}"
                                       class="nav-link @if(\Route::is('backend.transport.truck-loads.*')) active @endif">
                                        <i class="fas fa-truck-loading nav-icon"></i>
                                        <p>Truck Loads</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                @canany([
    'backend.organization.branches.index',
    'backend.organization.employees.index'])
                    <li class="nav-item @if(\Route::is('backend.organization.*')) menu-open @endif">
                        <a href="#" class="nav-link @if(\Route::is('backend.organization.*')) active @endif">
                            <i class="nav-icon fas fa-building"></i>
                            <p>Organization
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview"
                            @if(\Route::is('backend.organization.*')) style="display: block;" @endif>
                            @can('backend.organization.branches.index')
                                <li class="nav-item">
                                    <a href="{{ route('backend.organization.branches.index') }}"
                                       class="nav-link @if(\Route::is('backend.organization.branches.*')) active @endif">
                                        <i class="far fa-building nav-icon"></i>
                                        <p>Branches</p>
                                    </a>
                                </li>
                            @endcan

                            @can('backend.organization.employees.index')
                                <li class="nav-item">
                                    <a href="{{ route('backend.organization.employees.index') }}"
                                       class="nav-link @if(\Route::is('backend.organization.employees.*')) active @endif">
                                        <i class="fas fa-address-card nav-icon"></i>
                                        <p>Employees</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                @canany([
    'backend.settings.users.index',
    'backend.settings.roles.index',
    'backend.settings.permissions.index',
    'backend.settings.catalogs.index',
    'backend.settings.countries.index',
    'backend.settings.states.index',
    'backend.settings.cities.index',
    'backend.settings.occupations.index'])
                    <li class="nav-item @if(\Route::is('backend.settings.*')) menu-open @endif">
                        <a href="#" class="nav-link @if(\Route::is('backend.settings.*')) active @endif">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>Settings
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview"
                            @if(\Route::is('backend.settings.*')) style="display: block;" @endif>
                            @can('backend.settings.users.index')
                                <li class="nav-item">
                                    <a href="{{ route('backend.settings.users.index') }}"
                                       class="nav-link @if(\Route::is('backend.settings.users.*')) active @endif">
                                        <i class="fas fa-users nav-icon"></i>
                                        <p>Users</p>
                                    </a>
                                </li>
                            @endcan

                            @can('backend.settings.roles.index')
                                <li class="nav-item">
                                    <a href="{{ route('backend.settings.roles.index') }}"
                                       class="nav-link @if(\Route::is('backend.settings.roles.*')) active @endif">
                                        <i class="fas fa-address-card nav-icon"></i>
                                        <p>Roles</p>
                                    </a>
                                </li>
                            @endcan

                            @can('backend.settings.permissions.index')
                                <li class="nav-item">
                                    <a href="{{ route('backend.settings.permissions.index') }}"
                                       class="nav-link @if(\Route::is('backend.settings.permissions.*')) active @endif">
                                        <i class="fas fa-list-alt nav-icon"></i>
                                        <p>Permissions</p>
                                    </a>
                                </li>
                            @endcan

                            @can('backend.settings.catalogs.index')
                                <li class="nav-item">
                                    <a href="{{ route('backend.settings.catalogs.index') }}"
                                       class="nav-link @if(\Route::is('backend.settings.catalogs.*')) active @endif">
                                        <i class="fas fa-list-alt nav-icon"></i>
                                        <p>Catalogs</p>
                                    </a>
                                </li>
                            @endcan

                            @can('backend.settings.countries.index')
                                <li class="nav-item">
                                    <a href="{{ route('backend.settings.countries.index') }}"
                                       class="nav-link @if(\Route::is('backend.settings.countries.*')) active @endif">
                                        <i class="fas fa-globe-asia nav-icon"></i>
                                        <p>Countries</p>
                                    </a>
                                </li>
                            @endcan

                            @can('backend.settings.states.index')
                                <li class="nav-item">
                                    <a href="{{ route('backend.settings.states.index') }}"
                                       class="nav-link @if(\Route::is('backend.settings.states.*')) active @endif">
                                        <i class="fas fa-landmark nav-icon"></i>
                                        <p>States</p>
                                    </a>
                                </li>
                            @endcan

                            @can('backend.settings.cities.index')
                                <li class="nav-item">
                                    <a href="{{ route('backend.settings.cities.index') }}"
                                       class="nav-link @if(\Route::is('backend.settings.cities.*')) active @endif">
                                        <i class="fas fa-mountain nav-icon"></i>
                                        <p>Cities</p>
                                    </a>
                                </li>
                            @endcan

                            @can('backend.settings.occupations.index')
                                <li class="nav-item">
                                    <a href="{{ route('backend.settings.occupations.index') }}"
                                       class="nav-link @if(\Route::is('backend.settings.occupations.*')) active @endif">
                                        <i class="fas fa-hospital-user nav-icon"></i>
                                        <p>Occupations</p>
                                    </a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endcan
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<!-- /.main-sidebar -->
