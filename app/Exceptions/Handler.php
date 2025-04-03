<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Log;

use Spatie\LaravelIgnition\Exceptions\ViewException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        // $this->reportable(function (Throwable $e) {
        //     //
        // });

        $this->renderable(function (Throwable $e, $request) {

            return $this->handleException($request, $e);
        });
    }

    public function handleException($request, \Exception $exception)
    {
        // if($exception instanceof NotFoundHttpException) {

        //     return response()->view('components/errors500', [], 404);
        // }

        if ($exception instanceof MethodNotAllowedHttpException) {
            Log::info("The bad request cannot be  found");
            // return response(['message' => 'The bad request cannot be  found.'], 404);
        }

        // if($exception instanceof QueryException) 
        // {
        //     return response(['message'=>$exception], 404);
        // }

        // if($exception instanceof TooManyRequestsHttpException) {

        //     return response(['message'=>'Too Many Request.'], 500);
        // }

        // if ($exception instanceof QueryException && $exception->getStatusCode() === 'HY000') 
        // {
        //     // Handle the specific QueryException here
        //     return response()->view('components/errors500', [], 500); // Return the custom view with a 500 status code
        // }

        // if ($exception instanceof HttpException && $exception->getStatusCode() == 500) 
        // {
        //     return response()->view('components/errors500', [], 500);
        // }

        if ($exception instanceof HttpException && $exception->getStatusCode() == 419) {
            return redirect()->route('/');
        }

        // if ($exception instanceof ViewException) 
        // {
        //     // Handle the specific QueryException here
        //     return response()->view('components/errors500', [], 500); // Return the custom view with a 500 status code
        // }


    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson() || $request->is('api*')) {
            return response()->json(['error' => __('unauthenticated')], 401);
        }

        return redirect()->guest(route('/'));
    }
}
