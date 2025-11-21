<?php

use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\BrandsController;
use App\Http\Controllers\Dashboard\CompressorTypeController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\EquipmentsController;
use App\Http\Controllers\Dashboard\GroupController;
use App\Http\Controllers\Dashboard\InvoiceController;
use App\Http\Controllers\Dashboard\InvoicesAmerController;
use App\Http\Controllers\Dashboard\NotificationController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\ProjectAmerController;
use App\Http\Controllers\Dashboard\ProjectCapacityController;
use App\Http\Controllers\Dashboard\ProjectController;
use App\Http\Controllers\Dashboard\ProjectEquipmentController;
use App\Http\Controllers\Dashboard\ProjectInvoicesController;
use App\Http\Controllers\Dashboard\ProjectItemController;
use App\Http\Controllers\Dashboard\ProjectModelController;
use App\Http\Controllers\Dashboard\ProjectTeamController;
use App\Http\Controllers\Dashboard\ProjectTypeController;
use App\Http\Controllers\Dashboard\ProjectVoltController;
use App\Http\Controllers\Dashboard\RolesController;
use App\Http\Controllers\Dashboard\SectionItemsController;
use App\Http\Controllers\Dashboard\SectionsController;
use App\Http\Controllers\Dashboard\StoresController;
use App\Http\Controllers\Dashboard\TeamsController;
use App\Http\Controllers\Dashboard\UserController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;






/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('view_report', function () {
    return view('dashboard.report_pdf.invoices');
});

require __DIR__ . '/auth.php';

