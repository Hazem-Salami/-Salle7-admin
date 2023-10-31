<?php

namespace App\Jobs\orders\client;

use App\Models\Constant;
use App\Models\TowingOrder;
use App\Models\User;
use App\Models\WorkshopOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImmediatelyTowingOrderPayJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;

    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $fields = $this->data;
        try {
            $order = TowingOrder::where('ticket_id', $fields["id"])->first();
            $user = User::where('email', $fields["client_email"])->first();
            $towing = User::where('email', $fields["towing_email"])->first();

            $preAmountUser = $user->wallet->amount;
            $preAmountTowing = $towing->wallet->amount;

            $user->wallet->amount -= $fields["price"];
            $towing->wallet->amount += $fields["price"];

            $user->wallet->save();
            $towing->wallet->save();

            $newAmountUser = $user->wallet->amount;
            $newAmountTowing = $towing->wallet->amount;

            $user->wallet->charges()->create([
                'charge' => $fields["price"],
                'pre_mount' => $preAmountUser,
                'new_amount' => $newAmountUser,
                'type' => 1,
            ]);
            $towing->wallet->charges()->create([
                'charge' => $fields["price"],
                'pre_mount' => $preAmountTowing,
                'new_amount' => $newAmountTowing,
            ]);

            $order->delete();
        } catch (\Exception $exception) {
            echo $exception;
        }
    }
}
