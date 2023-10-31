<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\product\ProductController;

/**
 * Auth routes
 */

Route::group(['prefix' => 'cms/product'],
    function () {
        /*********** Public routes  ***********/

        /*********** Protected routes  ***********/
        Route::group(['middleware' => ['auth:api','storehouse.existence'], 'permissions'],
            function () {

                Route::get('/get/{storehouse_id?}', [ProductController::class, 'getProducts'])
                    ->where(['storehouse_id' => '[0-9]+'])
                    ->name('cms.product.get');
            });
    });

