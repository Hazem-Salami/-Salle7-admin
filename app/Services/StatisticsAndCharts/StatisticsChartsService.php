<?php

namespace App\Services\StatisticsAndCharts;

use App\Http\Requests\charts\DateRequest;
use App\Models\Revenue;
use App\Models\User;
use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Http\Response;

class StatisticsChartsService extends BaseService
{
    public function getNumUser(): Response
    {
        /**
         * 0 client
         * 1 workshop
         * 2 towing
         * 3 storehouse
         */
        $numClient = User::where('user_type', 0)->count();
        $numWorkshop = User::where('user_type', 1)->count();
        $numTowing = User::where('user_type', 2)->count();
        $numStorehouse = User::where('user_type', 3)->count();
        $numUser = User::count();

        return $this->customResponse(true, 'تم الحصول على عدد المستخدمين بنجاح', [
            "num_client" => $numClient,
            "num_workshop" => $numWorkshop,
            "num_towing" => $numTowing,
            "num_storehouse" => $numStorehouse,
            "num_user" => $numUser
        ]);
    }

    public function getRatioNumUser(): Response
    {
        $numClient = User::where('user_type', 0)->count();
        $numWorkshop = User::where('user_type', 1)->count();
        $numTowing = User::where('user_type', 2)->count();
        $numStorehouse = User::where('user_type', 3)->count();
        $numUser = User::count();

        return $this->customResponse(true, 'تم الحصول على نسب عدد المستخدمين بنجاح', [
            "ratio_client" => $numClient / $numUser * 100,
            "ratio_workshop" => $numWorkshop / $numUser * 100,
            "ratio_towing" => $numTowing / $numUser * 100,
            "ratio_storehouse" => $numStorehouse / $numUser * 100,
        ]);
    }

    public function getChartNumUser(DateRequest $request): Response
    {
        $start = new \DateTime(Carbon::createFromFormat('j/n/Y', $request->start_date)->format('d-m-Y'));
        $end = new \DateTime(Carbon::createFromFormat('j/n/Y', $request->end_date)->format('d-m-Y'));

        $difference  = $start->diff($end);

        if((int) $difference->format('%y') > 1){
            $details = User::whereBetween('created_at', [$start, $end])
                ->get()->groupBy(
                    function ($data){
                        return Carbon::parse($data->created_at)->format('Y');
                    }
                );
            $charts = array();
            foreach ($details as $key => $item ){
                $charts[]=[
                    'date' => $key,
                    'num_user'=> count($item)
                ];
            }
        }else{
            if((int) $difference->format('%m') > 1 || (int) $difference->format('%y') == 1){
                $details = User::whereBetween('created_at', [$start, $end])
                    ->get()->groupBy(
                    function ($data){
                        return Carbon::parse($data->created_at)->format('F/Y');
                    }
                );
                $charts = array();
                foreach ($details as $key => $item ){
                    $charts[]=[
                        'date' => $key,
                        'num_user'=> count($item)
                    ];
                }
            }else{
                $details = User::whereBetween('created_at', [$start, $end])
                    ->get()->groupBy(
                        function ($data){
                            return Carbon::parse($data->created_at)->format('d/F/Y');
                        }
                    );
                $charts = array();
                foreach ($details as $key => $item ){
                    $charts[]=[
                        'date' => $key,
                        'num_user'=> count($item)
                    ];
                }
            }
        }
        return $this->customResponse(true, 'تم الحصول على نسب عدد المستخدمين بنجاح',$charts);
    }

    public function getRevenues(DateRequest $request): Response
    {
        $start = new \DateTime(Carbon::createFromFormat('j/n/Y', $request->start_date)->format('d-m-Y'));
        $end = new \DateTime(Carbon::createFromFormat('j/n/Y', $request->end_date)->format('d-m-Y'));

        $revenues = Revenue::whereBetween('created_at', [$start, $end])->sum('amount');

        return $this->customResponse(true, 'تم الحصول على مجموع الأرباح بنجاح', $revenues);
    }


    public function getRevenuesByUser(User $user): Response
    {
        $revenues = Revenue::where('user_id', $user->id)->sum('amount');

        return $this->customResponse(true, 'تم الحصول على مجموع الأرباح بنجاح', $revenues);
    }
}
