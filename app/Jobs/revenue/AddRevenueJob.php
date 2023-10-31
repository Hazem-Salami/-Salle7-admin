<?php

namespace App\Jobs\revenue;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddRevenueJob implements ShouldQueue
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
     *
     * @return void
     */
    public function handle()
    {
        $fields = $this->data;
        try {
            $user = User::where('email', $fields["user_email"])->first();

            $preAmountUser = $user->wallet->amount;

            $user->wallet->amount -= $fields["revenue"];

            $user->revenues()->create([
                'amount' => $fields["revenue"]
            ]);

            $user->wallet->save();

            $newAmountUser = $user->wallet->amount;

            $user->wallet->charges()->create([
                'charge' => $fields["revenue"],
                'pre_mount' => $preAmountUser,
                'new_amount' => $newAmountUser,
                'type' => 1,
            ]);
        } catch (\Exception $exception) {
            echo $exception;
        }
    }
}
