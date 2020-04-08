<?php

namespace App\Exceptions;

use Exception;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     * @param Exception $exception
     * @return mixed|void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     */
    public function render($request, Exception $exception)
    {
        if ($this->isHttpException($exception)) {
            switch ($exception->getStatusCode()) {

                // Forbidden
                case '403':
                    return response()->json([
                        'success' => false,
                        'message' => 'Forbidden',
                    ], 403);
                    break;

                // not found
                case '404':
                    return response()->json([
                        'success' => false,
                        'message' => 'Not Found',
                    ], 404);
                    break;

                // Method not allowed
                case '405':
                    return response()->json([
                        'success' => false,
                        'message' => $exception->getMessage(),
                    ], 405);
                    break;

                // internal error
                case '500':
                    return response()->json([
                        'success' => false,
                        'message' => $exception->getMessage(),
                    ], 500);
                    break;

                default:
                    return $this->renderHttpException($exception);
                    break;
            }
        } else {

            if ($exception instanceof ModelNotFoundException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Entry for '.str_replace('App\\', '', $exception->getModel()).' not found',
                ], 404);
            } else if ($exception instanceof RequestException) {
                return response()->json([
                    'success' => false,
                    'message' => 'External API call failed.',
                ], 500);
            }

            return parent::render($request, $exception);
        }
    }
}
