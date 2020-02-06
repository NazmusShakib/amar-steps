<?php

namespace App\Http\Controllers\API;

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
                return $this->sendError('Validation Error.', $validator->errors(), 422);
            }
        }
        return $this->sendResponse($request->user(), 'Thanks for registering with our platform. We will text you to verify your phone number in a jiffy. Provide the code below.');
    }

    public function verify(Request $request)
    {
        if ($request->user()->verification_code !== $request->code) {
            $validator = ValidationException::withMessages([
                'code' => ['The code your provided is wrong. Please try again or request another text.'],
            ]);

            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        if ($request->user()->hasVerifiedPhone()) {
            return $this->sendResponse($request->user(), 'Your phone is verified already!');
        }

        $request->user()->markPhoneAsVerified();
        return $this->sendResponse($request->user(), 'Your phone was successfully verified!');
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
