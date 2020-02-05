<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\ProfileResource;
use App\Http\Resources\UserResource;
use App\Profile;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Amar Steps - Swagger API ",
 *      description="L6 Swagger OpenApi description",
 *      @OA\Contact(
 *          email="nshakib.se@gmail.com"
 *      )
 * )
 */
/**
 *  @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Sandbox OpenApi dynamic host server"
 *  )
 *
 */

class RegisterController extends BaseController
{
    /**
     * @OA\Post(
     *      path="/api/v1/register",
     *      operationId="register",
     *      tags={"Registration"},
     *      summary="Register Users!",
     *      description="Returns user name email and token.",
     *      @OA\Parameter(
     *          name="phone",
     *          description="Phone Number",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="email",
     *          description="User Email",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="password",
     *          description="User Password",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="password_confirmation",
     *          description="Password Confirmation",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Account has been created successfully.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *       ),
     *       @OA\Response(response=400, description="Bad request"),
     *     )
     *
     * Returns with token
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|unique:users,phone',
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
        $roleIDArr = Role::where('name', 'subscriber')->pluck('id');
        $user->roles()->attach($roleIDArr);

        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['phone'] = $user->phone;

        return $this->sendResponse($success, 'Account has been created successfully.');
    }

    /**
     * @OA\Post(
     *      path="/api/v1/login",
     *      operationId="login",
     *      tags={"Registration"},
     *      summary="Login Users!",
     *      description="Returns user details with token",
     *      @OA\Parameter(
     *          name="email",
     *          description="User Email",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *      ),
     *      @OA\Parameter(
     *          name="password",
     *          description="User password",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User login successfully.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *       ),
     *       @OA\Response(response=401, description="Unauthorised"),
     *     )
     *
     * Returns with token
     */
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')->accessToken;
            $success['auth'] = new UserResource($user);

            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Invalid credential.'], 401);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/v1/profile",
     *      operationId="profile",
     *      tags={"Profile"},
     *      summary="Get auth information",
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
     *          description="Retrieve auth profile.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *       ),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *      security={
     *         {
     *             "oauth2_security_example": {"write:auth", "read:auth"}
     *         }
     *     },
     * )
     *
     */
    public function profile()
    {
        $profile = User::find(Auth::id());
        return $this->sendResponse(new ProfileResource($profile), 'Retrieve auth profile.');
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            //'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'gender' => 'required|in:male,female',
            'address' => 'string|nullable',
            'bio' => 'string|nullable',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }
    }
}
