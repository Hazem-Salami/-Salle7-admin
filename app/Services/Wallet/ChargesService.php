<?php

namespace App\Services\Wallet;

use App\Http\Requests\wallet\ChargeWalletRequest;
use App\Jobs\wallets\ChargeWalletJob;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\WalletChargeHistory;
use App\Services\BaseService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChargesService extends BaseService
{
    public function chargeWallet(ChargeWalletRequest $request,
                                 User                $user): Response
    {
        if ($user->wallet == null)
            return $this->customResponse(false, 'لا يوجد محفظة لهذا المستخدم');

        DB::beginTransaction();
        $amount = $request->get('amount');
        $preAmount = $user->wallet->amount;
        $user->wallet->amount += $amount;
        $user->wallet->save();

        $in = $user->wallet->charges()->create([
            'charge' => $amount,
            'new_amount' => $user->wallet->amount,
            'pre_mount' => $preAmount
        ]);
        try {
            $data = [
                "user_email" => $user->email,
                'charge' => $amount,
            ];
            if ($user->user_type == 3)
                ChargeWalletJob::dispatch($data)->onQueue('store');
            else
                ChargeWalletJob::dispatch($data)->onQueue('main');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->customResponse(false, 'Bad Internet', null, 504);
        }
        DB::commit();
        return $this->customResponse('Charging done', $in);
    }

    public function getCharges(User $user): Response
    {
        if ($user->wallet == null)
            return $this->customResponse(false, 'لا يوجد محفظة لهذا المستخدم');

        return $this->customResponse(
            true,
            'charges log',
            $user->wallet->charges()->paginate(\request('size'))
        );
    }
}
