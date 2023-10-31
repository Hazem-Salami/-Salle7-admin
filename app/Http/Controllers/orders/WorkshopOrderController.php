<?php

namespace App\Http\Controllers\orders;

use App\Http\Controllers\Controller;
use App\Models\Preorder;
use App\Models\Workshop;
use App\Services\Order\WorkshopOrdersService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WorkshopOrderController extends Controller
{
    /**
     * The auth service implementation.
     *
     * @var WorkshopOrdersService
     */
    protected WorkshopOrdersService $workshopOrdersService;

    // singleton pattern, service container
    public function __construct(WorkshopOrdersService $workshopOrdersService)
    {
        $this->workshopOrdersService = $workshopOrdersService;
    }

    /**
     * @return Response
     */
    public function getImmediatelyOrders(): Response
    {
        return $this->workshopOrdersService->getImmediatelyOrders();
    }

    /**
     * @param Workshop $workshop
     * @return Response
     */
    public function showWorkshopPreorders(Workshop $workshop): Response
    {
        return $this->workshopOrdersService->showWorkshopPreorders($workshop);
    }

    /**
     * @param Workshop $workshop
     * @return Response
     */
    public function showWorkshopOrders(Workshop $workshop): Response
    {
        return $this->workshopOrdersService->showWorkshopOrders($workshop);
    }

    /**
     * @param $id
     * @return Response
     */
    public function getWorkshopOrders($id): Response
    {
        return $this->workshopOrdersService->getWorkshopOrders($id);
    }

    /**
     * @return Response
     */
    public function getPreOrders(): Response
    {
        return $this->workshopOrdersService->getPreOrders();
    }

    /**
     * @param $id
     * @return Response
     */
    public function showPreOrders($id): Response
    {
        return $this->workshopOrdersService->showPreOrders($id);
    }
}
