<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\category\CategoryController;

/**
 * Auth routes
 */

Route::group(['prefix' => 'cms/category'],
    function () {
        /*********** Public routes  ***********/

        /*********** Protected routes  ***********/
        Route::group(['middleware' => 'auth:api'],
            function () {

                Route::post('/create', [CategoryController::class, 'createCategory'])->name('cms.category.create');

                Route::get('/get/{children_id?}/{load_more?}', [CategoryController::class, 'getCategories'])
                    ->where(['children_id' => '[0-9]+'])
                    ->where(['load_more' => '[2-9][0-9]*'])
                    ->name('cms.category.get');

                Route::group(['middleware' => ['category.existence']],
                    function () {

                        Route::post('/{id}/update', [CategoryController::class, 'updateCategory'])
                            ->where(['id' => '[0-9]+'])
                            ->name('cms.category.update');

                        Route::delete('/{id}/delete', [CategoryController::class, 'deleteCategory'])
                            ->where(['id' => '[0-9]+'])
                            ->name('cms.category.delete');

                    });

            });
    });

