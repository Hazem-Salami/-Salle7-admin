<?php

namespace App\Jobs\wallets;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UserWalletJob implements ShouldQueue
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
            $user = User::where("email", $fields["email"])->first();
            if ($user != null)
                if ($user->wallet === null) {
                    $wallet = $user->wallet()->create();
                }
        } catch (\Exception $exception) {
            echo $exception;
        }
    }
}
