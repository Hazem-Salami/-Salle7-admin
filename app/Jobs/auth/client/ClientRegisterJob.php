<?php

namespace App\Jobs\auth\client;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ClientRegisterJob implements ShouldQueue
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
            User::create([
                'firstname' => $this->data["firstname"],
                'lastname' => $this->data["lastname"],
                'email' => $this->data["email"],
                'phone_number' => $this->data["phone_number"],
            ]);
        } catch (\Exception $exception) {
            echo $exception;
        }
    }
}
