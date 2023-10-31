<?php

namespace App\Jobs\auth\towing;

use App\Http\Traits\Base64Trait;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendAuthFilesJob implements ShouldQueue
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
     */
    public function handle(): void
    {
        try {

            $user = User::where('email',$this->data['user_email']) -> first();

            if($this->data['mechanics_photo']) {

                $i=0;

                foreach ($this->data['mechanics_photo'] as $base64encode) {

                    $i++;

                    $extension = $base64encode[1];
                    $base64encode = $base64encode[0];

                    $base64decode = $this->base64Decode($base64encode);

                    $path = $this->saveFile($extension, $base64decode, 'mechanics_photo', $i);

                    $user->userfiles()->create([
                        'path' => $path,
                    ]);
                }
            }

            if($this->data['certificate_photo']) {

                $i=0;

                foreach ($this->data['certificate_photo'] as $base64encode) {
                    $i++;

                    $extension = $base64encode[1];
                    $base64encode = $base64encode[0];

                    $base64decode = $this->base64Decode($base64encode);

                    $path = $this->saveFile($extension, $base64decode, 'certificate_photo', $i);

                    $user->userfiles()->create([
                        'path' => $path,
                    ]);
                }
            }

            $towing = $user->towing;

            $towing->number = $this->data['number'];
            $towing->type = $this->data['type'];
            $towing->price = $this->data['price'];

            $towing->update();

            $user->verifyrequest()->create();

        } catch (\Exception $exception) {
            echo $exception;
        }
    }
}
