<?php

namespace App\Services\Verify\Towing;

use App\Http\Traits\FilesTrait;
use App\Jobs\auth\towing\AcceptRequestJob;
use App\Jobs\auth\towing\RejectRequestJob;
use App\Models\UserFile;
use App\Models\VerifyRequest;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class VerifyRequestsService extends BaseService
{
    use FilesTrait;

    /**
     *
     * @return Response
     */
    public function getVerifyRequest(): Response
    {
        $response = array();

        $verifyrequests = VerifyRequest::all();

        foreach ($verifyrequests as $verifyrequest)
        {
            $user = $verifyrequest->user;

            $towing = $user->towing;

            if($towing)
                $response[] = [
                    "user_id" => $verifyrequest->user_id,
                    "towing_id" => $towing->id,
                    "car_number" => $towing->number,
                    'type' => $towing->type,
                    'price' => $towing->price,
                    "created_at" => $towing->created_at,
                    "updated_at" => $towing->updated_at,
                    "firstname" => $user->firstname,
                    "lastname" => $user->lastname,
                    "email" => $user->email,
                    "phone_number" => $user->phone_number,
                ];

        }

        return $this->customResponse(true, 'get verify requests towing success', $response);
    }

    /**
     * @param Request
     * @return Response
     */
    public function getFileRequest($request): Response
    {
        $verifyrequest = $request->get('verifyrequest');

        $user = $verifyrequest->user;
        $requestFiles = $user->userfiles;

        $response = [
            'requestFiles' => $requestFiles,
        ];

        return $this->customResponse(true, 'get file request success', $response);
    }

    /**
     * @param Request
     * @return Response
     */
    public function acceptRequest($request): Response
    {
        DB::beginTransaction();

        $verifyrequest = $request->get('verifyrequest');

        $user = $verifyrequest->user;

        if($user->user_type == 2) {
            VerifyRequest::where('user_id', $verifyrequest->user_id)->delete();

            $towing = $user->towing;

            $response = [
                'number' => $towing->number,
                'type' => $towing->type,
                'price' => $towing->price,
                'user_email' => $user->email,
            ];

            try {

                AcceptRequestJob::dispatch($response)->onQueue('main');

            } catch (\Exception $e) {
                DB::rollBack();
                return $this->customResponse(false, 'Bad Internet', null, 504);
            }
            DB::commit();

            return $this->customResponse(true, 'تم الموافقة على الطلب بنجاح');
        }

        return $this->customResponse(true, 'هذا الطلب غير مخصص لسيارة سحب');
    }

    /**
     * @param Request
     * @return Response
     */
    public function rejectRequest($request): Response
    {
        DB::beginTransaction();

        $verifyrequest = $request->get('verifyrequest');

        $user = $verifyrequest->user;

        if($user->user_type == 2) {
            VerifyRequest::where('user_id', $verifyrequest->user_id)->delete();

            $userfiles = UserFile::where('user_id', $verifyrequest->user_id)->get();

            foreach ($userfiles as $userfile) {
                $this->destoryFile($userfile->path);
                $userfile->delete();
            }

            $response = [
                'user_email' => $user->email,
            ];

            try {

                RejectRequestJob::dispatch($response)->onQueue('main');

            } catch (\Exception $e) {
                DB::rollBack();
                return $this->customResponse(false, 'Bad Internet', null, 504);
            }

            DB::commit();
            return $this->customResponse(true, 'تم رفض الطلب بنجاح');
        }

        return $this->customResponse(true, 'هذا الطلب غير مخصص لسيارة سحب');
    }
}
