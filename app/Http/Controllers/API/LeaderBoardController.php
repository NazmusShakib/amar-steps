<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CurrentMonthRankResource;
use App\Http\Resources\WorldRankResource;
use App\Models\BadgeUnit;
use App\User;
use Illuminate\Http\Request;

class LeaderBoardController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/leaderboard",
     *      operationId="leaderboard",
     *      tags={"Leader Board"},
     *      summary="leader board",
     *      description="Return leader board",
     *      @OA\Parameter(
     *          name="authorization",
     *          description="Bearer token",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *          ),
     *          in="header"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Retrieve leaderboard.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *       )
     * )
     */
    public function leaderboard()
    {
        $worldRanks = WorldRankResource::collection(User::worldRanks())->take(15);
        $currentMonthRanks = CurrentMonthRankResource::collection(User::currentMonthRanks())->take(15);

        return [
            'world_ranks' => $worldRanks,
            'current_month_ranks' => $currentMonthRanks,
        ];
    }


    /**
     * Return top 15 globally.
     */
    public function worldRank()
    {
        $wordRank = User::with(['profile' => function ($query) {
            $query->select('profiles.city', 'profiles.country', 'profiles.user_id');
        }])->select('id', 'name', 'headshot')->get();

        $wordRank = WorldRankResource::collection($wordRank)
            ->sortByDesc(('grand_total_distance'))->take(15);

        return $wordRank;
    }

    /**
     * Return top 15 on month.
     */
    public function currentMonthRank()
    {
        $currentMonthRank = User::with(['profile' => function ($query) {
            $query->select('profiles.city', 'profiles.country', 'profiles.user_id');
        }])->whereHas('currentMonthActivityLog', function ($query) {
            $query->select('activity_logs.activity', 'activity_logs.user_id');
        })->select('id', 'name', 'headshot')->get();

        $currentMonthRank = CurrentMonthRankResource::collection($currentMonthRank)
            ->sortByDesc(('current_month_total_distance'))->take(15);

        return $currentMonthRank;
    }


}
