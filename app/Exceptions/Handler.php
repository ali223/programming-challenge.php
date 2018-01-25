<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof ValidationException) {
            return response()->json([
                'errors' => [
                    'status' => $e->status,
                    'title' => $e->getMessage(),
                    'details' => $e->validator->errors()
                ]
            ], $e->status);
        }

        if ($e instanceof ModelNotFoundException) {
            return response()->json([
                'errors' => [
                    'status' => 404,
                    'title' => 'Resource Not Found',
                    'details' => 'Cannot find Resource with the id ' . implode(', ', $e->getIds())
                ]
            ], 404);
        }


        return parent::render($request, $e);
    }
}
