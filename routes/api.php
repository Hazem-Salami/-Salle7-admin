<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Wallet\ChargesController;
use App\Http\Controllers\ComplaintsAndSuggestions\ComplaintsSuggestionsController;
use App\Http\Controllers\StatisticsAndCharts\StatisticsChartsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'cms'],
    function () {
        Route::group(['middleware' => ['auth:api'], 'permissions'],
            function () {

                // user's wallet
                Route::get('/users/wallets/charges/{user}', [ChargesController::class, 'getCharges'])
                    ->where('user', '[0-9]+')
                    ->name('users.wallets.charges.get');

                Route::post('/users/wallets/charges/{user}', [ChargesController::class, 'chargeWallet'])
                    ->where('user', '[0-9]+')
                    ->name('users.wallets.charges.charge');


                //Complaints And Suggestions
                Route::get('/users/complaints', [ComplaintsSuggestionsController::class, 'getComplaints'])->name('users.complaints.get');
                Route::get('/users/suggestions', [ComplaintsSuggestionsController::class, 'getSuggestions'])->name('users.suggestions.get');

                //Statistics And Charts
                Route::get('/users/num', [StatisticsChartsController::class, 'getNumUser'])->name('users.number');
                Route::get('/users/ratio', [StatisticsChartsController::class, 'getRatioNumUser'])->name('users.ratio');
                Route::post('/users/chart', [StatisticsChartsController::class, 'getChartNumUser'])->name('users.chart');
                Route::post('/users/revenues', [StatisticsChartsController::class, 'getRevenues'])->name('users.revenues');
                Route::get('/users/revenues/{user}', [StatisticsChartsController::class, 'getRevenuesByUser'])->name('users.revenuesByUser');
            });
    });
