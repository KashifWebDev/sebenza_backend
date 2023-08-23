<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\QueryException;
use Illuminate\Encryption\MissingAppKeyException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *s
     * @return void
     */
    public function register()
    {
        $this->renderable(function (QueryException  $e) {
            return response()->json([
                    "status"=>false,
                    'message' => "No query found for this url",
                ], 404);
        });



        $this->renderable(function (MissingAppKeyException  $e) {
            return response()->json([
                    "status"=>false,
                    'message' => "CSRF Token not match.",
                ], 404);
        });


    }

    public function render($request, Exception $exception) {

        if ($request->ajax()) {

            //custom ajax errors
            switch ($exception->getStatusCode()) {

            //permission denied
            case 403:
                $response = [
                    "status"=>false,
                    'message' => "No query found for this url",
                ];
                break;

            //larevel session timeout
            case 419:
                $response = [
                    "status"=>false,
                    'message' => "No query found for this url",
                ];
                break;

            //not found
            case 404:
                $response = [
                    "status"=>false,
                    'message' => "No query found for this url",
                ];
                break;

            //business logic/generic errors
            case 409:
                $response = [
                    "status"=>false,
                    'message' => "No query found for this url",
                ];
                break;

            default:
                $response = [
                    "status"=>false,
                    'message' => "No query found for this url",
                ];
                break;
            }

            //return response - with error code
            return response()->json(array('message' => $response), $exception->getStatusCode());
        }

        //default laravel response
        return parent::render($request, $exception);
    }

}
