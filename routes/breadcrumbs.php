<?php

use App\Models\Backend\Common\Address;
use App\Models\Backend\Organization\Branch;
use App\Models\Backend\Setting\Barcode;
use App\Models\Backend\Setting\Catalog;
use App\Models\Backend\Setting\City;
use App\Models\Backend\Setting\Cost;
use App\Models\Backend\Setting\Country;
use App\Models\Backend\Setting\Occupation;
use App\Models\Backend\Setting\Permission;
use App\Models\Backend\Setting\Role;
use App\Models\Backend\Setting\Sms;
use App\Models\Backend\Setting\SmsTemplate;
use App\Models\Backend\Setting\State;
use App\Models\Backend\Setting\User;
use App\Models\Backend\Shipment\Invoice;
use App\Models\Backend\Shipment\Item;
use App\Models\Backend\Shipment\Transaction;
use App\Models\Backend\Transport\CheckPoint;
use App\Models\Backend\Transport\TruckLoad;
use App\Models\Backend\Transport\Vehicle;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push(__('menu-sidebar.Home'), route('home'));
});

Breadcrumbs::for('backend', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push(__('menu-sidebar.Backend'), route('backend'));
});

Breadcrumbs::for('backend.dashboard', function (BreadcrumbTrail $trail) {
    $trail->parent('backend');
    $trail->push(__('menu-sidebar.Dashboard'), route('backend.dashboard'));
});

/****************************************** Http Error ******************************************/

Breadcrumbs::for('errors.401', function (BreadcrumbTrail $trail) {
    $trail->parent('backend');
    $trail->push('Unauthorized Access', route('errors.401'));
});

Breadcrumbs::for('errors.403', function (BreadcrumbTrail $trail) {
    $trail->parent('backend');
    $trail->push('Access Forbidden', route('errors.403'));
});

Breadcrumbs::for('errors.404', function (BreadcrumbTrail $trail) {
    $trail->parent('backend');
    $trail->push('Page Not Found');
});

Breadcrumbs::for('errors.419', function (BreadcrumbTrail $trail) {
    $trail->parent('backend');
    $trail->push('Page/Request Expired', route('errors.419'));
});

Breadcrumbs::for('errors.429', function (BreadcrumbTrail $trail) {
    $trail->parent('backend');
    $trail->push('Too Many Requests', route('errors.429'));
});

Breadcrumbs::for('errors.500', function (BreadcrumbTrail $trail) {
    $trail->parent('backend');
    $trail->push('Internal Server Error', route('errors.500'));
});

Breadcrumbs::for('errors.503', function (BreadcrumbTrail $trail) {
    $trail->parent('backend');
    $trail->push('Service Unavailable', route('errors.503'));
});

/****************************************** Setting ******************************************/

Breadcrumbs::for('backend.settings', function (BreadcrumbTrail $trail) {

    $trail->parent('home');

    $trail->push(__('menu-sidebar.Settings'), route('backend.settings'));
});

/****************************************** User ******************************************/

Breadcrumbs::for('backend.settings.users.index', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.settings');

    $trail->push(__('menu-sidebar.Users'), route('backend.settings.users.index'));
});

Breadcrumbs::for('backend.settings.users.create', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.settings.users.index');

    $trail->push('Add', route('backend.settings.users.create'));
});

Breadcrumbs::for('backend.settings.users.show', function (BreadcrumbTrail $trail, $user) {

    $trail->parent('backend.settings.users.index');

    $user = ($user instanceof User) ? $user : $user[0];

    $trail->push($user->name, route('backend.settings.users.show', $user->id));
});

Breadcrumbs::for('backend.settings.users.edit', function (BreadcrumbTrail $trail, User $user) {

    $trail->parent('backend.settings.users.show', [$user]);

    $trail->push(__('common.Edit'), route('backend.settings.users.edit', $user->id));
});

/****************************************** Permission ******************************************/

