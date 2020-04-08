<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Ramsey\Uuid\Uuid;

class PostController extends BaseController
{
    /**
     * @OA\Get(
     *      path="/api/v1/posts",
     *      operationId="posts-get",
     *      tags={"Posts"},
     *      summary="Get all posts",
     *      description="Returns posts",
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
     */
    public function index()
    {
        $posts = Post::select(
            'id', 'title', 'description', 'thumbnail',
            'created_at', 'updated_at')
            ->orderBy('created_at', 'DESC')->get();

        return response()->json($posts, 200);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/posts",
     *      operationId="post-store",
     *      tags={"Posts"},
     *      summary="Create post.",
     *      description="Post has been created successfully.",
     *      @OA\Parameter(
     *          name="Authorization",
     *          description="Bearer token",
     *          required=true,
     *          in="header",
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\Parameter(
     *          name="title",
     *          description="Title",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="description",
     *          description="description",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="text",
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="thumbnail",
     *          description="thumbnail",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="file",
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Post has been created successfully.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\JsonContent(type="object",example = {"success":true,"data":{"saved data"},"message":"Post has been created successfully."})
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
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:50',
            'thumbnail' => 'nullable|mimes:jpeg,jpg,png,gif|max:6024',
            'description' => 'nullable|max:300',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Prerequisite failed.', $validator->errors(), 422);
        }
        $input = $request->only(['title', 'description']);
        if($request->hasFile('thumbnail'))
        {
            $imageID = Uuid::uuid4()->toString();
            $imageName = $imageID . '.' . $request->file('thumbnail')->getClientOriginalExtension();
            $thumb_200x200 = 'thumb_200x200_' . $imageName;
            if (!file_exists(public_path('images/posts/thumb/'))) {
                File::makeDirectory(public_path('images/posts/thumb/'),0755, true);
            }
            $request->file('thumbnail')->move(
                public_path('images/posts/'), $imageName
            );
            $path = public_path('images/posts/') . $imageName;
            Image::make($path)->resize(200, 200)->save(public_path('images/thumbnail/thumb/') . $thumb_200x200);
            $input['thumbnail'] = $imageName;
        }

        $post = Post::create($input);

        return $this->sendResponse($post, 'Post has been created successfully.');
    }

    /**
     * @OA\Get(
     *      path="/api/v1/posts/{id}",
     *      operationId="posts-single",
     *      tags={"Posts"},
     *      summary="Singel post",
     *      description="Returns single post",
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
     *          description="Retrieve single post by id.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\JsonContent(type="object",example = {"success":true,"data":{"id":4,"title":"Aspen Morse","description":"Aliquid veniam quo","thumbnail": "uuid.mime", "deleted_at":null,"created_at":"2020-02-16 01:30:13","updated_at":"2020-02-16 01:30:13","created_by":{"id":1,"name":"John Doe","email":"createor@example.com","phone":"023782732"}},"message":"Post retrieved successfully."})
     *          )
     *       ),
     * )
     */
    public function show($id)
    {
        $post = Post::with('createdBy')->find($id);

        if (is_null($post)) {
            return $this->sendError('Post not found.');
        }

        return $this->sendResponse($post, 'Post retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:50',
            'description' => 'nullable|max:300',
            'thumbnail' => 'nullable|mimes:jpeg,jpg,png,gif|max:6024',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Prerequisite failed.', $validator->errors(), 422);
        }
        $input = $request->only(['title', 'description']);

        $badge = Post::findOrFail($id);

        if($request->hasFile('thumbnail'))
        {
            $imageID = Uuid::uuid4()->toString();
            $imageName = $imageID . '.' . $request->file('thumbnail')->getClientOriginalExtension();
            $thumb_200x200 = 'thumb_200x200_' . $imageName;
            if (!file_exists(public_path('images/posts/thumb/'))) {
                File::makeDirectory(public_path('images/posts/thumb/'),0755, true);
            }
            $request->file('thumbnail')->move(
                public_path('images/posts/'), $imageName
            );
            $path = public_path('images/posts/') . $imageName;
            Image::make($path)->resize(200, 200)->save(public_path('images/posts/thumb/') . $thumb_200x200);
            $input['thumbnail'] = $imageName;

            // unlink old file
            if (file_exists(public_path() . '/images/posts/' . $badge->thumbnail) && $badge->thumbnail != null) {
                @unlink(public_path() . '/images/posts/thumb/thumb_200x200_' . $badge->thumbnail);
                @unlink(public_path() . '/images/posts/' . $badge->thumbnail);
            }
            $input['thumbnail'] = $imageName;
        }

        $badge->update($input);

        return $this->sendResponse($badge, 'Post has been updated successfully.');
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/posts/{id}",
     *      operationId="destroy-post",
     *      tags={"Posts"},
     *      summary="Delete post.",
     *      description="Post has been deleted successfully.",
     *      @OA\Parameter(
     *          name="Authorization",
     *          description="Bearer token",
     *          required=true,
     *          in="header",
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Post has been deleted successfully.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\JsonContent(type="object",example = {"success":true,"data":"","message":"Post has been deleted successfully."})
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
            Post::findOrFail($id)->delete();
            return $this->sendResponse([], 'Post has been deleted successfully.');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), '', 422);
        }
    }
}
