<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Backend\Common\AddressBookController;
use App\Http\Controllers\Backend\Common\NotificationController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\Model\ModelEnabledController;
use App\Http\Controllers\Backend\Model\ModelRestoreController;
use App\Http\Controllers\Backend\Model\ModelSoftDeleteController;
use App\Http\Controllers\Backend\Organization\BranchController;
use App\Http\Controllers\Backend\Organization\EmployeeController;
use App\Http\Controllers\Backend\OrganizationController;
use App\Http\Controllers\Backend\Setting\CatalogController;
use App\Http\Controllers\Backend\Setting\CityController;
use App\Http\Controllers\Backend\Setting\CountryController;
use App\Http\Controllers\Backend\Setting\OccupationController;
use App\Http\Controllers\Backend\Setting\PermissionController;
use App\Http\Controllers\Backend\Setting\RoleController;
use App\Http\Controllers\Backend\Setting\StateController;
use App\Http\Controllers\Backend\Setting\UserController;
use App\Http\Controllers\Backend\SettingController;
use App\Http\Controllers\Backend\Shipment\CustomerController;
use App\Http\Controllers\Backend\Shipment\InvoiceController;
use App\Http\Controllers\Backend\Shipment\ItemController;
use App\Http\Controllers\Backend\Shipment\TransactionController;
use App\Http\Controllers\Backend\ShipmentController;
use App\Http\Controllers\Backend\Transport\CheckPointController;
use App\Http\Controllers\Backend\Transport\DriverController;
use App\Http\Controllers\Backend\Transport\TruckLoadController;
use App\Http\Controllers\Backend\Transport\VehicleController;
use App\Http\Controllers\Backend\TransportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect(route('auth.login'));
})->name('home');


