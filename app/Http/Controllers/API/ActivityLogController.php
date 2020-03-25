<?php

namespace App\Http\Controllers\API;

use App\Events\ActivityLogCreated;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\ActivityLog;
use App\Models\Badge;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Ramsey\Uuid\Uuid;

class ActivityLogController extends BaseController
{
    /**
     * @OA\Get(
     *      path="/api/v1/activities",
     *      operationId="activities-get",
     *      tags={"Activities"},
     *      summary="Get auth activities",
     *      description="Thumbnail:: http://localhost:8000/images/activities/thumb/thumb_200x200_b2f1778c-b46e-4274-8380-3eee19bfd0bd.jpg",
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
     *              @OA\JsonContent(type="object",example = {})
     *          )
     *       ),
     * )
     *
     */
    public function index()
    {
        $activityLogs = ActivityLog::select(
            'id', 'activity', 'notes', 'thumbnail', 'created_at', 'updated_at')
            ->where('user_id', Auth::id())
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
     *      @OA\Parameter(
     *          name="thumbnail",
     *          description="Thumbnail should be: sometimes|mimes:jpeg,jpg,png,gif|max:5120",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="file",
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
            'thumbnail' => 'sometimes|mimes:jpeg,jpg,png,gif|max:5120',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Prerequisite failed.', $validator->errors(), 422);
        }

        $input = $request->only(['activity', 'notes', 'thumbnail']);

        if ($request->hasFile('thumbnail')) {
            $imageID = Uuid::uuid4()->toString();
            $imageName = $imageID . '.' . $request->file('thumbnail')
                    ->getClientOriginalExtension();

            $originalImagePath = public_path('images/activities/');
            $thumbPath = public_path('images/activities/thumb/');
            $thumb_200x200 = 'thumb_200x200_' . $imageName;

            if (!file_exists($thumbPath)) {
                File::makeDirectory($thumbPath, 0755, true);
            }

            $request->file('thumbnail')->move(
                $originalImagePath, $imageName
            );

            $path = $originalImagePath . $imageName;
            Image::make($path)->resize(200, 200)->save($thumbPath . $thumb_200x200);
            $input['thumbnail'] = $imageName;
        }

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
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'activity' => 'required',
            'notes' => 'nullable',
            'thumbnail' => 'sometimes|mimes:jpeg,jpg,png,gif|max:5120',
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
        $badges = Badge::select('badges.id', 'badges.name', 'target', 'unit_id', 'description', 'badge_icon')
            ->get();

        if (count($badges) <= 0) {
            return $this->sendError('Badge not found.');
        }

        return $this->sendResponse($badges, 'Badges retrieved successfully.');
    }


}
