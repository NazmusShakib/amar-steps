<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\ProfileResource;
use App\Http\Resources\UserResource;
use App\Profile;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Validation\ValidationException;

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
 * @OA\Server(
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
            'phone' => 'required|regex:/[0-9]{11}/|digits:11|unique:users,phone',
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

        try {
            $user->callToVerify();
        } catch (\Exception $exception) {
            $validator = ValidationException::withMessages([
                'phone' => $exception->getMessage(),
            ]);
            $user->delete();
            Session::flush();
            return $this->sendError('Prerequisite failed.', $validator->errors(), 422);
        }

        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['auth'] = new ProfileResource($user);

        return $this->sendResponse($success, 'Thanks for registering with our platform. We will text a verification code the given number in a jiffy. Provide the code below.');
    }

    /**
     * @OA\Post(
     *      path="/api/v1/login",
     *      operationId="login",
     *      tags={"Registration"},
     *      summary="Login Users!",
     *      description="Returns user details with token",
     *      @OA\Parameter(
     *          name="phone",
     *          description="User Phone",
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
        $remember_me = $request->has('remember') ? true : false;
        if (Auth::attempt(['phone' => $request->phone, 'password' => $request->password], $remember_me)) {
            $user = Auth::user();

            if(!$user->phone_verified_at)
                try {
                    $user->callToVerify();
                } catch (\Exception $exception) {
                    $validator = ValidationException::withMessages([
                        'phone' => $exception->getMessage(),
                    ]);
                    return $this->sendError('Prerequisite failed.', $validator->errors(), 422);
                }

            $success['token'] = $user->createToken('MyApp')->accessToken;
            $success['auth'] = new ProfileResource($user);

            return $this->sendResponse($success, 'I have logged in successfully.');
        } else {
            return $this->sendError('Invalid credential.', ['error' => ['Invalid credential.']], 401);
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
            'email' => 'required|email|unique:users,email,' . $request->user()->id,
            'height' => 'required',
            'weight' => 'required',
            'gender' => 'nullable|in:male,female',
            'address' => 'string|nullable',
            'bio' => 'string|nullable',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation failed.', $validator->errors(), 422);
        }

        $userOnly = $request->only('name', 'email', 'height', 'weight');
        $request->user()->update($userOnly);

        $profileOnly = $request->only('gender', 'bio', 'address');
        $request->user()->profile()->update($profileOnly);

        $profile = new ProfileResource($request->user());

        return $this->sendResponse($profile, 'Profile has been updated successfully.');
    }

    /**
     * @OA\Post(
     *      path="/api/v1/profile/change-password",
     *      operationId="change-password",
     *      tags={"Profile"},
     *      summary="Auth password changed.",
     *      description="Returns updated details.",
     *      @OA\Parameter(
     *          name="Change password",
     *          description="application/json",
     *          required=true,
     *          in="query",
     *          @OA\Schema(type="object",example = {"old_password":"123456","password":"123abc","password_confirmation":"123abcd"}),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Password has been changed successfully.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\JsonContent(type="object",example = {"success":true,"data":{"id":1,"name":"Admin User","email":"admin@example.com","phone":"+2992233690457","role":"Administrator","profile":{"gender":"male","dob":"1970-07-11","bio":"Duchess, 'chop off her knowledge, as there was no more to do that,' said the Cat, and vanished. Alice was soon submitted to by all three dates on.","address":"7749 Dana Trail Suite 868\nGibsonmouth, HI 32195-3665"}},"message":"Password has been changed successfully."})
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation failed.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\JsonContent(type="object",example = {"success":false,"message":"Validation failed.","errors": {}})
     *          )
     *      )
     * )
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => ['required', function ($attribute, $value, $fail) use ($request) {
                if (!Hash::check($value, $request->user()->password)) {
                    $fail('Old Password didn\'t match.');
                }
            }],
            'password' => 'required|min:6|confirmed|different:old_password',
            // 'password_confirmation' => 'required|same:password|different:old_password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation failed.', $validator->errors(), 422);
        }

        $request->user()->fill([
            'password' => Hash::make($request->password)
        ])->save();

        $profile = new ProfileResource($request->user());

        return $this->sendResponse($profile, 'Password has been changed successfully.');
    }
}
