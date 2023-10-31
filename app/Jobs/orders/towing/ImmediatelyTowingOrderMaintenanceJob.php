<?php

namespace App\Jobs\orders\towing;

use App\Models\TowingOrder;
use App\Models\WorkshopOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImmediatelyTowingOrderMaintenanceJob implements ShouldQueue
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
//            $order->has_on_road = $fields["onRoad"];
            $order->stage = 2;
            $order->save();
        } catch (\Exception $exception) {
            echo $exception;
        }
    }
}
