<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // if($exception instanceof \ErrorException){
        //     if ($request->ajax()) {
        //         return response()->json(['error' => trans('error.500_error')], 500);
        //     }
        //     return response()->view('vendor.errors.page', ['code' => 500, 'message' => trans('error.500_error')]);
        // }

        if ($exception instanceof NotFoundHttpException) {
            if ($request->ajax()) {
                return response()->json(['error' => trans('error.page.not_found')], 404);
            }
            return response()->view('vendor.errors.page', ['code' => 404, 'message' => trans('error.page.not_found')]);
        }
        if ($exception instanceof UnauthorizedException) {
            if ($request->ajax()) {
                return response()->json(['error' => $exception->getMessage()], 403);
            }
            return response()->view('vendor.errors.page', ['code' => 403, 'message' => $exception->getMessage()]);
        }
        if ($exception instanceof AuthenticationException) {
            if ($request->ajax()) {
                return response()->json(['error' => $exception->getMessage()], 401);
            }
        }

        return parent::render($request, $exception);
    }
}
