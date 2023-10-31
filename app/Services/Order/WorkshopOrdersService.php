<?php

namespace App\Services\Order;

use App\Models\Admin;
use App\Models\Preorder;
use App\Models\User;
use App\Models\Workshop;
use App\Models\WorkshopOrder;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;

class WorkshopOrdersService extends BaseService
{
    public function getImmediatelyOrders(): Response
    {
        $orders = WorkshopOrder::paginate(\request('size'));
        $orders->getCollection()->transform(function ($item, $key) {
            $user = User::where('email', $item->user_email)->first();
            $workshop = User::where('email', $item->workshop_email)->first();
            return [
                "id" => $item->id,
                "ticket_id" => $item->ticket_id,
                "user_id" => $user->id,
                "workshop_id" => $workshop->id,
                "price" => $item->price,
                "payment_method" => $item->payment_method,
                "stage" => $item->stage,
                "address" => $item->address,
                "user_latitude" => $item->user_latitude,
                "user_longitude" => $item->user_longitude,
                "created_at" => $item->created_at,
                "updated_at" => $item->updated_at,
                "user" => $user,
                "workshop" => $workshop->workshop
            ];
        });
        return $this->customResponse(true, 'workshop orders', $orders);
    }

    public function showWorkshopOrders(Workshop $workshop): Response
    {
        $orders = WorkshopOrder::where("workshop_email", $workshop->user->email)
            ->paginate(\request('size'));
        return $this->customResponse(true, "workshop\'s orders", $orders);
    }

    public function showWorkshopPreorders(Workshop $workshop): Response
    {
        $orders = Preorder::where("workshop_email", $workshop->user->email)
            ->paginate(\request('size'));
        return $this->customResponse(true, "workshop\'s preorders", $orders);
    }

    public function getWorkshopOrders($id): Response
    {
        $order = WorkshopOrder::find($id);
        if ($order == null)
            return $this->customResponse(false, 'model not found', null, 400);
        $order->user = User::where('email', $order->user_email)->first();
        $order->workshop = User::where('email', $order->workshop_email)->first()->workshop;

        return $this->customResponse(true, 'workshop orders details', $order);
    }

    public function getPreOrders(): Response
    {
        $orders = Preorder::paginate(\request('size'));
        $orders->getCollection()->transform(function ($item, $key) {
            $user = User::where('email', $item->user_email)->first();
            $workshop = User::where('email', $item->workshop_email)->first();
            return [
                "id" => $item->id,
                "ticket_id" => $item->ticket_id,
                "price" => $item->price,
                "payment_method" => $item->payment_method,
                "stage" => $item->stage,
                "address" => $item->address,
                "created_at" => $item->created_at,
                "updated_at" => $item->updated_at,
                "user" => $user,
                "workshop" => $workshop->workshop
            ];
        });
        return $this->customResponse(true, 'workshop preorders', $orders);
    }

    public function showPreOrders($id): Response
    {
        $order = Preorder::find($id);
        if ($order == null)
            return $this->customResponse(false, 'model not found', null, 400);
        $order->user = User::where('email', $order->user_email)->first();
        $order->workshop = User::where('email', $order->workshop_email)->first();
        return $this->customResponse(true, 'workshop preorders details', $order);
    }
}
