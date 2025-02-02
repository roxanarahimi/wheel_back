<?php

namespace App\Http\Controllers;

use App\Http\Resources\DailyPrizeResource;
use App\Http\Resources\UserChanceResource;
use App\Http\Resources\UserResource;
use App\Models\DailyPrize;
use App\Models\User;
use App\Models\UserChance;
use Illuminate\Http\Request;

class DailyPrizeController extends Controller
{
    public function index()
    {
        try {
            $data = DailyPrize::select('id', 'value', 'possibility','updated_at')->orderBy('id')->get();
            return response(DailyPrizeResource::collection($data), 200);
        } catch (\Exceptions $exception) {
            return response($exception);
        }
    }

    public function indexx()
    {
        try {
            $data = DailyPrize::select('id', 'value', 'possibility', 'active')
                ->orderBy('id')->where('active', true)->get();
            return $data;
        } catch (\Exceptions $exception) {
            return response($exception);
        }
    }

    public function trys(Request $request)
    {
        try {

            $info = UserChance::orderByDesc('id')->paginate(200);
            $data = UserChanceResource::collection($info);

            return $info;
        } catch (\Exceptions $exception) {
            return response($exception);
        }
    }

    public function winners(Request $request)
    {
        try {
            if (isset($request['mobile']) && $request['mobile'] != '') {
                $info = User::orderByDesc('id')->where('mobile', $request['mobile'])->first();
                $info = new UserResource($info);
            } else {
                $info = UserChance::orderByDesc('id')->whereNot('chance', 'pooch')->paginate(200);
                $data = UserChanceResource::collection($info);
            }
            return $info;
        } catch (\Exceptions $exception) {
            return response($exception);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = DailyPrize::create($request->all('value', 'possibility'));
            return response($data, 201);
        } catch (\Exceptions $exception) {
            return response($exception);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $dailyPrize = DailyPrize::find($id);
            $dailyPrize->update($request->all('value', 'possibility', 'active'));
            return response($dailyPrize, 200);
        } catch (\Exceptions $exception) {
            return response($exception);
        }
    }

    public function activeToggle(DailyPrize $dailyPrize)
    {
        try {
            $dailyPrize->update(['active' => !$dailyPrize['active']]);
            return response($dailyPrize, 200);
        } catch (\Exceptions $exception) {
            return response($exception);
        }
    }

    public function distroy(DailyPrize $dailyPrize)
    {
        try {
            $data = DailyPrize::orderBy('id')->get();
            return response($data, 200);
        } catch (\Exceptions $exception) {
            return response($exception);
        }
    }

    public function refresh()
    {
        try {
            $prizes = DailyPrize::where('id','>', 1)->get();
            foreach ($prizes as $item) {
                $item->update(['possibility'=> 1]);
            }
            $datetime = new \DateTime("now", new \DateTimeZone("Asia/Tehran"));
            $nowTime = $datetime->format('Y-m-d G:i');
            echo $nowTime . ' - Tehran Time: OK;
';
        }catch (\Exception $exception){
            $datetime = new \DateTime("now", new \DateTimeZone("Asia/Tehran"));
            $nowTime = $datetime->format('Y-m-d G:i');
            echo $nowTime . ' - Tehran Time: ' . $exception->getMessage() . '
';
        }
    }

}