Breadcrumbs::for('backend.settings.permissions.index', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.settings');

    $trail->push(__('menu-sidebar.Permissions'), route('backend.settings.permissions.index'));
});

Breadcrumbs::for('backend.settings.permissions.create', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.settings.permissions.index');

    $trail->push(__('common.Add'), route('backend.settings.permissions.create'));
});

Breadcrumbs::for('backend.settings.permissions.show', function (BreadcrumbTrail $trail, $permission) {

    $trail->parent('backend.settings.permissions.index');

    $permission = ($permission instanceof Permission) ? $permission : $permission[0];

    $trail->push($permission->display_name, route('backend.settings.permissions.show', $permission->id));
});

Breadcrumbs::for('backend.settings.permissions.edit', function (BreadcrumbTrail $trail, Permission $permission) {

    $trail->parent('backend.settings.permissions.show', [$permission]);

    $trail->push(__('common.Edit'), route('backend.settings.permissions.edit', $permission->id));
});

/****************************************** Role ******************************************/

Breadcrumbs::for('backend.settings.roles.index', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.settings');

    $trail->push(__('menu-sidebar.Roles'), route('backend.settings.roles.index'));
});

Breadcrumbs::for('backend.settings.roles.create', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.settings.roles.index');

    $trail->push(__('common.Add'), route('backend.settings.roles.create'));
});

Breadcrumbs::for('backend.settings.roles.show', function (BreadcrumbTrail $trail, $role) {

    $trail->parent('backend.settings.roles.index');

    $role = ($role instanceof Role) ? $role : $role[0];

    $trail->push($role->name, route('backend.settings.roles.show', $role->id));
});

Breadcrumbs::for('backend.settings.roles.edit', function (BreadcrumbTrail $trail, Role $role) {

    $trail->parent('backend.settings.roles.show', [$role]);

    $trail->push(__('common.Edit'), route('backend.settings.roles.edit', $role->id));
});


/****************************************** Catalog ******************************************/

Breadcrumbs::for('backend.settings.catalogs.index', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.settings');

    $trail->push(__('menu-sidebar.Catalogs'), route('backend.settings.catalogs.index'));
});

Breadcrumbs::for('backend.settings.catalogs.create', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.settings.catalogs.index');

    $trail->push(__('common.Add'), route('backend.settings.catalogs.create'));
});

Breadcrumbs::for('backend.settings.catalogs.show', function (BreadcrumbTrail $trail, $catalog) {

    $trail->parent('backend.settings.catalogs.index');

    $catalog = ($catalog instanceof Catalog) ? $catalog : $catalog[0];

    $trail->push($catalog->name, route('backend.settings.catalogs.show', $catalog->id));
});

Breadcrumbs::for('backend.settings.catalogs.edit', function (BreadcrumbTrail $trail, Catalog $catalog) {

    $trail->parent('backend.settings.catalogs.show', [$catalog]);

    $trail->push(__('common.Edit'), route('backend.settings.catalogs.edit', $catalog->id));
});

/****************************************** Country ******************************************/

Breadcrumbs::for('backend.settings.countries.index', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.settings');

    $trail->push(__('menu-sidebar.Countries'), route('backend.settings.countries.index'));
});

Breadcrumbs::for('backend.settings.countries.create', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.settings.countries.index');

    $trail->push(__('common.Add'), route('backend.settings.countries.create'));
});

Breadcrumbs::for('backend.settings.countries.show', function (BreadcrumbTrail $trail, $country) {

    $trail->parent('backend.settings.countries.index');

    $country = ($country instanceof Country) ? $country : $country[0];

    $trail->push($country->name, route('backend.settings.countries.show', $country->id));
});

Breadcrumbs::for('backend.settings.countries.edit', function (BreadcrumbTrail $trail, Country $country) {

    $trail->parent('backend.settings.countries.show', [$country]);

    $trail->push(__('common.Edit'), route('backend.settings.countries.edit', $country->id));
});

