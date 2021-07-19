<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

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
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($request->is('api') || $request->is('api/*')) {
            if ($exception instanceof ValidationException) {

                return response()->json([
                    'result' => __('statusPw.E00900001003'),
                    'returnValue' => [
                        "return_code" => __('returnCode.Parameter_Error_00-01-000002'),
                        "emplex_customer_no" => null
                    ]
                ]);
            } else {
                return response()->json([
                    'message' => [
                        'errors' => $exception->getMessage(),
                        'line' => $exception->getTrace(),
                    ]
                ], 500);
            }
        }

        return parent::render($request, $exception);
    }

}
