<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
        ValidationException::class
       // ParentFinderException::class,
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
        if (config('app.debug')) {
            return parent::render($request, $e);
        }
            /*customized handler */
            return $this->handle($request, $e);
    }

    /**
     * Convert the Exception into a JSON HTTP Response
     *
     * @param Request $request
     * @param Exception $e
     * @return JSONResponse
     */
      function handle($request, Exception $e) {
        
        if ($e instanceOf ParentFinderException) {echo "a";
            $data   = $e->toArray();
            $status = $e->getStatus();
            $errorList=Array(
                                "status"=>'Failed',
                                "message"=> $data['status'] ,
                                "details"=>$data['detail'] 
                                );
           
        }
     
      else if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {echo "22";
            $data = array_merge([
                'id'     => 'not_found',
                'status' => '404'
            ], config('errors.not_found'));
     
            $status = 404;
        }     
        else{
            $errorList=Array("status"=>'Failed',
                          "Message"=> $e->getMessage()
                          );
            print_r($errorList);
        }
      return json_encode($errorList); 
       
    }
}