/****************************************** State ******************************************/

Breadcrumbs::for('backend.settings.states.index', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.settings');

    $trail->push(__('menu-sidebar.States'), route('backend.settings.states.index'));
});

Breadcrumbs::for('backend.settings.states.create', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.settings.states.index');

    $trail->push(__('common.Add'), route('backend.settings.states.create'));
});

Breadcrumbs::for('backend.settings.states.show', function (BreadcrumbTrail $trail, $state) {

    $trail->parent('backend.settings.states.index');

    $state = ($state instanceof State) ? $state : $state[0];

    $trail->push($state->name, route('backend.settings.states.show', $state->id));
});

Breadcrumbs::for('backend.settings.states.edit', function (BreadcrumbTrail $trail, State $state) {

    $trail->parent('backend.settings.states.show', [$state]);

    $trail->push(__('common.Edit'), route('backend.settings.states.edit', $state->id));
});

/****************************************** City ******************************************/

Breadcrumbs::for('backend.settings.cities.index', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.settings');

    $trail->push(__('menu-sidebar.Cities'), route('backend.settings.cities.index'));
});

Breadcrumbs::for('backend.settings.cities.create', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.settings.cities.index');

    $trail->push(__('common.Add'), route('backend.settings.cities.create'));
});

Breadcrumbs::for('backend.settings.cities.show', function (BreadcrumbTrail $trail, $city) {

    $trail->parent('backend.settings.cities.index');

    $city = ($city instanceof City) ? $city : $city[0];

    $trail->push($city->name, route('backend.settings.cities.show', $city->id));
});

Breadcrumbs::for('backend.settings.cities.edit', function (BreadcrumbTrail $trail, City $city) {

    $trail->parent('backend.settings.cities.show', [$city]);

    $trail->push(__('common.Edit'), route('backend.settings.cities.edit', $city->id));
});

/****************************************** Occupation ******************************************/
Breadcrumbs::for('backend.settings.occupations.index', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.settings');

    $trail->push(__('menu-sidebar.Occupations'), route('backend.settings.occupations.index'));
});

Breadcrumbs::for('backend.settings.occupations.create', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.settings.occupations.index');

    $trail->push(__('common.Add'), route('backend.settings.occupations.create'));
});

Breadcrumbs::for('backend.settings.occupations.show', function (BreadcrumbTrail $trail, $occupation) {

    $trail->parent('backend.settings.occupations.index');

    $occupation = ($occupation instanceof Occupation) ? $occupation : $occupation[0];

    $trail->push($occupation->name, route('backend.settings.occupations.show', $occupation->id));
});

Breadcrumbs::for('backend.settings.occupations.edit', function (BreadcrumbTrail $trail, Occupation $occupation) {

    $trail->parent('backend.settings.occupations.show', [$occupation]);

    $trail->push(__('common.Edit'), route('backend.settings.occupations.edit', $occupation->id));
});

/****************************************** Cost ******************************************/

Breadcrumbs::for('backend.setting.costs.index', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.organization');

    $trail->push(__('menu-sidebar.Costs'), route('backend.setting.costs.index'));
});

Breadcrumbs::for('backend.setting.costs.create', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.setting.costs.index');

    $trail->push(__('common.Add'), route('backend.setting.costs.create'));
});

Breadcrumbs::for('backend.setting.costs.show', function (BreadcrumbTrail $trail, $cost) {

    $trail->parent('backend.setting.costs.index');

    $cost = ($cost instanceof Cost) ? $cost : $cost[0];

    $trail->push($cost->name, route('backend.setting.costs.show', $cost->id));
});

Breadcrumbs::for('backend.setting.costs.edit', function (BreadcrumbTrail $trail, Cost $cost) {

    $trail->parent('backend.setting.costs.show', [$cost]);

    $trail->push(__('common.Edit'), route('backend.setting.costs.edit', $cost->id));
});

