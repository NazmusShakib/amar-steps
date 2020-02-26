<?php

namespace App\Http\Controllers\API;

use App\Events\ActivityLogCreated;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\ActivityLog;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ActivityLogController extends BaseController
{
    /**
     * @OA\Get(
     *      path="/api/v1/activities",
     *      operationId="activities-get",
     *      tags={"Activities"},
     *      summary="Get auth activities",
     *      description="Returns auth data",
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
     *          description="Retrieve auth activities.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *       ),
     * )
     *
     */
    public function index()
    {
        $activityLogs = ActivityLog::with('user')->select(
            'id', 'activity', 'created_at', 'updated_at')->where('user_id', Auth::id())
            ->orderBy('created_at', 'DESC')->get();

        return response()->json($activityLogs, 200);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/activities",
     *      operationId="activities-post",
     *      tags={"Activities"},
     *      summary="Save activity log.",
     *      description="Activity has been created successfully.",
     *      @OA\Parameter(
     *          name="Authorization",
     *          description="Bearer token",
     *          required=true,
     *          in="header",
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\Parameter(
     *          name="activity",
     *          description="activity json",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="json",
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="notes",
     *          description="notes",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Activity has been created successfully.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\JsonContent(type="object",example = {"success":true,"data":{"saved data"},"message":"Activity has been created successfully."})
     *          )
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Prerequisite failed.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\JsonContent(type="object",example = {"success":false,"data":{"errors"},"message":"Prerequisite failed."})
     *          )
     *       ),
     *       @OA\Response(response=401, description="Unauthorised"),
     *     )
     *
     * Returns with token
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'activity' => 'required',
            'notes' => 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Prerequisite failed.', $validator->errors(), 422);
        }

        $input = $request->only(['activity', 'notes']);
        $activityLog = ActivityLog::create($input);

        // Event dispatcher to create badge
        event(new ActivityLogCreated($activityLog));

        return $this->sendResponse($activityLog, 'Activity has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $activityLog = ActivityLog::with('user')->find($id);

        if (is_null($activityLog)) {
            return $this->sendError('Activity not found.');
        }

        return $this->sendResponse($activityLog, 'Activity retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'activity' => 'required',
            'notes' => 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Prerequisite failed.', $validator->errors(), 422);
        }

        $activityLog = ActivityLog::find($id);
        $activityLog->update([
            'activity' => $request->activity,
            'notes' => $request->notes,
        ]);

        return $this->sendResponse($activityLog, 'Activity has been updated successfully.');
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/activities/{id}",
     *      operationId="destroy-activity",
     *      tags={"Activities"},
     *      summary="Delete activity.",
     *      description="Activity has been deleted successfully.",
     *      @OA\Parameter(
     *          name="Authorization",
     *          description="Bearer token",
     *          required=true,
     *          in="header",
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Activity has been deleted successfully.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\JsonContent(type="object",example = {"success":true,"data":"","message":"Activity has been deleted successfully."})
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Failed to delete.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\JsonContent(type="object",example = {"success":false,"message":"Failed to delete."})
     *          )
     *      ),
     * )
     */
    public function destroy($id)
    {
        try {
            ActivityLog::findOrFail($id)->delete();
            return $this->sendResponse([], 'Activity has been deleted successfully.');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), '', 422);
        }
    }


    /**
     * @OA\Get(
     *      path="/api/v1/activities/badges",
     *      operationId="activities-badges",
     *      tags={"Activities"},
     *      summary="Auth badges",
     *      description="Returns all badges",
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
     *          description="Retrieve single badge by id.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\JsonContent(type="object",example = {})
     *          )
     *       ),
     * )
     *
     */
    public function activityBadge()
    {
        $badges = User::with(['badges' => function ($query) {
            $query->select('badges.id', 'badges.name', 'display_name', 'target', 'description');
        }])->find(Auth::id())->badges;

        if (count($badges) <= 0) {
            return $this->sendError('Badge not found.');
        }

        return $this->sendResponse($badges, 'Badges retrieved successfully.');
    }


}
