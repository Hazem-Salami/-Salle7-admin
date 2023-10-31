<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\AdminAuthController;
use App\Http\Controllers\auth\UserController;
use App\Http\Controllers\Admins\RolesController;
use App\Http\Controllers\Admins\AdminsController;

/**
 * Auth routes
 */

Route::group(['prefix' => 'cms'],
    function () {
        /*********** Public routes  ***********/
        // auth
        Route::post('/login', [AdminAuthController::class, 'login'])->name('cms.login');

        /*********** Protected routes  ***********/
        Route::group(['middleware' => ['auth:api'], 'permissions'],
            function () {

                // auth
                Route::post('/logout', [AdminAuthController::class, 'logout'])->name('cms.logout');

                // user
                Route::get('/users', [UserController::class, 'getUsersByType'])->name('cms.users.get');

                Route::post('/users/block/{user}', [UserController::class, 'blocking'])
                    ->where('user', '[0-9]+')
                    ->name('cms.users.block');

                Route::get('/users/show/{user}', [UserController::class, 'showUser'])
                    ->where('user', '[0-9]+')
                    ->name('cms.users.show');

                // admins
                Route::controller(AdminsController::class)->group(function () {
                    Route::get('/admins', 'index')->name('cms.admin.get');
                    Route::post('/admins', 'store')->name('cms.admin.store');
                    Route::get('/admins/{admin}', 'show')->where('admin', '[0-9]+')->name('cms.admin.show');
                    Route::post('/admins/{admin}', 'update')->where('admin', '[0-9]+')->name('cms.admin.update');
                    Route::delete('/admins/{admin}', 'delete')->where('admin', '[0-9]+')->name('cms.admin.delete');
                });

                // roles
                Route::controller(RolesController::class)->group(function () {
                    Route::get('/permissions', 'getPermissions')->name('permissions.get');
                    Route::post('/permissions/except', 'getPermissionsExcept')->name('permissions.except.get');
                    Route::get('/roles', 'index')->name('cms.roles.get');
                    Route::post('/roles', 'store')->name('cms.roles.store');
                    Route::get('/roles/{role}', 'show')->where('role', '[0-9]+')->name('cms.roles.show');
                    Route::post('/roles/{role}', 'update')->where('role', '[0-9]+')->name('cms.roles.update');
                    Route::post('/roles/permissions/assignment/{role}', 'rolePermissionAssignment')->where('role', '[0-9]+')->name('cms.roles.assignment.permissions');
                    Route::delete('/roles/{role}', 'delete')->where('role', '[0-9]+')->name('cms.roles.delete');
                });

            });
    });

