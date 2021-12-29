<?php

namespace App\Exceptions;

use App\Traits\CommonResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\ModelNotFoundException as SM;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\RouteNotFoundException;
use Throwable;
use \Illuminate\Database\Eloquent\ModelNotFoundException;
use \Illuminate\Validation\ValidationException;
class Handler extends ExceptionHandler
{
  use CommonResponse;
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
     *
     * @return void
     * See more for json response code
     * https://gist.github.com/jeffochoa/a162fc4381d69a2d862dafa61cda0798
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {

        });
        $this->renderable(function (Throwable $exception, $request) {
            if ($request->wantsJson()) {
                $msg = config('message');
                if ($exception instanceof SM || $exception instanceof ModelNotFoundException) {
                    return $this->sendFailedResponse([], $msg['not_found'], JsonResponse::HTTP_NOT_FOUND);
                } else if ($exception instanceof MethodNotAllowedHttpException) {
                    return $this->sendFailedResponse([], $msg['not_allowed'], JsonResponse::HTTP_METHOD_NOT_ALLOWED);
                } else if ($exception instanceof ValidationException) {
                    return $this->sendFailedResponse(
                        $this->transformValidationErrors($exception->errors()),
                        $msg['form_error'],
                        JsonResponse::HTTP_UNPROCESSABLE_ENTITY
                    );
                } else if ($exception instanceof AuthorizationException) {
                    return $this->sendFailedResponse([], $msg['forbidden'], JsonResponse::HTTP_FORBIDDEN);
                } else if ($exception instanceof AuthenticationException) {
                    return $this->sendFailedResponse([], $msg['unauth'], JsonResponse::HTTP_UNAUTHORIZED);
                } else if ($exception instanceof NotFoundHttpException || $exception instanceof RouteNotFoundException) {
                    return $this->sendFailedResponse([], $msg['not_found'], JsonResponse::HTTP_NOT_FOUND);
                }
                // else if ($exception instanceof QueryException) {
                //     //dd('here');
                //     return $this->sendFailedResponse([], $msg['server_error'], 500);
                // }
            }
        });
    }

    /**
     * Parse validation errors.
     *
     * @param array $errors
     * @return JsonResponse
     */
    public function transformValidationErrors(array $errors)
    {
      $transformedErrors = [];

      foreach ($errors as $field => $message) {
          $transformedErrors[] = [
              'field' => $field,
              'message' => $message[0],
          ];
      }
      return count($transformedErrors) > 0 ? $transformedErrors[0]['message'] : 'Error occurred.';
    }
}
