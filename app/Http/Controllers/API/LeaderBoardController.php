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
