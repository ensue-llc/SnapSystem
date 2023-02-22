<?php

namespace Ensue\Snap\Exceptions;

use Ensue\Snap\Constants\SnapConstant;
use Ensue\Snap\Foundation\SnapResponseTraits;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

abstract class SnapExceptionHandler extends Handler
{
    use SnapResponseTraits;

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
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Exception $e
     * @return JsonResponse|Response
     * @throws Exception|Throwable
     */
    public function render($request, Throwable $e): Response|JsonResponse
    {
        if ($this->isApiRequest($request)) {
            if ($e instanceof HttpException && $request->getMethod() === 'OPTIONS') {
                return new Response();
            }
            if ($e instanceof AuthenticationException) {
                return $this->responseUnAuthorize($e->getMessage());
            } else if ($e instanceof SnapException) {
                return $this->nicoResponse($e->getResponseBody(), $e->getCode(), $e->getMessage(), $e->getResponseCode());
            } elseif ($e instanceof ValidationException) {
                return $this->responseValidationError($e->errors());
            } elseif ($e instanceof ModelNotFoundException) {
                return $this->responseNotFound($e->getMessage());
            } elseif ($e instanceof HttpException) {
                return $this->nicoResponse('', $e->getStatusCode(), $e->getMessage(), SnapConstant::getAppMsgCodeFromStatusCode($e->getStatusCode()));
            }

            return $this->nicoResponse(null, $e->getCode(), $e->getMessage() . " " . $e->getFile() . ": line " . $e->getLine(), $e->getCode());
        }

        return parent::render($request, $e);
    }

    /**
     * Test if the request is an API call
     * @param Request $request
     * @return boolean
     */
    protected function isApiRequest(Request $request): bool
    {
        return $request->segment(1) === 'api';
    }
}
