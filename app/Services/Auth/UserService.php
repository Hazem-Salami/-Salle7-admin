<?php

namespace App\Services\Auth;

use App\Http\Requests\auth\LoginRequest;
use App\Jobs\auth\UserBlockingJob;
use App\Models\User;
use App\Models\Workshop;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class UserService extends BaseService
{
    /**
     * @param $request
     * @return Response
     */
    public function getUsersByType($request): Response
    {
        if ($request->isPaginate == 0) {
            $users = User::where('user_type', $request->type)->get();
            if ($request->type == 1)
                $users = $users->map(function ($item) {
                    return [
                        "id" => $item->id,
                        "workshop_name" => $item->workshop->name,
                        "address" => $item->workshop->address,
                        "description" => $item->workshop->description,
                        "firstname" => $item->firstname,
                        "lastname" => $item->lastname,
                        "phone_number" => $item->phone_number,
                        "user_latitude" => $item->workshop->latitude,
                        "user_longitude" => $item->workshop->longitude,
                        "created_at" => $item->created_at,
                        "updated_at" => $item->updated_at,
                    ];
                });
            else if ($request->type == 3)
                $users = $users->map(function ($item) {
                    return [
                        "id" => $item->id,
                        "store_name" => $item->storehouse->name,
                        "firstname" => $item->firstname,
                        "lastname" => $item->lastname,
                        "phone_number" => $item->phone_number,
                        "user_latitude" => $item->storehouse->latitude,
                        "user_longitude" => $item->storehouse->longitude,
                        "created_at" => $item->created_at,
                        "updated_at" => $item->updated_at,
                    ];
                });
            else
                $users = $users->map(function ($item) {
                    return [
                        "id" => $item->id,
                        "firstname" => $item->firstname,
                        "lastname" => $item->lastname,
                        "phone_number" => $item->phone_number,
                        "user_latitude" => $item->latitude,
                        "user_longitude" => $item->longitude,
                        "created_at" => $item->created_at,
                        "updated_at" => $item->updated_at,
                    ];
                });
        } else {
            $users = User::where('user_type', $request->type)->paginate($request->size);
        }
        return $this->customResponse(false, 'Users list', $users);
    }

    public function showUser(User $user): Response
    {
        $user->wallet;

        if($user->user_type == 1){
            $user->property_id = $user->workshop->id;
        }else if($user->user_type == 2){
            $user->property_id = $user->towing->id;
        }else if($user->user_type == 3){
            $user->property_id = $user->storehouse->id;
        }else{
            $user->property_id = $user->id;
        }

        return $this->customResponse(true, "User's details", $user);
    }

    public function blocking(User $user): Response
    {
        DB::beginTransaction();
        $user->blocked = !$user->blocked;
        $user->save();
        try {

            UserBlockingJob::dispatch([
                'user_email' => $user->email,
                'status' => $user->blocked,
            ])->onQueue('main');

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->customResponse(false, 'Bad Internet', null, 504);
        }
        DB::commit();
        return $this->customResponse(true, "User blocking toggled success", $user);
    }
}
