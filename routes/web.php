<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\GroupController;
use App\Http\Controllers\Dashboard\RolesController;
use App\Http\Controllers\Dashboard\TeamsController;
use App\Http\Controllers\Dashboard\InvoiceController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\ProjectController;
use App\Http\Controllers\Dashboard\SectionsController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\EquipmentsController;
use App\Http\Controllers\Dashboard\ProjectItemController;
use App\Http\Controllers\Dashboard\ProjectTeamController;
use App\Http\Controllers\Dashboard\NotificationController;
use App\Http\Controllers\Dashboard\SectionItemsController;
use App\Http\Controllers\Dashboard\ProjectInvoicesController;
use App\Http\Controllers\Dashboard\ProjectEquipmentController;

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

    // Route to fetch chart data
    Route::get('/charts-data', [DashboardController::class, 'getChartsData'])->name('charts.data');
});