/****************************************** Sms ******************************************/

Breadcrumbs::for('backend.setting.smss.index', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.organization');

    $trail->push(__('menu-sidebar.Smss'), route('backend.setting.smss.index'));
});

Breadcrumbs::for('backend.setting.smss.create', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.setting.smss.index');

    $trail->push(__('common.Add'), route('backend.setting.smss.create'));
});

Breadcrumbs::for('backend.setting.smss.show', function (BreadcrumbTrail $trail, $sms) {

    $trail->parent('backend.setting.smss.index');

    $sms = ($sms instanceof Sms) ? $sms : $sms[0];

    $trail->push($sms->name, route('backend.setting.smss.show', $sms->id));
});

Breadcrumbs::for('backend.setting.smss.edit', function (BreadcrumbTrail $trail, Sms $sms) {

    $trail->parent('backend.setting.smss.show', [$sms]);

    $trail->push(__('common.Edit'), route('backend.setting.smss.edit', $sms->id));
});

/****************************************** SmsTemplate ******************************************/

Breadcrumbs::for('backend.setting.smstemplates.index', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.organization');

    $trail->push(__('menu-sidebar.SmsTemplates'), route('backend.setting.smstemplates.index'));
});

Breadcrumbs::for('backend.setting.smstemplates.create', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.setting.smstemplates.index');

    $trail->push(__('common.Add'), route('backend.setting.smstemplates.create'));
});

Breadcrumbs::for('backend.setting.smstemplates.show', function (BreadcrumbTrail $trail, $smstemplate) {

    $trail->parent('backend.setting.smstemplates.index');

    $smstemplate = ($smstemplate instanceof SmsTemplate) ? $smstemplate : $smstemplate[0];

    $trail->push($smstemplate->name, route('backend.setting.smstemplates.show', $smstemplate->id));
});

Breadcrumbs::for('backend.setting.smstemplates.edit', function (BreadcrumbTrail $trail, SmsTemplate $smstemplate) {

    $trail->parent('backend.setting.smstemplates.show', [$smstemplate]);

    $trail->push(__('common.Edit'), route('backend.setting.smstemplates.edit', $smstemplate->id));
});

/****************************************** Barcode ******************************************/

Breadcrumbs::for('backend.setting.barcodes.index', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.organization');

    $trail->push(__('menu-sidebar.Barcodes'), route('backend.setting.barcodes.index'));
});

Breadcrumbs::for('backend.setting.barcodes.create', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.setting.barcodes.index');

    $trail->push(__('common.Add'), route('backend.setting.barcodes.create'));
});

Breadcrumbs::for('backend.setting.barcodes.show', function (BreadcrumbTrail $trail, $barcode) {

    $trail->parent('backend.setting.barcodes.index');

    $barcode = ($barcode instanceof Barcode) ? $barcode : $barcode[0];

    $trail->push($barcode->name, route('backend.setting.barcodes.show', $barcode->id));
});

Breadcrumbs::for('backend.setting.barcodes.edit', function (BreadcrumbTrail $trail, Barcode $barcode) {

    $trail->parent('backend.setting.barcodes.show', [$barcode]);

    $trail->push(__('common.Edit'), route('backend.setting.barcodes.edit', $barcode->id));
});


/****************************************** Shipment ******************************************/
Breadcrumbs::for('backend.shipment', function (BreadcrumbTrail $trail) {

    $trail->parent('backend');

    $trail->push(__('menu-sidebar.Shipment'), route('backend.shipment'));
});

/****************************************** Customer ******************************************/
Breadcrumbs::for('backend.shipment.customers.index', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.shipment');

    $trail->push(__('menu-sidebar.Customers'), route('backend.shipment.customers.index'));
});

Breadcrumbs::for('backend.shipment.customers.create', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.shipment.customers.index');

    $trail->push(__('common.Add'), route('backend.shipment.customers.create'));
});

