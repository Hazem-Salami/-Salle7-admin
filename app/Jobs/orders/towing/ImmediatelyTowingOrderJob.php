<?php

namespace App\Jobs\orders\towing;

use App\Models\TowingOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImmediatelyTowingOrderJob implements ShouldQueue
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
            if ($fields["acceptStatus"] == "1") {
                TowingOrder::where('user_email', $fields["user_email"])
                    ->where('ticket_id', '!=', $fields["id"])
                    ->delete();
                $order->stage = 1;
                $order->save();
            } else
                $order->delete();
        } catch (\Exception $exception) {
            echo $exception;
        }
    }
}
