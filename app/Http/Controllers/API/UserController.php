<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Profile;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $users = User::with('roles')->select(
            'id', 'name', 'email', 'phone')->paginate(10);

        return new UserCollection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'role' => 'required|in:admin,staff,subscriber',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $user->profile()->save(new Profile());
        $roleIDArr = Role::where('name', $input['role'])->pluck('id');
        $user->roles()->attach($roleIDArr);

        return $this->sendResponse(new UserResource($user), 'User has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = User::find($id);

        if (is_null($user)) {
            return $this->sendError('User not found.');
        }

        return $this->sendResponse(new ProfileResource($user), 'User retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Profile $profile
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Profile $profile)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'bio' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $profile->bio = $input['bio'];
        $profile->save();

        return $this->sendResponse(new UserResource($profile), 'Profile updated successfully.');
    }

    /**
     * @OA\Delete(
     *      path="/api/v1/users/{id}",
     *      operationId="destroy-user",
     *      tags={"Profile"},
     *      summary="Delete user.",
     *      description="User has been deleted successfully.",
     *      @OA\Parameter(
     *          name="Authorization",
     *          description="Bearer token",
     *          required=true,
     *          in="header",
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User has been deleted successfully.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\JsonContent(type="object",example = {"success":true,"data":"","message":"User has been deleted successfully."})
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
    public function destroy(User $user)
    {
        if (Auth::id() != $user->id) {
            $user->delete();
            return $this->sendResponse([], 'User has been deleted successfully.');
        }
        return $this->sendError('Failed to delete.', [], 403);
    }
}
