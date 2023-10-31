<?php

namespace App\Http\Controllers\Verify\Towing;

use App\Http\Controllers\Controller;
use App\Services\Verify\Towing\VerifyRequestsService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VerifyRequestsController extends Controller
{
    /**
     *
     * @var VerifyRequestsService
     */
    protected VerifyRequestsService $verifyRequestsService;

    // singleton pattern, service container
    public function __construct(VerifyRequestsService $verifyRequestsService)
    {
        $this->verifyRequestsService = $verifyRequestsService;
    }

    public function getVerifyRequest(): Response
    {
        return $this->verifyRequestsService->getVerifyRequest();
    }

    public function getFileRequest(Request $request): Response
    {
        return $this->verifyRequestsService->getFileRequest($request);
    }

    public function acceptRequest(Request $request): Response
    {
        return $this->verifyRequestsService->acceptRequest($request);
    }

    public function rejectRequest(Request $request): Response
    {
        return $this->verifyRequestsService->rejectRequest($request);
    }
}
