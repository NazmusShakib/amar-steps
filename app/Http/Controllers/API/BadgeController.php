<?php

namespace App\Http\Controllers\API;

use App\Helpers\Helper;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Badge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BadgeController extends BaseController
{
    /**
     * @OA\Get(
     *      path="/api/v1/badges",
     *      operationId="badges-get",
     *      tags={"Badges"},
     *      summary="Get all badges",
     *      description="Returns badges",
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
     *          description="Retrieve badges.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *       ),
     * )
     *
     */
    public function index()
    {
        $badges = Badge::with('createdBy')->select(
            'id', 'name', 'display_name', 'target', 'description')
            ->orderBy('created_at', 'DESC')->paginate(15);

        return response()->json($badges, 200);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/badges",
     *      operationId="Badges-post",
     *      tags={"Badges"},
     *      summary="Create badge.",
     *      description="Badge has been created successfully.",
     *      @OA\Parameter(
     *          name="Authorization",
     *          description="Bearer token",
     *          required=true,
     *          in="header",
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\Parameter(
     *          name="name",
     *          description="name",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="display_name",
     *          description="display_name",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="target",
     *          description="target",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="double",
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="description",
     *          description="description",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Badge has been created successfully.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\JsonContent(type="object",example = {"success":true,"data":{"saved data"},"message":"Badge has been created successfully."})
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
     *     )
     *
     * Returns with token
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:badges,name,NULL,id,deleted_at,NULL',
            'display_name' => 'nullable',
            'target' => 'nullable|regex:/^\d+(\.\d{1,3})?$/',
            'description' => 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Prerequisite failed.', $validator->errors(), 422);
        }

        $input = $request->only(['name', 'display_name', 'target', 'description']);
        $input['created_by'] = Auth::id();
        $badge = Badge::create($input);

        return $this->sendResponse($badge, 'Badge has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $badge = Badge::with('createdBy')->find($id);

        if (is_null($badge)) {
            return $this->sendError('Badge not found.');
        }

        return $this->sendResponse($badge, 'Badge retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Badge $badge)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:badges,name,' . $badge->id,
            'display_name' => 'nullable',
            'description' => 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Prerequisite failed.', $validator->errors(), 422);
        }

        // $badge = Badge::find($id);
        $badge->update([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description,
        ]);

        return $this->sendResponse($badge, 'Badge has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Badge::find($id)->delete();
            return $this->sendResponse([], 'Badge has been deleted successfully.');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), '', 422);
        }
    }

}
