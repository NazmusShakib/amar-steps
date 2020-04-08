<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BeFollowerController extends BaseController
{
    protected $auth;

    public function __construct(Request $request)
    {
        $this->auth = $request->user('api');
    }

    /**
     * @OA\GET(
     *      path="/api/v1/followers",
     *      operationId="followers",
     *      tags={"Followers"},
     *      summary="Follow list",
     *      description="Following list",
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
     *          description="following List.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\JsonContent(type="object",example = {"follow_list":{{"id":1,"name":"Admin User","email":"admin@example.com","phone":"0111","headshot":null,"profile":{"city":null,"country":null,"address":"9219 Kris Track Suite 613\nTylerstad, MA 28181-8012","user_id":1}},{"id":2,"name":"Staff Account","email":"staff@example.com","phone":"0222","headshot":null,"profile":{"city":null,"country":null,"address":"630 Howell Branch Suite 384\nNorth Destinytown, AL 41217","user_id":2}}},"unfollow_list":{{"id":3,"name":"Subscriber Account","email":"subscriber@example.com","phone":"0333","headshot":null,"profile":{"city":null,"country":null,"address":"73721 Noble Trail ew Christopherchester, NV 62390-3169","user_id":3}}}})
     *          )
     *       ),
     * )
     *
     */
    public function followingList()
    {
        $followList = User::followedBy($this->auth)
            ->with(['profile' => function ($query) {
                $query->select('profiles.city', 'profiles.country', 'profiles.address', 'profiles.user_id');
            }])
            ->select('id', 'name', 'email', 'phone', 'headshot')->get();
        $unfollowList = User::unfollowedBy($this->auth)
            ->with(['profile' => function ($query) {
                $query->select('profiles.city', 'profiles.country', 'profiles.address', 'profiles.user_id');
            }])
            ->select('id', 'name', 'email', 'phone', 'headshot')->get();

        return [
            'follow_list' => $followList,
            'unfollow_list' => $unfollowList
        ];
    }

    /**
     * @OA\POST(
     *      path="/api/v1/followers/send-follow-request/{id}",
     *      operationId="send-follow-request",
     *      tags={"Followers"},
     *      summary="Send follow request to the given id parameter",
     *      description="Request has been sent successfully.",
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
     *          description="Request has been sent successfully.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\JsonContent(type="object",example = {"success":true,"data":{},"message":"Request has been sent successfully."})
     *          )
     *       ),
     * )
     *
     */
    public function sendFollowRequest($id)
    {
        $followableUser = User::findOrFail($id);
        if ($followableUser) {
            $this->auth->followRequest($followableUser);
            return $this->sendResponse([], 'Request has been sent successfully.');
        }

        return $this->sendError('Failed to send request.', [], 403);
    }

    /**
     * @OA\POST(
     *      path="/api/v1/followers/cancel-follow-request/{id}",
     *      operationId="cancel-follow-request",
     *      tags={"Followers"},
     *      summary="The follower may cancel the request before getting acceptance.",
     *      description="Request has been canceled successfully.",
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
     *          description="Request has been canceled successfully.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\JsonContent(type="object",example = {"success":true,"data":{},"message":"Request has been cancled successfully."})
     *          )
     *       ),
     * )
     *
     */
    public function cancelFollowRequest($id)
    {
        $followableUser = User::findOrFail($id);
        if ($followableUser) {
            $this->auth->cancelFollowRequest($followableUser);
            return $this->sendResponse([], 'Request has been canceled successfully.');
        }

        return $this->sendError('Failed to cancel request.', [], 403);
    }

    /**
     * @OA\POST(
     *      path="/api/v1/followers/accept-follow-request/{id}",
     *      operationId="accept-follow-request",
     *      tags={"Followers"},
     *      summary="accept-follow-request",
     *      description="Request has been accepted successfully.",
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
     *          description="Request has been accepted successfully.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\JsonContent(type="object",example = {"success":true,"data":{},"message":"Request has been accepted successfully."})
     *          )
     *       ),
     * )
     *
     */
    public function acceptFollowRequest($id)
    {
        $followableUser = User::findOrFail($id);
        if ($followableUser) {
            $this->auth->acceptFollowRequest($followableUser);
            return $this->sendResponse([], 'Request has been accepted successfully.');
        }

        return $this->sendError('Failed to accept request.', [], 403);
    }

    /**
     * @OA\POST(
     *      path="/api/v1/followers/decline-follow-request/{id}",
     *      operationId="decline-follow-request",
     *      tags={"Followers"},
     *      summary="decline-follow-request",
     *      description="Request has been declined successfully.",
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
     *          description="Request has been declined successfully.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\JsonContent(type="object",example = {"success":true,"data":{},"message":"Request has been decline successfully."})
     *          )
     *       ),
     * )
     *
     */
    public function declineFollowRequest($id)
    {
        $followableUser = User::findOrFail($id);
        if ($followableUser) {
            $this->auth->declineFollowRequest($followableUser);
            return $this->sendResponse([], 'Request has been declined successfully.');
        }

        return $this->sendError('Failed to decline request.', [], 403);
    }

}
