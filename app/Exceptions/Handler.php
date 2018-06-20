<?php

namespace App\Exceptions;


use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [];

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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Exception $exception
     */
    public function report(Exception $exception)
    {
        if (!app()->isLocal() && app()->bound('sentry') && $this->shouldReport($exception)) {
            app('sentry')->captureException($exception);
        } else {
            parent::report($exception);
        }
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $exception
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $custom_render = true;

        if ($exception instanceof MaintenanceModeException) {
            return response()->view('errors.503');
        } else if ($exception instanceof NotFoundHttpException) {
            $code = $exception->getStatusCode();
            $message = 'Not found.';
        } else if ($exception instanceof MethodNotAllowedHttpException) {
            $code = $exception->getStatusCode();
            $message = 'Method not allowed.';
        } else {
            $custom_render = false;
        }

        if ($custom_render) {
            return response()->view('error', ['description' => $code . ' - ' . $message . '<br />'], $code);
        } else {
            return parent::render($request, $exception);
        }
    }
}
