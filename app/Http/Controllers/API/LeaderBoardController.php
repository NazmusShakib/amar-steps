<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class LeaderBoardController extends Controller
{

    public function leaderboard()
    {
        $wordRank = User::with(['unitTotal', 'profile' => function($query) {
            $query->select('profiles.city', 'profiles.country','profiles.user_id');
        }])
            ->select('id', 'name', 'headshot')->get();

        $json = [
            'rank' => 1,
            'name' => "Nazmus Shakib",
            'city' => "Dhaka",
            'country' => "Bangladesh",
            'distance' => "Bangladesh",
        ];

        return $wordRank;
    }


}
