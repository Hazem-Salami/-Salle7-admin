<?php

namespace App\Jobs\auth\storehouse;

use App\Http\Traits\Base64Trait;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
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

            if($this->data['storehouse_photo']) {

                $i=0;

                foreach ($this->data['storehouse_photo'] as $base64encode) {

                    $i++;

                    $extension = $base64encode[1];
                    $base64encode = $base64encode[0];

                    $base64decode = $this->base64Decode($base64encode);

                    $path = $this->saveFile($extension, $base64decode, 'storehouse_photo', $i);

                    $user->userfiles()->create([
                        'path' => $path,
                    ]);
                }
            }

            if($this->data['IDphoto']) {

                $i=0;

                foreach ($this->data['IDphoto'] as $base64encode) {
                    $i++;

                    $extension = $base64encode[1];
                    $base64encode = $base64encode[0];

                    $base64decode = $this->base64Decode($base64encode);

                    $path = $this->saveFile($extension, $base64decode, 'ID_photo', $i);

                    $user->userfiles()->create([
                        'path' => $path,
                    ]);
                }
            }

            $storehouse = $user->storehouse;

            $storehouse->latitude = $this->data['latitude'];
            $storehouse->longitude = $this->data['longitude'];

            $storehouse->update();

            $user->verifyrequest()->create();

        } catch (\Exception $exception) {
            echo $exception;
        }
    }
}
