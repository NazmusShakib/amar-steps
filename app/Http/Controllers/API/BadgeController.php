<?php

namespace App\Http\Controllers\API;

use App\Helpers\Helper;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Badge;
use App\Models\BadgeUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Ramsey\Uuid\Uuid;

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
        $badges = Badge::select(
            'id', 'name', 'target', 'unit_id',
            'description', 'badge_icon', 'created_at', 'updated_at')
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
            'name' => 'required|max:30|unique:badges,name,NULL,id,deleted_at,NULL',
            'unit_id' => 'required|exists:units,id',
            'badge_icon' => 'nullable|mimes:jpeg,jpg,png,gif|max:1024',
            'target' => 'nullable|regex:/^\d+(\.\d{1,3})?$/',
            'description' => 'nullable|max:200',
        ], [
            'unit_id.required' => 'The unit field is required.',
            'unit_id.exists' => 'The selected unit is invalid.',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Prerequisite failed.', $validator->errors(), 422);
        }
        $input = $request->only(['name', 'target', 'description', 'unit_id']);
        if($request->hasFile('badge_icon'))
        {
            $imageID = Uuid::uuid4()->toString();
            $imageName = $imageID . '.' . $request->file('badge_icon')->getClientOriginalExtension();
            $thumb_200x200 = 'thumb_200x200_' . $imageName;
            if (!file_exists(public_path('images/badges/thumb/'))) {
                File::makeDirectory(public_path('images/badges/thumb/'),0755, true);
            }
            $request->file('badge_icon')->move(
                public_path('images/badges/'), $imageName
            );
            $path = public_path('images/badges/') . $imageName;
            Image::make($path)->resize(200, 200)->save(public_path('images/badges/thumb/') . $thumb_200x200);
            $input['badge_icon'] = $imageName;
        }

        $badge = Badge::create($input);
        $badge['unit'] = BadgeUnit::select('id', 'actual_name', 'short_name')
            ->find($input['unit_id']);

        return $this->sendResponse($badge, 'Badge has been created successfully.');
    }

    /**
     * @OA\Get(
     *      path="/api/v1/badges/{id}",
     *      operationId="badges-single",
     *      tags={"Badges"},
     *      summary="Singel badge",
     *      description="Returns single badge",
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
     *              @OA\JsonContent(type="object",example = {"success":true,"data":{"id":4,"name":"Aspen Morse","display_name":"Alfreda Eaton","target":50,"description":"Aliquid veniam quo","deleted_at":null,"created_at":"2020-02-16 01:30:13","updated_at":"2020-02-16 01:30:13","created_by":{"id":1,"name":"Admin User","email":"admin@example.com","phone":"0111"}},"message":"Badge retrieved successfully."})
     *          )
     *       ),
     * )
     *
     */
    public function show($id)
    {
        $badge = Badge::with('createdBy', 'unit')->find($id);

        if (is_null($badge)) {
            return $this->sendError('Badge not found.');
        }

        return $this->sendResponse($badge, 'Badge retrieved successfully.');
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:30|unique:badges,name,' . $id,
            'unit_id' => 'required|exists:units,id',
            'target' => 'nullable|regex:/^\d+(\.\d{1,3})?$/',
            'description' => 'nullable|max:200',
            'badge_icon' => 'nullable|mimes:jpeg,jpg,png,gif|max:1024',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Prerequisite failed.', $validator->errors(), 422);
        }
        $input = $request->only(['name', 'target', 'description', 'unit_id']);

        $badge = Badge::findOrFail($id);

        if($request->hasFile('badge_icon'))
        {
            $imageID = Uuid::uuid4()->toString();
            $imageName = $imageID . '.' . $request->file('badge_icon')->getClientOriginalExtension();
            $thumb_200x200 = 'thumb_200x200_' . $imageName;
            if (!file_exists(public_path('images/badges/thumb/'))) {
                File::makeDirectory(public_path('images/badges/thumb/'),0755, true);
            }
            $request->file('badge_icon')->move(
                public_path('images/badges/'), $imageName
            );
            $path = public_path('images/badges/') . $imageName;
            Image::make($path)->resize(200, 200)->save(public_path('images/badges/thumb/') . $thumb_200x200);
            $input['badge_icon'] = $imageName;

            // unlink old file
            if (file_exists(public_path() . '/images/badges/' . $badge->badge_icon) && $badge->badge_icon != null) {
                @unlink(public_path() . '/images/badges/thumb/thumb_200x200_' . $badge->badge_icon);
                @unlink(public_path() . '/images/badges/' . $badge->badge_icon);
            }
            $input['badge_icon'] = $imageName;
        }

        $badge->update($input);

        return $this->sendResponse($badge, 'Badge has been updated successfully.');
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/badges/{id}",
     *      operationId="destroy-badge",
     *      tags={"Badges"},
     *      summary="Delete badge.",
     *      description="Badge has been deleted successfully.",
     *      @OA\Parameter(
     *          name="Authorization",
     *          description="Bearer token",
     *          required=true,
     *          in="header",
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Badge has been deleted successfully.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\JsonContent(type="object",example = {"success":true,"data":"","message":"Badge has been deleted successfully."})
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
            Badge::find($id)->delete();
            return $this->sendResponse([], 'Badge has been deleted successfully.');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), '', 422);
        }
    }

}
