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
        $currentMonthRanks = $this->currentMonthRanks->take(15);

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
     *              @OA\JsonContent(type="object",example = {"auth_rank_globally":{"id":1,"name":"Admin User","headshot":null,"city":null,"country":null,"address":"6540 Kris Mews\nNew Dorianstad, DC 80324","grand_distance":2037.8058,"rank":1},"auth_current_month_rank":{"id":1,"name":"Admin User","headshot":null,"city":null,"country":null,"address":"6540 Kris Mews\nNew Dorianstad, DC 80324","current_month_distance":2037.8058,"rank":1}})
     *          )
     *       )
     * )
     */
    public function authRank(Request $request)
    {
        return [
            'auth_rank_globally' => $this->authRankGlobally($request),
            'auth_current_month_rank' => $this->authCurrentMonthRank($request)
        ];
    }

    /**
     * Return auth Rank Globally.
     */
    private function authRankGlobally($request)
    {
        $worldRanks = $this->worldRanks->values()->toArray();
        $authRankGlobally = [];
        foreach ($worldRanks as $key => $rank) {
            if ($rank['id'] == Auth::id()) {
                $authRankGlobally = [
                    'id' => $rank['id'],
                    'name' => $rank['name'],
                    'headshot' => $rank['headshot'],
                    'city' => $rank['profile']['city'],
                    'country' => $rank['profile']['country'],
                    'address' => $rank['profile']['address'],
                ];
                $authRankGlobally['grand_distance'] = $request->user()->grand_total_distance;
                $authRankGlobally['rank'] = $key + 1;
            }
        }
        return $authRankGlobally;
    }

    /**
     * Return auth Current Month Rank.
     */
    private function authCurrentMonthRank($request)
    {
        $currentMonthRanks = $this->currentMonthRanks->values()->toArray();
        $currentMonthDistance = 0;
        $authCurrentMonthRank = [];
        foreach ($currentMonthRanks as $key => $rank) {
            if ($rank['id'] == Auth::id()) {
                $authCurrentMonthRank = $rank;
                $authCurrentMonthRank['rank'] = $key + 1;
            }
        }
        return $authCurrentMonthRank;
    }
}
