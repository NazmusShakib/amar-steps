<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\SenderResource;
use App\Http\Resources\FriendResource;

use App\Notifications\FriendRequestNotification;
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
     *              @OA\JsonContent(type="object",example = {"success":true,"data":{{"id":1,"name":"Admin User","email":"rahap@example.com","phone":"012312453453543","headshot":null,"address":"9219 Kris Track Suite 613\nTylerstad, MA 28181-8012"},{"id":3,"name":"Subscriber Account","email":"sub@example.com","phone":"34534534","headshot":null,"address":"73721 Noble Trail\nNew Christopherchester, NV 62390-3169"}},"message":"Friends list."})
     *          )
     *       ),
     * )
     *
     */
    public function friendsList()
    {
        $acceptedRequests = $this->auth->getFriends();

        $acceptedRequests = FriendResource::collection($acceptedRequests);

        return $this->sendResponse($acceptedRequests, 'Friends list.');
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
        $hasSent = $this->auth->hasSentFriendRequestTo($recipient);
        $isFriendWith = $this->auth->isFriendWith($recipient);

        if ($isFriendWith) {
            return $this->sendResponse([], 'The recipient has already been on your friend list.');
        } else if (!$hasSent) {
            $this->auth->befriend($recipient);

            // send a notification to the recipient
            $recipient->notify(new FriendRequestNotification($this->auth));

            return $this->sendResponse([], 'Request has been sent successfully.');
        }

        return $this->sendResponse([], 'A request has already been sent.');
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
        $hasSent = $sender->hasSentFriendRequestTo($this->auth);
        if ($hasSent) {
            $this->auth->acceptFriendRequest($sender);
            return $this->sendResponse([], 'Request has been accepted successfully.');
        }

        return $this->sendError('No request to accept.', [], 403);
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
        $hasSent = $sender->hasSentFriendRequestTo($this->auth);
        if ($hasSent) {
            $this->auth->denyFriendRequest($sender);
            return $this->sendResponse([], 'Request has been declined successfully.');
        }

        return $this->sendError('No request to deny.', [], 403);
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
    public function unFriend($id)
    {
        $friend = User::findOrFail($id);
        $isFriendWith = $this->auth->isFriendWith($friend);

        if ($isFriendWith) {
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
     *              @OA\JsonContent(type="object",example = {"success":true,"data":{{"id":1,"name":"Admin User","email":"raptn@example.com","phone":"0453453454353","headshot":null,"address":"9219 Kris Track Suite 613 Tylerstad, MA 28181-8012"},{"id":2,"name":"John Dou","email":"dou@example.com","phone":"012345678","headshot":null,"address":"234 Kris Track Suite 613ylerstad, MA 28181-8012"}},"message":"List of pending friend requests."})
     *          )
     *       ),
     * )
     *
     */
    public function pendingRequests()
    {
        $pendingFriendRequests = $this->auth->getFriendRequests();

        $pendingRequests = SenderResource::collection($pendingFriendRequests);

        $pendingRequests = $pendingRequests->values()->toArray();

        return $this->sendResponse($pendingRequests, 'List of pending friend requests.');
    }

}
