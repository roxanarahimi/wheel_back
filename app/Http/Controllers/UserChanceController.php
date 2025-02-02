<?php

namespace App\Http\Controllers;

use App\Models\UserChance;
use Illuminate\Http\Request;

class UserChanceController extends Controller
{

    public function store(Request $request)
    {
        try {
            $chance = UserChance::create([
                'user_id' => $request['user_id'],
                'chance' => $request['chance'],
                'type' => $request['type']
            ]);
            return response($chance,200);
        }catch (\Exception $exception){
            return response($exception);
        }
    }

    public function show(UserChance $userChance)
    {
        //
    }
    public function update(Request $request, UserChance $userChance)
    {
        //
    }

}
