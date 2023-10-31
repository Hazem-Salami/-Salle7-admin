<?php

namespace App\Http\Controllers\StatisticsAndCharts;

use App\Http\Controllers\Controller;
use App\Http\Requests\charts\DateRequest;
use App\Models\User;
use App\Services\StatisticsAndCharts\StatisticsChartsService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StatisticsChartsController extends Controller
{
    /**
     *
     * @var StatisticsChartsService
     */
    protected StatisticsChartsService $statisticsChartsService;

    // singleton pattern, service container
    public function __construct(StatisticsChartsService $statisticsChartsService)
    {
        $this->statisticsChartsService = $statisticsChartsService;
    }

    public function getNumUser(): Response
    {
        return $this->statisticsChartsService->getNumUser();
    }

    public function getRatioNumUser(): Response
    {
        return $this->statisticsChartsService->getRatioNumUser();
    }

    public function getChartNumUser(DateRequest $request): Response
    {
        return $this->statisticsChartsService->getChartNumUser($request);
    }

    public function getRevenues(DateRequest $request): Response
    {
        return $this->statisticsChartsService->getRevenues($request);
    }

    public function getRevenuesByUser(User $user): Response
    {
        return $this->statisticsChartsService->getRevenuesByUser($user);
    }
}
