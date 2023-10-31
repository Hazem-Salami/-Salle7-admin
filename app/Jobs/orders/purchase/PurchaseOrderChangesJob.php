<?php

namespace App\Jobs\orders\purchase;

use App\Models\Product;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PurchaseOrderChangesJob implements ShouldQueue
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
        try {

            foreach ($this->data as $response){

                $user = User::where('email', $response['email'])->first();

                $product = Product::where([
                    'made' => $response['made'],
                    'product_code' => $response['product_code'],
                    'user_id' => $user->id
                ])->first();

                switch ($response['type']){
                    case 0 :
                        $product->update([
                            'quantity' => $product->quantity + $response['quantity'],
                        ]);
                        break;
                    case 1 :
                        $product->update([
                            'quantity' => $product->quantity - $response['quantity'],
                        ]);
                        break;
                }

            }

        } catch (\Exception $exception) {
            echo $exception;
        }
    }
}
