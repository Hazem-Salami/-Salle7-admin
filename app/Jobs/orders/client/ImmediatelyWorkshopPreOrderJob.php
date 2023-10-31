<?php

namespace App\Jobs\orders\client;

use App\Models\Preorder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImmediatelyWorkshopPreOrderJob implements ShouldQueue
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
            Preorder::create([
                'ticket_id' => $fields["id"],
                'stage' => 0,
                'description' =>  $fields["description"],
                'payment_method' => $fields["payment_method"],
                'address' => $fields["address"],
                'user_email' => $fields["user_email"],
                'workshop_email' => $fields["workshop_email"],
            ]);
        } catch (\Exception $exception) {
            echo $exception;
        }
    }
}
