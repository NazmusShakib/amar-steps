<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Twilio\TwiML\VoiceResponse;
use Twilio\TwiML\MessagingResponse;

class PhoneVerificationController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function show(Request $request)
    {
        if (!$request->user()->hasVerifiedPhone()) {
            try {
                $request->user()->callToVerify();
            } catch (\Exception $exception) {
                $validator = ValidationException::withMessages([
                    'phone' => $exception->getMessage(),
                ]);
                return $this->sendError('Prerequisite failed.', $validator->errors(), 422);
            }
        }
        return $this->sendResponse($request->user(), 'Thanks for registering with our platform. We will text you to verify your phone number in a jiffy. Provide the code below.');
    }

    /**
     * @OA\Post(
     *      path="/api/v1/phone/verify",
     *      operationId="post-phone-verify",
     *      tags={"Registration"},
     *      summary="Verify phone.",
     *      description="Your phone was successfully verified.",
     *      @OA\Parameter(
     *          name="Authorization",
     *          description="Bearer token",
     *          required=true,
     *          in="header",
     *          @OA\Schema(type="string"),
     *      ),
     *      @OA\Parameter(
     *          name="code",
     *          description="Verification code",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Your phone was successfully verified.",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *       ),
     *       @OA\Response(response=401, description="Unauthorised"),
     *     )
     *
     * Returns with token
     */
    public function verify(Request $request)
    {
        if ($request->user()->verification_code !== $request->code) {
            $validator = ValidationException::withMessages([
                'code' => ['The code your provided is wrong. Please try again or request another text.'],
            ]);

            return $this->sendError('Prerequisite failed.', $validator->errors(), 422);
        }

        if ($request->user()->hasVerifiedPhone()) {
            return $this->sendResponse($request->user(), 'Your phone is verified already.');
        } elseif ($request->user()->markPhoneAsVerified()) {
            return $this->sendResponse($request->user(), 'Your phone was successfully verified.');
        }

        return $this->sendError('Session has been expired; you must log in again.', [], 440);
    }

    public function buildTwiML($code)
    {
        // $code = $this->formatCode($code);
        $response = new MessagingResponse();
        $response->message("Congratulations, your account has been reviewed and verified.");
        echo $response;
    }

    public function formatCode($code)
    {
        $collection = collect(str_split($code));
        return $collection->reduce(
            function ($carry, $item) {
                return "{$carry}. {$item}";
            }
        );
    }
}