Breadcrumbs::for('backend.shipment.customers.show', function (BreadcrumbTrail $trail, $customer) {

    $trail->parent('backend.shipment.customers.index');

    $customer = ($customer instanceof User) ? $customer : $customer[0];

    $trail->push($customer->name, route('backend.shipment.customers.show', $customer->id));
});

Breadcrumbs::for('backend.shipment.customers.edit', function (BreadcrumbTrail $trail, User $customer) {

    $trail->parent('backend.shipment.customers.show', [$customer]);

    $trail->push(__('common.Edit'), route('backend.shipment.customers.edit', $customer->id));
});

/****************************************** Item ******************************************/
Breadcrumbs::for('backend.shipment.items.index', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.shipment');

    $trail->push(__('menu-sidebar.Items'), route('backend.shipment.items.index'));
});

Breadcrumbs::for('backend.shipment.items.create', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.shipment.items.index');

    $trail->push(__('common.Add'), route('backend.shipment.items.create'));
});

Breadcrumbs::for('backend.shipment.items.show', function (BreadcrumbTrail $trail, $item) {

    $trail->parent('backend.shipment.items.index');

    $item = ($item instanceof Item) ? $item : $item[0];

    $trail->push($item->name, route('backend.shipment.items.show', $item->id));
});

Breadcrumbs::for('backend.shipment.items.edit', function (BreadcrumbTrail $trail, Item $item) {

    $trail->parent('backend.shipment.items.show', [$item]);

    $trail->push(__('common.Edit'), route('backend.shipment.items.edit', $item->id));
});

/****************************************** Invoice ******************************************/

Breadcrumbs::for('backend.shipment.invoices.index', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.shipment');

    $trail->push(__('menu-sidebar.Invoices'), route('backend.shipment.invoices.index'));
});

Breadcrumbs::for('backend.shipment.invoices.create', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.shipment.invoices.index');

    $trail->push(__('common.Add'), route('backend.shipment.invoices.create'));
});

Breadcrumbs::for('backend.shipment.invoices.show', function (BreadcrumbTrail $trail, $invoice) {

    $trail->parent('backend.shipment.invoices.index');

    $invoice = ($invoice instanceof Invoice) ? $invoice : $invoice[0];

    $trail->push($invoice->name, route('backend.shipment.invoices.show', $invoice->id));
});

Breadcrumbs::for('backend.shipment.invoices.edit', function (BreadcrumbTrail $trail, Invoice $invoice) {

    $trail->parent('backend.shipment.invoices.show', [$invoice]);

    $trail->push(__('common.Edit'), route('backend.shipment.invoices.edit', $invoice->id));
});

/****************************************** Transaction ******************************************/

Breadcrumbs::for('backend.shipment.transactions.index', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.shipment');

    $trail->push(__('menu-sidebar.Transactions'), route('backend.shipment.transactions.index'));
});

Breadcrumbs::for('backend.shipment.transactions.create', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.shipment.transactions.index');

    $trail->push(__('common.Add'), route('backend.shipment.transactions.create'));
});

Breadcrumbs::for('backend.shipment.transactions.show', function (BreadcrumbTrail $trail, $transaction) {

    $trail->parent('backend.shipment.transactions.index');

    $transaction = ($transaction instanceof Transaction) ? $transaction : $transaction[0];

    $trail->push($transaction->name, route('backend.shipment.transactions.show', $transaction->id));
});

Breadcrumbs::for('backend.shipment.transactions.edit', function (BreadcrumbTrail $trail, Transaction $transaction) {

    $trail->parent('backend.shipment.transactions.show', [$transaction]);

    $trail->push(__('common.Edit'), route('backend.shipment.transactions.edit', $transaction->id));
});

/****************************************** Transport ******************************************/

Breadcrumbs::for('backend.transport', function (BreadcrumbTrail $trail) {

    $trail->parent('backend');

    $trail->push(__('menu-sidebar.Transport'), route('backend.transport'));
});