Route::group([
    'middleware' => ['auth']
], function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');


    Route::prefix('admins')->controller(AdminController::class)->group(function () {
        Route::get('/index', 'index')->name('admin.index');
        Route::get('{admin}/show', 'show')->name('admin.show');
        Route::get('/create', 'create')->name('admin.create');
        Route::post('/store', 'store')->name('admin.store');
        Route::get('{admin}/edit', 'edit')->name('admin.edit');
        Route::post('{admin}/edit_status', 'edit_status')->name('admin.edit_status');
        Route::post('{admin}/update', 'update')->name('admin.update');
        Route::post('{admin}/delete', 'delete')->name('admin.delete');
        // assign_role
        Route::post('{admin}/assign_role', 'assign_role')->name('admin.assign_role');
    });

    Route::prefix('users')->controller(UserController::class)->group(function () {
        Route::get('/index', 'index')->name('user.index');
        Route::get('{user}/show', 'show')->name('user.show');
        Route::get('/create', 'create')->name('user.create');
        Route::post('/store', 'store')->name('user.store');
        Route::get('{user}/edit', 'edit')->name('user.edit');
        Route::post('{user}/edit_status', 'edit_status')->name('user.edit_status');
        Route::post('{user}/update', 'update')->name('user.update');
        Route::post('{user}/delete', 'delete')->name('user.delete');
        // assign_role
        Route::post('{user}/assign_role', 'assign_role')->name('user.assign_role');
    });

    // profile
    Route::get('{user}/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::post('{user}/update_profile', [ProfileController::class, 'update_profile'])->name('update_profile');
    Route::post('{user}/update_profile_photo', [ProfileController::class, 'update_profile_photo'])->name('update_profile_photo');

    Route::prefix('groups')->controller(GroupController::class)->group(function () {
        Route::get('/index', 'index')->name('groups.index');
        Route::get('{group}/show', 'show')->name('groups.show');
        Route::get('/create', 'create')->name('groups.create');
        Route::post('/store', 'store')->name('groups.store');
        Route::get('{group}/edit', 'edit')->name('groups.edit');
        Route::post('{group}/update', 'update')->name('groups.update');
        Route::post('{group}/destroy', 'destroy')->name('groups.destroy');
    });

    Route::prefix('roles')->controller(RolesController::class)->group(function () {
        Route::get('/index', 'index')->name('roles.index');
        Route::get('{role}/show', 'show')->name('roles.show');
        Route::get('/create', 'create')->name('roles.create');
        Route::post('/store', 'store')->name('roles.store');
        Route::get('{role}/edit', 'edit')->name('roles.edit');
        Route::post('{role}/update', 'update')->name('roles.update');
        Route::post('{role}/delete', 'delete')->name('roles.delete');
    });


    Route::prefix('sections')->controller(SectionsController::class)->group(function () {
        Route::get('/index', 'index')->name('sections.index');
        Route::get('{section}/show', 'show')->name('sections.show');
        Route::get('/create', 'create')->name('sections.create');
        Route::post('/store', 'store')->name('sections.store');
        Route::post('/ajax-store', 'ajaxStore')->name('sections.ajax-store');
        Route::get('{section}/edit', 'edit')->name('sections.edit');
        Route::post('{section}/update', 'update')->name('sections.update');
        Route::post('{section}/destroy', 'destroy')->name('sections.destroy');
    });

    Route::prefix('section_items')->controller(SectionItemsController::class)->group(function () {
        Route::get('/index', 'index')->name('section_items.index');
        Route::get('{section_item}/show', 'show')->name('section_items.show');
        Route::get('/create', 'create')->name('section_items.create');
        Route::post('/store', 'store')->name('section_items.store');
        Route::post('/ajax-store', 'ajaxStore')->name('section_items.ajax-store');
        Route::get('{section_item}/edit', 'edit')->name('section_items.edit');
        Route::post('{section_item}/update', 'update')->name('section_items.update');
        Route::post('{section_item}/destroy', 'destroy')->name('section_items.destroy');
    });

    Route::prefix('equipments')->controller(EquipmentsController::class)->group(function () {
        Route::get('/index', 'index')->name('equipments.index');
        Route::get('{equipment}/show', 'show')->name('equipments.show');
        Route::get('/create', 'create')->name('equipments.create');
        Route::post('/store', 'store')->name('equipments.store');
        Route::get('{equipment}/edit', 'edit')->name('equipments.edit');
        Route::post('{equipment}/update', 'update')->name('equipments.update');
        Route::post('{equipment}/destroy', 'destroy')->name('equipments.destroy');
    });

    Route::prefix('projects')->controller(ProjectController::class)->group(function () {
        Route::get('/index', 'index')->name('projects.index');
        Route::get('{project}/show', 'show')->name('projects.show');
        Route::get('/create', 'create')->name('projects.create');
        Route::post('/store', 'store')->name('projects.store');
        Route::get('{project}/edit', 'edit')->name('projects.edit');
        Route::post('{project}/update', 'update')->name('projects.update');
        Route::post('{project}/destroy', 'destroy')->name('projects.destroy');
    });

    Route::prefix('project-items')->controller(ProjectItemController::class)->group(function () {
        Route::get('/index', 'index')->name('project-items.index');
        Route::get('{project_item}/show', 'show')->name('project-items.show');
        Route::get('/create', 'create')->name('project-items.create');
        Route::post('/store', 'store')->name('project-items.store');
        Route::get('{project_item}/edit', 'edit')->name('project-items.edit');
        Route::post('{project_item}/update', 'update')->name('project-items.update');
        Route::post('{project_item}/destroy', 'destroy')->name('project-items.destroy');
        Route::post('{project_item}/update-received-qty', 'updateReceivedQty')->name('project-items.update-received-qty');
        Route::post('{project_item}/update-executed-qty', 'updateExecutedQty')->name('project-items.update-executed-qty');
    });

    Route::prefix('project-teams')->controller(ProjectTeamController::class)->group(function () {
        Route::get('/index', 'index')->name('project-teams.index');
        Route::get('{project_team}/show', 'show')->name('project-teams.show');
        Route::get('/create', 'create')->name('project-teams.create');
        Route::post('/store', 'store')->name('project-teams.store');
        Route::get('{project_team}/edit', 'edit')->name('project-teams.edit');
        Route::post('{project_team}/update', 'update')->name('project-teams.update');
        Route::post('{project_team}/destroy', 'destroy')->name('project-teams.destroy');
        Route::post('/bulk-assign', 'bulkAssign')->name('project-teams.bulk-assign');
        Route::post('{project_team}/transfer', 'transfer')->name('project-teams.transfer');
        Route::get('/get-leader', 'getLeader')->name('project-teams.get-leader');
    });

    Route::prefix('project-equipments')->controller(ProjectEquipmentController::class)->group(function () {
        Route::get('/index', 'index')->name('project-equipments.index');
        Route::get('{project_equipment}/show', 'show')->name('project-equipments.show');
        Route::get('/create', 'create')->name('project-equipments.create');
        Route::post('/store', 'store')->name('project-equipments.store');
        Route::get('{project_equipment}/edit', 'edit')->name('project-equipments.edit');
        Route::post('{project_equipment}/update', 'update')->name('project-equipments.update');
        Route::post('{project_equipment}/destroy', 'destroy')->name('project-equipments.destroy');
        Route::post('/bulk-assign', 'bulkAssign')->name('project-equipments.bulk-assign');
        Route::post('{project_equipment}/update-status', 'updateStatus')->name('project-equipments.update-status');
        Route::get('available-equipment/{project_id?}', 'getAvailableEquipment')->name('project-equipments.available-equipment');
    });

     // Invoices routes
    Route::prefix('invoices')->controller(ProjectInvoicesController::class)->group(function () {
        Route::get('/index', 'index')->name('invoices.index');
        Route::get('/create', 'create')->name('invoices.create');
        Route::post('/store', 'store')->name('invoices.store');
        Route::get('{invoice}/show', 'show')->name('invoices.show');
        Route::get('{invoice}/edit', 'edit')->name('invoices.edit');
        Route::post('{invoice}/update', 'update')->name('invoices.update');
        Route::post('{invoice}/destroy', 'destroy')->name('invoices.destroy');
        Route::post('{invoice}/approve', 'approve')->name('invoices.approve');
        Route::post('{invoice}/reject', 'reject')->name('invoices.reject');
    });

    Route::prefix('notifications')->controller(NotificationController::class)->group(function () {
        Route::get('/', 'index')->name('notifications.index');
        Route::get('/{notification}/show', 'show')->name('notifications.show');
        Route::get('/mark-all-as-read', 'markAllAsRead')->name('notifications.markAllAsRead');
        Route::delete('/{notification}', 'destroy')->name('notifications.destroy');
    });


    // Brands routes
    Route::prefix('brands')->controller(BrandsController::class)->group(function () {
        Route::get('/index', 'index')->name('brands.index');
        Route::get('/create', 'create')->name('brands.create');
        Route::post('/store', 'store')->name('brands.store');
        Route::get('{brand}/show', 'show')->name('brands.show');
        Route::get('{brand}/edit', 'edit')->name('brands.edit');
        Route::post('{brand}/update', 'update')->name('brands.update');
        Route::post('{brand}/destroy', 'destroy')->name('brands.destroy');
    });
    
    // stores routes
    Route::prefix('stores')->controller(StoresController::class)->group(function () {
        Route::get('/index', 'index')->name('stores.index');
        Route::get('/create', 'create')->name('stores.create');
        Route::post('/store', 'store')->name('stores.store');
        Route::get('{store}/show', 'show')->name('stores.show');
        Route::get('{store}/edit', 'edit')->name('stores.edit');
        Route::post('{store}/update', 'update')->name('stores.update');
        Route::post('{store}/destroy', 'destroy')->name('stores.destroy');
    });

    Route::prefix('project_amers')->controller(ProjectAmerController::class)->group(function () {
        Route::get('/index', 'index')->name('project_amers.index');
        Route::get('{project_amer}/show', 'show')->name('project_amers.show');
        Route::get('/create', 'create')->name('project_amers.create');
        Route::post('/store', 'store')->name('project_amers.store');
        Route::get('{project_amer}/edit', 'edit')->name('project_amers.edit');
        Route::post('{project_amer}/update', 'update')->name('project_amers.update');
        Route::post('{project_amer}/destroy', 'destroy')->name('project_amers.destroy');
        Route::get('{project_amer}/download-service-completion', 'downloadServiceCompletionPDF')->name('project_amers.download_service_completion');
    });

    // Project Types routes
    Route::prefix('project_types')->controller(ProjectTypeController::class)->group(function () {
        Route::get('/index', 'index')->name('project_types.index');
        Route::get('/maintenance/index', 'maintenanceIndex')->name('maintenance_types.index');
        Route::get('{type}/show', 'show')->name('project_types.show');
        Route::get('/create', 'create')->name('project_types.create');
        Route::get('/maintenance/create', 'maintenanceCreate')->name('maintenance_types.create');
        Route::post('/store', 'store')->name('project_types.store');
        Route::get('{type}/edit', 'edit')->name('project_types.edit');
        Route::get('/maintenance/{type}/edit', 'maintenanceEdit')->name('maintenance_types.edit');
        Route::match(['post','put'],'{type}/update', 'update')->name('project_types.update');
        Route::match(['post','delete'],'{type}/destroy', 'destroy')->name('project_types.destroy');
    });

    // Project Capacities routes
    Route::prefix('project_capacities')->controller(ProjectCapacityController::class)->group(function () {
        Route::get('/index', 'index')->name('project_capacities.index');
        Route::get('{capacity}/show', 'show')->name('project_capacities.show');
        Route::get('/create', 'create')->name('project_capacities.create');
        Route::post('/store', 'store')->name('project_capacities.store');
        Route::get('{capacity}/edit', 'edit')->name('project_capacities.edit');
        Route::match(['post','put'],'{capacity}/update', 'update')->name('project_capacities.update');
        Route::match(['post','delete'],'{capacity}/destroy', 'destroy')->name('project_capacities.destroy');
    });

    // Project Volts routes
    Route::prefix('project_volts')->controller(ProjectVoltController::class)->group(function () {
        Route::get('/index', 'index')->name('project_volts.index');
        Route::get('{volt}/show', 'show')->name('project_volts.show');
        Route::get('/create', 'create')->name('project_volts.create');
        Route::post('/store', 'store')->name('project_volts.store');
        Route::get('{volt}/edit', 'edit')->name('project_volts.edit');
        Route::match(['post','put'],'{volt}/update', 'update')->name('project_volts.update');
        Route::match(['post','delete'],'{volt}/destroy', 'destroy')->name('project_volts.destroy');
    });

    // Project Models routes
    Route::prefix('project_models')->controller(ProjectModelController::class)->group(function () {
        Route::get('/index', 'index')->name('project_models.index');
        Route::get('{model}/show', 'show')->name('project_models.show');
        Route::get('/create', 'create')->name('project_models.create');
        Route::post('/store', 'store')->name('project_models.store');
        Route::get('{model}/edit', 'edit')->name('project_models.edit');
        Route::match(['post','put'],'{model}/update', 'update')->name('project_models.update');
        Route::match(['post','delete'],'{model}/destroy', 'destroy')->name('project_models.destroy');
    });

     // Invoices Amer routes
    Route::prefix('invoices_amer')->controller(InvoicesAmerController::class)->group(function () {
        Route::get('/index', 'index')->name('invoices_amer.index');
        Route::get('/create', 'create')->name('invoices_amer.create');
        Route::post('/store', 'store')->name('invoices_amer.store');
        Route::get('{invoice_amer}/show', 'show')->name('invoices_amer.show');
        Route::get('{invoice_amer}/edit', 'edit')->name('invoices_amer.edit');
        Route::post('{invoice_amer}/update', 'update')->name('invoices_amer.update');
        Route::post('{invoice_amer}/destroy', 'destroy')->name('invoices_amer.destroy');
        Route::post('{invoice_amer}/approve', 'approve')->name('invoices_amer.approve');
        Route::post('{invoice_amer}/reject', 'reject')->name('invoices_amer.reject');
    });

    // Route to fetch chart data
    Route::get('/charts-data', [DashboardController::class, 'getChartsData'])->name('charts.data');
});
