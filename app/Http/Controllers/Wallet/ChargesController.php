<?php

namespace App\Http\Controllers\Wallet;

use App\Http\Controllers\Controller;
use App\Http\Requests\wallet\ChargeWalletRequest;
use App\Models\User;
use App\Models\UserWallet;
use App\Services\Wallet\ChargesService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ChargesController extends Controller
{
    /**
     * The workshop orders service implementation.
     *
     * @var ChargesService
     */
    protected ChargesService $chargesService;

    // singleton pattern, service container
    public function __construct(ChargesService $chargesService)
    {
        $this->chargesService = $chargesService;
    }

    /**
     * @param User $user
     * @return Response
     */
    public function getCharges(User $user): Response
    {
        return $this->chargesService->getCharges($user);
    }

    /**
     * @param ChargeWalletRequest $request
     * @param User $user
     * @return Response
     */
    public function chargeWallet(ChargeWalletRequest $request,
                                 User $user): Response
    {
        return $this->chargesService->chargeWallet($request, $user);
    }

}
