<?php
    namespace App\Exceptions;
    use Exception;
    use Request;
    use Illuminate\Auth\AuthenticationException;
    use Response;
    use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
    class Handler extends ExceptionHandler
    {
        //use RestTrait;
        //use RestExceptionHandlerTrait;

        // /**
        //  * A list of the exception types that are not reported.
        //  *
        //  * @var array
        //  */
        // protected $dontReport = [
        //     //
        // ];

        // /**
        //  * A list of the inputs that are never flashed for validation exceptions.
        //  *
        //  * @var array
        //  */
        // protected $dontFlash = [
        //     'password',
        //     'password_confirmation',
        // ];

        // /**
        //  * Report or log an exception.
        //  *
        //  * @param  \Exception  $exception
        //  * @return void
        //  */
        // public function report(Exception $exception)
        // {
        //     parent::report($exception);
        // }

        // /**
        //  * Render an exception into an HTTP response.
        //  *
        //  * @param  \Illuminate\Http\Request  $request
        //  * @param  \Exception  $e
        //  * @return \Illuminate\Http\Response
        //  */
        // public function render($request, Exception $e)
        // {
        //     // if(!$this->isApiCall($request)) {
        //     //     $retval = parent::render($request, $e);
        //     // } else {
        //         $retval = $this->getJsonResponseForException($request, $e);
        //     // }

        //     return $retval;
        // }

        
        /**
         * Convert an authentication exception into a response.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  \Illuminate\Auth\AuthenticationException  $exception
         * @return \Illuminate\Http\Response
         */
         protected function unauthenticated($request, AuthenticationException $exception)
         {
            return $request->expectsJson()
                    ? response()->json(['message' => 'Unauthenticated.'], 401)
                    : redirect()->guest(route('login'));
        }
}