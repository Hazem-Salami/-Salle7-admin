<?php

use App\Http\Controllers\Verify\Storehouse\VerifyRequestsController;
use Illuminate\Support\Facades\Route;

/**
 * Auth routes
 */

Route::group(['prefix' => 'cms'],
    function () {
        /*********** Public routes  ***********/

        /*********** Protected routes  ***********/
        Route::group(['middleware' => ['auth:api'], 'permissions'],
            function () {

                Route::group(['prefix' => 'storehouse'],
                    function () {
                        //verify storehouse
                        Route::group(['prefix' => 'request'],
                            function () {

                                Route::get('/get', [VerifyRequestsController::class, 'getVerifyRequest'])->name('cms.storehouse.requsets.get');

                                Route::group(['middleware' => ['verify.request.existence']],
                                    function () {

                                        Route::get('/{id}/files', [VerifyRequestsController::class, 'getFileRequest'])
                                            ->name('cms.storehouse.request.files')
                                            ->where(['id' => '[0-9]+']);

                                        Route::delete('/{id}/accept', [VerifyRequestsController::class, 'acceptRequest'])
                                            ->name('cms.storehouse.request.accept')
                                            ->where(['id' => '[0-9]+']);

                                        Route::delete('/{id}/reject', [VerifyRequestsController::class, 'rejectRequest'])
                                            ->name('cms.storehouse.request.reject')
                                            ->where(['id' => '[0-9]+']);

                                    });
                            });
                    });
            });
    });