Route::prefix('backend')->group(function () {
    /**
     * Authentication Route
     */
    Route::name('auth.')->group(function () {

        Route::view('/privacy-terms', 'auth::terms')->name('terms');

        Route::get('/login', [AuthenticatedSessionController::class, 'create'])
            ->middleware('guest')
            ->name('login');

        Route::post('/login', [AuthenticatedSessionController::class, 'store'])
            ->middleware('guest');

        if (config('auth.allow_register')):
            Route::get('/register', [RegisteredUserController::class, 'create'])
                ->middleware('guest')
                ->name('register');

            Route::post('/register', [RegisteredUserController::class, 'store'])
                ->middleware('guest');
        endif;

        if (config('auth.allow_password_reset')):
            Route::get('/forgot-password', [PasswordResetController::class, 'create'])
                ->middleware('guest')
                ->name('password.request');
        endif;

        Route::post('/forgot-password', [PasswordResetController::class, 'store'])
            ->middleware('guest')
            ->name('password.email');

        Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])
            ->middleware('guest')
            ->name('password.reset');

        Route::post('/reset-password', [PasswordResetController::class, 'update'])
            ->middleware('guest')
            ->name('password.update');

        Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
            ->middleware('auth')
            ->name('verification.notice');

        Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
            ->middleware(['auth', 'signed', 'throttle:6,1'])
            ->name('verification.verify');

        Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware(['auth', 'throttle:6,1'])
            ->name('verification.send');

        Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
            ->middleware('auth')
            ->name('password.confirm');

        Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
            ->middleware('auth');

        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
            ->middleware('auth')
            ->name('logout');
    });

    /**
     * Admin Panel/ Backend Route
     */
    Route::get('/', function () {
        return redirect(\route('backend.dashboard'));
    })->name('backend');

    Route::middleware(['auth'])->name('backend.')->group(function () {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');

        //Common Operations
        Route::prefix('common')->name('common.')->group(function () {
            Route::get('delete/{route}/{id}', ModelSoftDeleteController::class)->name('delete');
            Route::get('restore/{route}/{id}', ModelRestoreController::class)->name('restore');
            Route::get('enabled', ModelEnabledController::class)->name('enabled');
            //Notification
            Route::resource('notifications', NotificationController::class);

            //Address Book
            Route::resource('address-books', AddressBookController::class)->where(['address-book' => '([0-9]+)']);
            Route::prefix('address-books')->name('address-books.')->group(function () {
                Route::patch('{address-book}/restore', [AddressBookController::class, 'restore'])->name('restore');
                Route::get('export', [AddressBookController::class, 'export'])->name('export');
                Route::get('import', [AddressBookController::class, 'import'])->name('import');
                Route::post('import', [AddressBookController::class, 'importBulk']);
                Route::post('print', [AddressBookController::class, 'print'])->name('print');
            });
        });

        //Shipment
        Route::get('shipment', ShipmentController::class)->name('shipment');
        Route::prefix('shipment')->name('shipment.')->group(function () {
            //Customer
            Route::resource('customers', CustomerController::class)->where(['customer' => '([0-9]+)']);
            Route::prefix('customers')->name('customers.')->group(function () {
                Route::patch('{customer}/restore', [CustomerController::class, 'restore'])->name('restore');
                Route::get('export', [CustomerController::class, 'export'])->name('export');
                Route::get('import', [CustomerController::class, 'import'])->name('import');
                Route::post('import', [CustomerController::class, 'importBulk']);
                Route::post('print', [CustomerController::class, 'print'])->name('print');
                Route::get('ajax', [CustomerController::class, 'ajax'])->name('ajax');
            });

            //Item
            Route::resource('items', ItemController::class)->where(['item' => '([0-9]+)']);
            Route::prefix('items')->name('items.')->group(function () {
                Route::patch('{item}/restore', [ItemController::class, 'restore'])->name('restore');
                Route::get('/export', [ItemController::class, 'export'])->name('export');
                Route::get('/import', [ItemController::class, 'import'])->name('import');
                Route::post('/import', [ItemController::class, 'importBulk']);
                Route::post('/print', [ItemController::class, 'print'])->name('print');
                Route::get('ajax', [ItemController::class, 'ajax'])->name('ajax');
            });

            //Invoice
            Route::resource('invoices', InvoiceController::class)->where(['invoice' => '([0-9]+)']);
            Route::prefix('invoices')->name('invoices.')->group(function () {
                Route::patch('{invoice}/restore', [InvoiceController::class, 'restore'])->name('restore');
                Route::get('permission', [InvoiceController::class, 'permission'])->name('permission');
                Route::get('export', [InvoiceController::class, 'export'])->name('export');
                Route::get('import', [InvoiceController::class, 'import'])->name('import');
                Route::post('import', [InvoiceController::class, 'importBulk']);
                Route::post('print', [InvoiceController::class, 'print'])->name('print');
                Route::get('ajax', [InvoiceController::class, 'ajax'])->name('ajax')->middleware('ajax');
            });

            //Transaction
            Route::resource('transactions', TransactionController::class)->where(['transaction' => '([0-9]+)']);
            Route::prefix('transactions')->name('transactions.')->group(function () {
                Route::patch('{transaction}/restore', [TransactionController::class, 'restore'])->name('restore');
                Route::get('export', [TransactionController::class, 'export'])->name('export');
                Route::get('import', [TransactionController::class, 'import'])->name('import');
                Route::post('print', [TransactionController::class, 'print'])->name('print');
                Route::get('ajax', [TransactionController::class, 'ajax'])->name('ajax')->middleware('ajax');
            });
        });

        //Transport
        Route::get('transport', TransportController::class)->name('transport');
        Route::prefix('transport')->name('transport.')->group(function () {
            //Vehicle
            Route::resource('vehicles', VehicleController::class)->where(['vehicle' => '([0-9]+)']);
            Route::prefix('vehicles')->name('vehicles.')->group(function () {
                Route::patch('{vehicle}/restore', [VehicleController::class, 'restore'])->name('restore');
                Route::get('export', [VehicleController::class, 'export'])->name('export');
                Route::get('import', [VehicleController::class, 'import'])->name('import');
                Route::post('import', [VehicleController::class, 'importBulk']);
                Route::post('print', [VehicleController::class, 'print'])->name('print');
            });

            //Driver
            Route::resource('drivers', DriverController::class)->where(['driver' => '([0-9]+)']);
            Route::prefix('drivers')->name('drivers.')->group(function () {
                Route::patch('{driver}/restore', [DriverController::class, 'restore'])->name('restore');
                Route::get('/export', [DriverController::class, 'export'])->name('export');
                Route::get('/import', [DriverController::class, 'import'])->name('import');
                Route::post('/import', [DriverController::class, 'importBulk']);
                Route::post('/print', [DriverController::class, 'print'])->name('print');
            });

            //Check-Point
            Route::resource('check-points', CheckPointController::class)->where(['check-point' => '([0-9]+)']);
            Route::prefix('check-points')->name('check-points.')->group(function () {
                Route::patch('{check-point}/restore', [CheckPointController::class, 'restore'])->name('restore');
                Route::get('permission', [CheckPointController::class, 'permission'])->name('permission');
                Route::get('export', [CheckPointController::class, 'export'])->name('export');
                Route::get('import', [CheckPointController::class, 'import'])->name('import');
                Route::post('import', [CheckPointController::class, 'importBulk']);
                Route::post('print', [CheckPointController::class, 'print'])->name('print');
                Route::get('ajax', [CheckPointController::class, 'ajax'])->name('ajax')->middleware('ajax');
            });

            //Track Loads
            Route::resource('truck-loads', TruckLoadController::class)->where(['truck-load' => '([0-9]+)']);
            Route::prefix('truck-loads')->name('truck-loads.')->group(function () {
                Route::patch('{truck-load}/restore', [TruckLoadController::class, 'restore'])->name('restore');
                Route::get('export', [TruckLoadController::class, 'export'])->name('export');
                Route::get('import', [TruckLoadController::class, 'import'])->name('import');
                Route::post('import', [TruckLoadController::class, 'importBulk']);
                Route::post('print', [TruckLoadController::class, 'print'])->name('print');
                Route::get('ajax', [TruckLoadController::class, 'ajax'])->name('ajax')->middleware('ajax');
            });
        });

        //Organization
        Route::get('organization', OrganizationController::class)->name('organization');
        Route::prefix('organization')->name('organization.')->group(function () {
            //Branch
            Route::resource('branches', BranchController::class)->where(['branch' => '([0-9]+)']);
            Route::prefix('branches')->name('branches.')->group(function () {
                Route::patch('{branch}/restore', [BranchController::class, 'restore'])->name('restore');
                Route::get('export', [BranchController::class, 'export'])->name('export');
                Route::get('import', [BranchController::class, 'import'])->name('import');
                Route::post('import', [BranchController::class, 'importBulk']);
                Route::post('print', [BranchController::class, 'print'])->name('print');
            });

            //Employee
            Route::resource('employees', EmployeeController::class)->where(['employee' => '([0-9]+)']);
            Route::prefix('employees')->name('employees.')->group(function () {
                Route::patch('{employee}/restore', [EmployeeController::class, 'restore'])->name('restore');
                Route::get('/export', [EmployeeController::class, 'export'])->name('export');
                Route::get('/import', [EmployeeController::class, 'import'])->name('import');
                Route::post('/import', [EmployeeController::class, 'importBulk']);
                Route::post('/print', [EmployeeController::class, 'print'])->name('print');
            });
        });

        //Setting
        Route::get('settings', SettingController::class)->name('settings');
        Route::prefix('settings')->name('settings.')->group(function () {
            //User
            Route::resource('users', UserController::class)->where(['user' => '([0-9]+)']);
            Route::prefix('users')->name('users.')->group(function () {
                Route::patch('{user}/restore', [UserController::class, 'restore'])->name('restore');
                Route::get('export', [UserController::class, 'export'])->name('export');
                Route::get('import', [UserController::class, 'import'])->name('import');
                Route::post('import', [UserController::class, 'importBulk']);
                Route::post('print', [UserController::class, 'print'])->name('print');
            });

            //Permission
            Route::resource('permissions', PermissionController::class)->where(['permission' => '([0-9]+)']);
            Route::prefix('permissions')->name('permissions.')->group(function () {
                Route::patch('{permission}/restore', [PermissionController::class, 'restore'])->name('restore');
                Route::get('/export', [PermissionController::class, 'export'])->name('export');
                Route::get('/import', [PermissionController::class, 'import'])->name('import');
                Route::post('/import', [PermissionController::class, 'importBulk']);
                Route::post('/print', [PermissionController::class, 'print'])->name('print');
            });

            //Role
            Route::resource('roles', RoleController::class)->where(['role' => '([0-9]+)']);
            Route::prefix('roles')->name('roles.')->group(function () {
                Route::patch('{role}/restore', [RoleController::class, 'restore'])->name('restore');
                Route::get('permission', [RoleController::class, 'permission'])->name('permission');
                Route::get('export', [RoleController::class, 'export'])->name('export');
                Route::get('import', [RoleController::class, 'import'])->name('import');
                Route::post('import', [RoleController::class, 'importBulk']);
                Route::post('print', [RoleController::class, 'print'])->name('print');
                Route::get('ajax', [RoleController::class, 'ajax'])->name('ajax')->middleware('ajax');
            });

            //Catalogs
            Route::resource('catalogs', CatalogController::class)->where(['catalog' => '([0-9]+)']);
            Route::prefix('catalogs')->name('catalogs.')->group(function () {
                Route::patch('{catalog}/restore', [CatalogController::class, 'restore'])->name('restore');
                Route::get('export', [CatalogController::class, 'export'])->name('export');
                Route::get('import', [CatalogController::class, 'import'])->name('import');
                Route::post('print', [CatalogController::class, 'print'])->name('print');
                Route::get('ajax', [CatalogController::class, 'ajax'])->name('ajax')->middleware('ajax');
            });

            //Occupation
            Route::resource('occupations', OccupationController::class)->where(['occupation' => '([0-9]+)']);
            Route::prefix('occupations')->name('occupations.')->group(function () {
                Route::patch('{occupation}/restore', [OccupationController::class, 'restore'])->name('restore');
                Route::get('export', [OccupationController::class, 'export'])->name('export');
                Route::get('import', [OccupationController::class, 'import'])->name('import');
                Route::post('import', [OccupationController::class, 'importBulk']);
                Route::post('print', [OccupationController::class, 'print'])->name('print');
                Route::get('ajax', [OccupationController::class, 'ajax'])->name('ajax')->middleware('ajax');
            });

            //Country
            Route::resource('countries', CountryController::class)->where(['country' => '([0-9]+)']);
            Route::prefix('countries')->name('countries.')->group(function () {
                Route::patch('{country}/restore', [CountryController::class, 'restore'])->name('restore');
                Route::get('export', [CountryController::class, 'export'])->name('export');
                Route::get('import', [CountryController::class, 'import'])->name('import');
                Route::post('import', [CountryController::class, 'importBulk']);
                Route::post('print', [CountryController::class, 'print'])->name('print');
            });

            //State
            Route::resource('states', StateController::class)->where(['state' => '([0-9]+)']);
            Route::prefix('states')->name('states.')->group(function () {
                Route::patch('{state}/restore', [StateController::class, 'restore'])->name('restore');
                Route::get('/export', [StateController::class, 'export'])->name('export');
                Route::get('/import', [StateController::class, 'import'])->name('import');
                Route::post('/import', [StateController::class, 'importBulk']);
                Route::post('/print', [StateController::class, 'print'])->name('print');
                Route::get('ajax', [StateController::class, 'ajax'])->name('ajax')->middleware('ajax');
            });

            //City
            Route::resource('cities', CityController::class)->where(['city' => '([0-9]+)']);
            Route::prefix('cities')->name('cities.')->group(function () {
                Route::patch('{city}/restore', [CityController::class, 'restore'])->name('restore');
                Route::get('export', [CityController::class, 'export'])->name('export');
                Route::get('import', [CityController::class, 'import'])->name('import');
                Route::post('import', [CityController::class, 'importBulk']);
                Route::post('print', [CityController::class, 'print'])->name('print');
                Route::get('ajax', [CityController::class, 'ajax'])->name('ajax')->middleware('ajax');
            });

        });
    });
});


