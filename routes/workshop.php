<?php

use App\Http\Controllers\auth\AdminAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Verify\Workshop\VerifyRequestsController;
use App\Http\Controllers\orders\WorkshopOrderController;

/**
 * Auth routes
 */

Route::group(['prefix' => 'cms'],
    function () {
        /*********** Public routes  ***********/

        /*********** Protected routes  ***********/
        Route::group(['middleware' => ['auth:api', 'permissions']],
            function () {

                Route::group(['prefix' => 'workshop'],
                    function () {
                        // orders
                        Route::get('/orders', [WorkshopOrderController::class, 'getImmediatelyOrders'])->name('cms.workshop.orders.get');
                        Route::get('/{workshop}/orders', [WorkshopOrderController::class, 'showWorkshopOrders'])
                            ->where('workshop', '[0-9]+')
                            ->name('cms.workshop.orders.get.show');
                        Route::get('/orders/{id}', [WorkshopOrderController::class, 'getWorkshopOrders'])
                            ->where('id', '[0-9]+')
                            ->name('cms.workshop.orders.show');

                        Route::get('/preorders', [WorkshopOrderController::class, 'getPreOrders'])->name('cms.workshop.preorders.get');
                        Route::get('/{workshop}/preorders', [WorkshopOrderController::class, 'showWorkshopPreorders'])
                            ->where('workshop', '[0-9]+')
                            ->name('cms.workshop.preorders.get.show');
                        Route::get('/preorders/{id}', [WorkshopOrderController::class, 'showPreOrders'])
                            ->where('preorder', '[0-9]+')
                            ->name('cms.workshop.preorders.show');

                        //verify workshop
                        Route::group(['prefix' => 'request'],
                            function () {

                                Route::get('/get', [VerifyRequestsController::class, 'getVerifyRequest'])->name('cms.workshop.requsets.get');

                                Route::group(['middleware' => ['verify.request.existence']],
                                    function () {

                                        Route::get('/{id}/files', [VerifyRequestsController::class, 'getFileRequest'])
                                            ->name('cms.workshop.request.files')
                                            ->where(['id' => '[0-9]+']);

                                        Route::delete('/{id}/accept', [VerifyRequestsController::class, 'acceptRequest'])
                                            ->name('cms.workshop.request.accept')
                                            ->where(['id' => '[0-9]+']);

                                        Route::delete('/{id}/reject', [VerifyRequestsController::class, 'rejectRequest'])
                                            ->name('cms.workshop.request.reject')
                                            ->where(['id' => '[0-9]+']);

                                    });

                            });
                    });
            });
    });
