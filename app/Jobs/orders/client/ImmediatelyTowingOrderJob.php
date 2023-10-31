<?php

namespace App\Jobs\orders\client;

use App\Models\TowingOrder;
use App\Models\WorkshopOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
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
        try {
            $fields = $this->data;
            TowingOrder::create([
                'stage' => 0,
                'ticket_id' => $fields["id"],
                'user_email' => $fields["user_email"],
                'towing_email' => $fields["towing_email"],
                'user_latitude' => $fields["user_latitude"],
                'user_longitude' => $fields["user_longitude"],
            ]);
        } catch (\Exception $exception) {
            echo $exception;
        }
    }
}
