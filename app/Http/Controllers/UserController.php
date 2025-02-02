<?php

namespace App\Http\Controllers;

use App\Models\DailyPrize;
use App\Models\RdsInfo;
use App\Models\User;
use App\Models\UserChance;
use Illuminate\Http\Request;
use Kavenegar;
use \App\Http\Controllers\DailyPrizeController;
class UserController extends Controller
{
    public function register(Request $request)
    {
        try {
            $user = User::where('mobile', $request['mobile'])->first();
            if (!$user) {
                $user = User::create(['mobile' => $request['mobile']]);
            }
            return response($user, 200);
        } catch (\Exception $exception) {
            return response($exception);
        }
    }

    public function play(Request $request)
    {
        $user = User::where('mobile', $request['mobile'])->first();
        $array =  (new DailyPrizeController)->indexx($request['type']);
            $weightedValues = [];
            foreach ($array as $item) {
                if ($item['possibility'] > 0) {
                    for ($i = 0; $i < $item['possibility']; $i++) {
                        $weightedValues[] = $item['value'];
                    }
                }
            }
            if (!empty($weightedValues)) {
                $val = $weightedValues[array_rand($weightedValues)];
                UserChance::create(['user_id' => $user['id'], 'chance' => $val, 'type' => $request['type']]);
                if ($val == 'pooch') {
                    $message = ["این بار شانس باهات یار نبود...",
                        "ولی بازم فرصت داری! فردا همینجا منتظرتیم."];
                    return response(['message' => $message], 200);
                } else {
                    $t = DailyPrize::where('possibility','>',0)->where('value',$val)->first();
                    $t->update(['possibility'=>0]);
                    $brand = '';
                    switch($request['type']){
                        case 'osareh':$brand = 'الیت';break;
                        case 'ocopa':$brand = 'اوکوپا';break;
                        case 'copa':$brand = 'کوپا';break;
                    }
                    if ($val == 'pack'){
                        $message = ["برنده شدی!",
                            "یک عدد پک هدیه ".$brand,
                            "برای ارسال هدیه ت باهات تماس میگیریم در دسترس باش."];
                        return response(['message' => $message], 200);
                    }else{
                        $message = ["برنده شدی!",
                            " کد تخفیف: " . $val,
                            "3 روز فرصت داری ازش استفاده کنی!"];
                        return response(['message' => $message], 200);
                    }
                }
            }
    }

    public function oneTimePassword(Request $request)
    {
        try {
            $sender = "10005989";        //This is the Sender number
            $code = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $user = User::where('mobile', $request['mobile'])->first();
            $codes = RdsInfo::where('user_id', $user['id'])->get();
            if (count($codes)) {
                foreach ($codes as $item) {
                    $item->delete();
                }
            }
            RdsInfo::create([
                "user_id" => $user['id'],
                "code" => $code
            ]);
            $message = "کد تایید شما: " . $code . "
" . "گردونه شانس الیت";        //The body of SMS
            $result = Kavenegar::Send($sender, $request['mobile'], $message);

            return response($result);

        } catch (\Kavenegar\Exceptions\ApiException $e) {
            // در صورتی که خروجی وب سرویس 200 نباشد این خطا رخ می دهد
            echo $e->errorMessage();
        } catch (\Kavenegar\Exceptions\HttpException $e) {
            // در زمانی که مشکلی در برقرای ارتباط با وب سرویس وجود داشته باشد این خطا رخ می دهد
            echo $e->errorMessage();
        } catch (\Exceptions $ex) {
            // در صورت بروز خطایی دیگر
            echo $ex->getMessage();
        }
    }

    public function verify(Request $request)
    {
        $user = User::where('mobile', $request['mobile'])->first();
        $code = $user->code->code;
        if (isset($code) && $code == $request['code']) {
            $today = UserChance::where('user_id',$user['id'])
                ->where('created_at', '>=', today()->subHours(24))->get();
            if (count($today)>0) {
                $message = ["امروز شانست رو امتحان کردی. فردا میتونی دوباره تلاش کنی!"];
                return response(['message' => $message, 'status2'=> 422], 200);
            } else {
                return response(['message' => 'user mobile successfully verified.'], 200);
            }
        } else {
            return response(['message' => 'wrong code'], 500);

        }

    }

    public function test()
    {
        $today = UserChance::where('user_id',1)
            ->where('created_at', '>=', today()->subHours(24))->get();
        return response(count($today),200);
        try {
            $sender = "10005989";        //This is the Sender number
            $code = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $result = Kavenegar::Send($sender, '09128222725', $code);
            return response([$result, $_SERVER['SERVER_ADDR']]);

        } catch (\Kavenegar\Exceptions\ApiException $e) {
            // در صورتی که خروجی وب سرویس 200 نباشد این خطا رخ می دهد
            echo $e->errorMessage();
        } catch (\Kavenegar\Exceptions\HttpException $e) {
            // در زمانی که مشکلی در برقرای ارتباط با وب سرویس وجود داشته باشد این خطا رخ می دهد
            echo $e->errorMessage();
        } catch (\Exceptions $ex) {
            // در صورت بروز خطایی دیگر
            echo $ex->getMessage();
        }
    }

}
