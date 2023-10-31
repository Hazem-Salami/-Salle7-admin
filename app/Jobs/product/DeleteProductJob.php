<?php

namespace App\Jobs\product;

use App\Http\Traits\Base64Trait;
use App\Models\Product;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Base64Trait;

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
        try{
            $user = User::where('email', $this->data['user_email'])->first();

            $product = Product::where(
                [
                    'made' => $this->data['made'],
                    'product_code' => $this->data['product_code'],
                    'user_id' => $user->id
                ])->first();

            $this->deleteFile($product->image_path);

            $product->delete();
        } catch (\Exception $exception) {
            echo $exception;
        }
    }
}
