<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PendingRequestCollection;
use App\Http\Resources\PendingRequestResource;

use App\User;
use Illuminate\Http\Request;

class FriendshipsController extends BaseController
{
    protected $auth;

    public function __construct(Request $request)
    {
        $this->auth = $request->user('api');
    }

    /**
     * @OA\GET(
     *      path="/api/v1/friends",
     *      operationId="friends",
     *      tags={"Friends"},
     *      summary="Friends list",
     *      description="Friends list",
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
    public function friendList()
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
     *      path="/api/v1/friends/send-request/{id}",
     *      operationId="send-request",
     *      tags={"Friends"},
     *      summary="Send friend request to the given id parameter",
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
    public function sendFriendRequest($id)
    {
        $recipient = User::findOrFail($id);
        if ($recipient) {
            $this->auth->befriend($recipient);
            return $this->sendResponse([], 'Request has been sent successfully.');
        }

        return $this->sendError('Failed to send request.', [], 403);
    }

    /**
     * @OA\POST(
     *      path="/api/v1/friends/accept-request/{id}",
     *      operationId="accept-request",
     *      tags={"Friends"},
     *      summary="accept-request",
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
    public function acceptFriendRequest($id)
    {
        $sender = User::findOrFail($id);
        if ($sender) {
            $this->auth->acceptFriendRequest($sender);
            return $this->sendResponse([], 'Request has been accepted successfully.');
        }

        return $this->sendError('Failed to accept request.', [], 403);
    }

    /**
     * @OA\POST(
     *      path="/api/v1/friends/deny-request/{id}",
     *      operationId="deny-request",
     *      tags={"Friends"},
     *      summary="deny-request",
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
    public function denyFriendRequest($id)
    {
        $sender = User::findOrFail($id);
        if ($sender) {
            $this->auth->denyFriendRequest($sender);
            return $this->sendResponse([], 'Request has been declined successfully.');
        }

        return $this->sendError('Failed to decline request.', [], 403);
    }

    /**
     * @OA\POST(
     *      path="/api/v1/friends/un-friend/{id}",
     *      operationId="un-friend",
     *      tags={"Friends"},
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
    public function removeFriend($id)
    {
        $friend = User::findOrFail($id);
        if ($friend) {
            $this->auth->unfriend($friend);
            return $this->sendResponse([], 'Request has been canceled successfully.');
        }

        return $this->sendError('Failed to cancel request.', [], 403);
    }


    /**
     * @OA\GET(
     *      path="/api/v1/friends/pending-requests",
     *      operationId="friends-pending-requests",
     *      tags={"Friends"},
     *      summary="List of pending friend requests.",
     *      description="List of pending friend requests.",
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
     *          description="List of pending friend requests.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\JsonContent(type="object",example = {"success":true,"data":{{"id":1,"name":"Admin User","email":"admin@example.com","phone":"0111","headshot":null,"address":"9219 Kris Track Suite 613 Tylerstad, MA 28181-8012"},{"id":2,"name":"John Dou","email":"dou@example.com","phone":"012345678","headshot":null,"address":"234 Kris Track Suite 613ylerstad, MA 28181-8012"}},"message":"List of pending friend requests."})
     *          )
     *       ),
     * )
     *
     */
    public function pendingRequests()
    {
        $pendingFriendRequests = $this->auth->getFriendRequests();

        $pendingRequests = PendingRequestResource::collection($pendingFriendRequests);

        $pendingRequests = $pendingRequests->values()->toArray();

        return $this->sendResponse($pendingRequests, 'List of pending friend requests.');
    }

}
