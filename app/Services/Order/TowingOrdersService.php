<?php

namespace App\Services\Order;

use App\Models\Admin;
use App\Models\Preorder;
use App\Models\Towing;
use App\Models\TowingOrder;
use App\Models\User;
use App\Models\Workshop;
use App\Models\WorkshopOrder;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;

class TowingOrdersService extends BaseService
{
    public function getAllOrders(): Response
    {
        $orders = TowingOrder::paginate(\request('size'));
        $orders->getCollection()->transform(function ($item, $key) {
            $user = User::where('email', $item->user_email)->first();
            $towing = User::where('email', $item->towing_email)->first();
            return [
                "id" => $item->id,
                "ticket_id" => $item->ticket_id,
                "price" => $item->price,
                "payment_method" => $item->payment_method,
                "stage" => $item->stage,
                "address" => $item->address,
                "user_latitude" => $item->user_latitude,
                "user_longitude" => $item->user_longitude,
                "created_at" => $item->created_at,
                "updated_at" => $item->updated_at,
                "user" => $user,
                "towing" => $towing->towing
            ];
        });
        return $this->customResponse(true, 'towing orders', $orders);
    }

    public function getTowingOrders(Towing $towing): Response
    {
        $orders = TowingOrder::where("towing_email", $towing->user->email)
            ->paginate(\request('size'));
        return $this->customResponse(true, "towing\'s orders", $orders);

    }

    public function showTowingOrder($id): Response
    {
        $order = TowingOrder::find($id);
        if ($order == null)
            return $this->customResponse(false, 'model not found', null, 400);
        $order->user = User::where('email', $order->user_email)->first();
        $order->towing = User::where('email', $order->towing_email)->first()->workshop;

        return $this->customResponse(true, 'towing orders details', $order);
    }
}
