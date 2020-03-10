<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CurrentMonthRankResource;
use App\Http\Resources\WorldRankResource;
use App\Models\BadgeUnit;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaderBoardController extends Controller
{
    protected $worldRanks;
    protected $currentMonthRanks;
    protected $authRankGlobally;
    protected $authCurrentMonthRank;

    public function __construct()
    {
        $this->worldRanks = User::worldRanks();
        $this->currentMonthRanks = User::currentMonthRanks();
    }


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
        $worldRanks = WorldRankResource::collection($this->worldRanks)->take(15);
        $currentMonthRanks = CurrentMonthRankResource::collection($this->currentMonthRanks)->take(15);

        return [
            'world_ranks' => $worldRanks->values()->toArray(),
            'current_month_ranks' => $currentMonthRanks->values()->toArray(),
        ];
    }

    /**
     * @OA\Get(
     *      path="/api/v1/leaderboard/auth-rank",
     *      operationId="leaderboard-auth-rank",
     *      tags={"Leader Board"},
     *      summary="leader board auth rank",
     *      description="Return leader board auth rank",
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
     *              @OA\JsonContent(type="object",example = {"auth_rank_globally":{"id":1,"name":"Admin User","headshot":null,"grand_distance":8151.2232,"rank":1,"profile":{"city":"Dhaka","country":"Bangladesh","user_id":1}},"auth_current_month_rank":{"id":1,"name":"Admin User","headshot":null,"current_month_distance":6113.4174,"rank":1,"profile":{"city":"Dhaka","country":"Bangladesh","user_id":1}}})

     *          )
     *       )
     * )
     */
    public function authRank(Request $request)
    {
        $this->authRankGlobally();
        $this->authCurrentMonthRank($request);

        return [
            'auth_rank_globally' => $this->authRankGlobally,
            'auth_current_month_rank' => $this->authCurrentMonthRank
        ];
    }


    /**
     * Return auth Rank Globally.
     */
    private function authRankGlobally()
    {
        $this->worldRanks->each(function ($rank, $key) {
            if ($rank->id == Auth::id()) {
                $this->authRankGlobally = $rank;
                $this->authRankGlobally['grand_distance'] = $rank->grand_total_distance;
                return $this->authRankGlobally['rank'] = $key + 1;
            }
        });

        return $this->authRankGlobally;
    }

    /**
     * Return auth Current Month Rank.
     */
    private function authCurrentMonthRank($request)
    {
        $currentMonthDistance = 0;
        $this->currentMonthRanks->each(function ($rank, $key) use ($request, $currentMonthDistance) {
            if ($rank->id == Auth::id()) {
                $this->authCurrentMonthRank = $rank;

                foreach ($request->user()->currentMonthActivityLog as $eachLog) {
                    $activity = json_decode($eachLog->activity);
                    $currentMonthDistance += $activity->distance;
                }
                $this->authCurrentMonthRank['current_month_distance'] = $currentMonthDistance;
                return $this->authCurrentMonthRank['rank'] = $key + 1;
            }
        });

        return $this->authCurrentMonthRank;
    }
}
