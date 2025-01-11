<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserChance;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register($mobile)
    {
        try {
            $user = User::where('mobile', $mobile)->first();
            if (!$user) {
                $user = User::create(['mobile' => $mobile]);
            }
            return response($user, 200);
        } catch (\Exception $exception) {
            return response($exception);
        }
    }

    public function play(User $user)
    {
        $array = [
            ['value' => '123456', 'possibility' => 0],
            ['value' => '456789', 'possibility' => 1],
            ['value' => '789123', 'possibility' => 1],
            ['value' => 'pooch', 'possibility' => 100],
        ];
//        if (count($user->today()) == 0) {
        if (1) {
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
                if ($val == 'pooch') {
                    $message = 'این بار شانس باهات یار نبود...<br>ولی بازم فرصت داری! فردا همینجا منتظرتیم.';
                    return response(['message' => $message], 200);
                } else {
                    $message = 'برنده شدی!<br> کد تخفیف: ' . $val . '<br>3 روز فرصت داری ازش استفاده کنی!';
                    return response(['message' => $message], 200);
                }
            }

        } else {
            $message = 'امروز شانست رو امتحان کردی. <br>فردا میتونی دوباره تلاش کنی!';
            return response(['message' => $message, 500]);
        }
    }
}