/****************************************** Vehicle ******************************************/

Breadcrumbs::for('backend.transport.vehicles.index', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.transport');

    $trail->push(__('menu-sidebar.Vehicles'), route('backend.transport.vehicles.index'));
});

Breadcrumbs::for('backend.transport.vehicles.create', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.transport.vehicles.index');

    $trail->push(__('common.Add'), route('backend.transport.vehicles.create'));
});

Breadcrumbs::for('backend.transport.vehicles.show', function (BreadcrumbTrail $trail, $vehicle) {

    $trail->parent('backend.transport.vehicles.index');

    $vehicle = ($vehicle instanceof Vehicle) ? $vehicle : $vehicle[0];

    $trail->push($vehicle->name, route('backend.transport.vehicles.show', $vehicle->id));
});

Breadcrumbs::for('backend.transport.vehicles.edit', function (BreadcrumbTrail $trail, Vehicle $vehicle) {

    $trail->parent('backend.transport.vehicles.show', [$vehicle]);

    $trail->push(__('common.Edit'), route('backend.transport.vehicles.edit', $vehicle->id));
});

/****************************************** Driver ******************************************/

Breadcrumbs::for('backend.transport.drivers.index', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.transport');

    $trail->push(__('menu-sidebar.Drivers'), route('backend.transport.drivers.index'));
});

Breadcrumbs::for('backend.transport.drivers.create', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.transport.drivers.index');

    $trail->push(__('common.Add'), route('backend.transport.drivers.create'));
});

Breadcrumbs::for('backend.transport.drivers.show', function (BreadcrumbTrail $trail, $driver) {

    $trail->parent('backend.transport.drivers.index');

    $driver = ($driver instanceof User) ? $driver : $driver[0];

    $trail->push($driver->name, route('backend.transport.drivers.show', $driver->id));
});

Breadcrumbs::for('backend.transport.drivers.edit', function (BreadcrumbTrail $trail, User $driver) {

    $trail->parent('backend.transport.drivers.show', [$driver]);

    $trail->push(__('common.Edit'), route('backend.transport.drivers.edit', $driver->id));
});

/****************************************** CheckPoint ******************************************/

Breadcrumbs::for('backend.transport.check-points.index', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.transport');

    $trail->push(__('menu-sidebar.CheckPoints'), route('backend.transport.check-points.index'));
});

Breadcrumbs::for('backend.transport.check-points.create', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.transport.check-points.index');

    $trail->push(__('common.Add'), route('backend.transport.check-points.create'));
});

Breadcrumbs::for('backend.transport.check-points.show', function (BreadcrumbTrail $trail, $checkPoint) {

    $trail->parent('backend.transport.check-points.index');

    $checkPoint = ($checkPoint instanceof CheckPoint) ? $checkPoint : $checkPoint[0];

    $trail->push($checkPoint->name, route('backend.transport.check-points.show', $checkPoint->id));
});

Breadcrumbs::for('backend.transport.check-points.edit', function (BreadcrumbTrail $trail, CheckPoint $checkPoint) {

    $trail->parent('backend.transport.check-points.show', [$checkPoint]);

    $trail->push(__('common.Edit'), route('backend.transport.check-points.edit', $checkPoint->id));
});

/****************************************** TruckLoad ******************************************/

Breadcrumbs::for('backend.transport.truck-loads.index', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.transport');

    $trail->push(__('menu-sidebar.Track Loads'), route('backend.transport.truck-loads.index'));
});

Breadcrumbs::for('backend.transport.truck-loads.create', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.transport.truck-loads.index');

    $trail->push(__('common.Add'), route('backend.transport.truck-loads.create'));
});

