<?php

namespace App\Http\Controllers;

use App\Models\DailyPrize;
use App\Models\User;
use App\Models\UserChance;
use Illuminate\Http\Request;

class DailyPrizeController extends Controller
{
    public function index()
    {
        try {
            $data = DailyPrize::select('id','value','possibility')->orderBy('id')->get();
            return response($data,200);
        } catch (\Exceptions $exception) {
            return response($exception);
        }
    }
   public function indexx()
    {
        try {
            $data = DailyPrize::select('id','value','possibility','active')
                ->orderBy('id')->where('active',true)->get();
            return $data;
        } catch (\Exceptions $exception) {
            return response($exception);
        }
    }
   public function winners(Request $request)
    {
        try {
            if (isset($request['mobile'])&&$request['mobile']!=''){
                $data = User::select('id','mobile')
                    ->orderByDesc('id')->where('mobile',$request['mobile'])
                    ->with('prizes:chance as prize,created_at as date,user_id')->paginate(200);
            }else{
                $data = UserChance::select('chance as prize','created_at as date','user_id')
                    ->orderByDesc('id')->with('user:id,mobile')->whereNot('chance','pooch')->paginate(200);
            }
            return $data;
        } catch (\Exceptions $exception) {
            return response($exception);
        }
    }
    public function store(Request $request)
    {
        try {
            $data = DailyPrize::create($request->all('value','possibility'));
            return response($data,201);
        } catch (\Exceptions $exception) {
            return response($exception);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $dailyPrize = DailyPrize::find($id);
            $dailyPrize->update($request->all('value','possibility','active'));
            return response($dailyPrize,200);
        } catch (\Exceptions $exception) {
            return response($exception);
        }
    }
    public function activeToggle(DailyPrize $dailyPrize)
    {
        try {
            $dailyPrize->update(['active'=>!$dailyPrize['active']]);
            return response($dailyPrize,200);
        } catch (\Exceptions $exception) {
            return response($exception);
        }
    }
    public function distroy(DailyPrize $dailyPrize)
    {
        try {
            $data = DailyPrize::orderBy('id')->get();
            return response($data,200);
        } catch (\Exceptions $exception) {
            return response($exception);
        }
    }

}
