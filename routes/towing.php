<?php

use App\Http\Controllers\auth\AdminAuthController;
use App\Http\Controllers\Verify\Towing\VerifyRequestsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\orders\TowingOrderController;

/**
 * Auth routes
 */

Route::group(['prefix' => 'cms'],
    function () {
        /*********** Public routes  ***********/

        /*********** Protected routes  ***********/
        Route::group(['middleware' => ['auth:api'], 'permissions'],
            function () {

                Route::group(['prefix' => 'towing'],
                    function () {
                        // orders
                        Route::get('/orders', [TowingOrderController::class, 'getAllOrders'])->name('cms.towing.orders.get');
                        Route::get('/orders/{id}', [TowingOrderController::class, 'showTowingOrder'])
                            ->where('id', '[0-9]+')
                            ->name('cms.towing.orders.show');
                        Route::get('/{towing}/orders', [TowingOrderController::class, 'getTowingOrders'])
                            ->where('towing', '[0-9]+')
                            ->name('cms.towing.orders.get.his');

                        //verify workshop
                        Route::group(['prefix' => 'request'],
                            function () {

                                Route::get('/get', [VerifyRequestsController::class, 'getVerifyRequest'])->name('cms.towing.requsets.get');

                                Route::group(['middleware' => ['verify.request.existence']],
                                    function () {

                                        Route::get('/{id}/files', [VerifyRequestsController::class, 'getFileRequest'])
                                            ->name('cms.towing.requset.files')
                                            ->where(['id' => '[0-9]+']);

                                        Route::delete('/{id}/accept', [VerifyRequestsController::class, 'acceptRequest'])
                                            ->name('cms.towing.request.accept')
                                            ->where(['id' => '[0-9]+']);

                                        Route::delete('/{id}/reject', [VerifyRequestsController::class, 'rejectRequest'])
                                            ->name('cms.towing.request.reject')
                                            ->where(['id' => '[0-9]+']);

                                    });
                            });

                    });
            });
    });
