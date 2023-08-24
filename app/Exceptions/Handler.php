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
    public function render($request, \Exception $exception)
{
    // global exception handler if api request for non existing object id
    if ($request->wantsJson() && $exception->getMessage() == 'Trying to get property of non-object') {
        return response()->json([
            'status' => 'object requested not found'
        ], 404);
    }

    return parent::render($request, $exception);
}


}
