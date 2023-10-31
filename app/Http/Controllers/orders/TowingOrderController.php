<?php

namespace App\Http\Controllers\orders;

use App\Http\Controllers\Controller;
use App\Models\Towing;
use App\Services\Order\TowingOrdersService;
use App\Services\Order\WorkshopOrdersService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TowingOrderController extends Controller
{
    /**
     * The auth service implementation.
     *
     * @var TowingOrdersService
     */
    protected TowingOrdersService $towingOrdersService;

    // singleton pattern, service container
    public function __construct(TowingOrdersService $towingOrdersService)
    {
        $this->towingOrdersService = $towingOrdersService;
    }

    /**
     * @return Response
     */
    public function getAllOrders(): Response
    {
        return $this->towingOrdersService->getAllOrders();
    }

    /**
     * @param Towing $towing
     * @return Response
     */
    public function getTowingOrders(Towing $towing): Response
    {
        return $this->towingOrdersService->getTowingOrders($towing);
    }

    /**
     * @param $id
     * @return Response
     */
    public function showTowingOrder($id): Response
    {
        return $this->towingOrdersService->showTowingOrder($id);
    }
}
