<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Validation\ValidationException;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    private $sentryID;
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [ 
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
        
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {   
        if ($this->shouldReport($exception)) {

            $this->sentryID = app('sentry')->captureException($exception);
        }
     
        parent::report($exception);        
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        //My custom exceptions
        if($exception instanceof TooManyArgumentsException){
            if ($request->ajax()) {
                return response()->json(['error' => 'Invalid request'], 400);
            }
            return response()->view('errors.400', [], 400);
        }
        //Some responses taken form the parent::render(); (My goal is that in the end, an exception will be handeled with sentry)
        
        $e = $this->prepareException($exception);
        if ($e instanceof HttpResponseException) {
            return $e->getResponse();
        } elseif ($e instanceof AuthenticationException) {
            return $this->unauthenticated($request, $e);
        } elseif ($e instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($e, $request); 
        } elseif($e instanceof NotFoundHttpException){
            return response()->view('errors.404', [], 404);
        }
        //
        //return parent::render($request, $exception);

        if(env('APP_DEBUG') === false){
            return response()->view('errors.500', [
                'sentryID' => $this->sentryID,
            ], 500);
        }
        return $this->prepareResponse($request, $e);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('/livedemo/companywatchlist/login');
    }
}
