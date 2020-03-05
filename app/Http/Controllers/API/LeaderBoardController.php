<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\LeaderBoardResource;
use App\Models\BadgeUnit;
use App\User;
use function foo\func;
use Illuminate\Http\Request;

class LeaderBoardController extends Controller
{
    public function leaderboard()
    {
        $wordRank = User::with(['profile' => function($query) {
            $query->select('profiles.city', 'profiles.country','profiles.user_id');
        }, 'currentMonthActivityLog' => function ($query) {
            $query->select('activity_logs.activity','activity_logs.user_id');
        }])
            ->select('id', 'name', 'headshot')->get();
        //return $wordRank;

       return LeaderBoardResource::collection($wordRank)
           ->sortByDesc(('grand_total_distance'));
    }


}