Breadcrumbs::for('backend.transport.truck-loads.show', function (BreadcrumbTrail $trail, $trackLoad) {

    $trail->parent('backend.transport.truck-loads.index');

    $trackLoad = ($trackLoad instanceof TruckLoad) ? $trackLoad : $trackLoad[0];

    $trail->push($trackLoad->name, route('backend.transport.truck-loads.show', $trackLoad->id));
});

Breadcrumbs::for('backend.transport.truck-loads.edit', function (BreadcrumbTrail $trail, TruckLoad $trackLoad) {

    $trail->parent('backend.transport.truck-loads.show', [$trackLoad]);

    $trail->push(__('common.Edit'), route('backend.transport.truck-loads.edit', $trackLoad->id));
});

/****************************************** Organization ******************************************/

Breadcrumbs::for('backend.organization', function (BreadcrumbTrail $trail) {

    $trail->parent('backend');

    $trail->push(__('menu-sidebar.Organization'), route('backend.organization'));
});

/****************************************** Branch ******************************************/

Breadcrumbs::for('backend.organization.branches.index', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.organization');

    $trail->push(__('menu-sidebar.Branches'), route('backend.organization.branches.index'));
});

Breadcrumbs::for('backend.organization.branches.create', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.organization.branches.index');

    $trail->push(__('common.Add'), route('backend.organization.branches.create'));
});

Breadcrumbs::for('backend.organization.branches.show', function (BreadcrumbTrail $trail, $branch) {

    $trail->parent('backend.organization.branches.index');

    $branch = ($branch instanceof Branch) ? $branch : $branch[0];

    $trail->push($branch->name, route('backend.organization.branches.show', $branch->id));
});

Breadcrumbs::for('backend.organization.branches.edit', function (BreadcrumbTrail $trail, Branch $branch) {

    $trail->parent('backend.organization.branches.show', [$branch]);

    $trail->push(__('common.Edit'), route('backend.organization.branches.edit', $branch->id));
});

/****************************************** Employee ******************************************/

Breadcrumbs::for('backend.organization.employees.index', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.organization');

    $trail->push(__('menu-sidebar.Employees'), route('backend.organization.employees.index'));
});

Breadcrumbs::for('backend.organization.employees.create', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.organization.employees.index');

    $trail->push(__('common.Add'), route('backend.organization.employees.create'));
});

Breadcrumbs::for('backend.organization.employees.show', function (BreadcrumbTrail $trail, $employee) {

    $trail->parent('backend.organization.employees.index');

    $employee = ($employee instanceof User) ? $employee : $employee[0];

    $trail->push($employee->name, route('backend.organization.employees.show', $employee->id));
});

Breadcrumbs::for('backend.organization.employees.edit', function (BreadcrumbTrail $trail, User $employee) {

    $trail->parent('backend.organization.employees.show', [$employee]);

    $trail->push(__('common.Edit'), route('backend.organization.employees.edit', $employee->id));
});

/****************************************** Address Book ******************************************/
Breadcrumbs::for('backend.common.address-books.index', function (BreadcrumbTrail $trail) {

    $trail->parent('backend');

    $trail->push(__('menu-sidebar.Address Book'), route('backend.common.address-books.index'));
});

Breadcrumbs::for('backend.common.address-books.create', function (BreadcrumbTrail $trail) {

    $trail->parent('backend.common.address-books.index');

    $trail->push(__('common.Add'), route('backend.common.address-books.create'));
});

Breadcrumbs::for('backend.common.address-books.show', function (BreadcrumbTrail $trail, $addressBook) {

    $trail->parent('backend.common.address-books.index');

    $addressBook = ($addressBook instanceof Address) ? $addressBook : $addressBook[0];

    $trail->push($addressBook->name, route('backend.common.address-books.show', $addressBook->id));
});

Breadcrumbs::for('backend.common.address-books.edit', function (BreadcrumbTrail $trail, Address $addressBook) {

    $trail->parent('backend.common.address-books.show', [$addressBook]);

    $trail->push(__('common.Edit'), route('backend.common.address-books.edit', $addressBook->id));
});