<?php

namespace Ensue\Snap\Foundation;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

/**
 * Trait NicoResponseTraits
 * @package Ensue\Snap\Foundation
 */
trait SnapResponseTraits
{
    protected bool $api = true;

    public function responseOk($body, $codeText = 'ok', $messageCode = 'snap::snap.ok', array $headers = []): JsonResponse
    {
        return $this->nicoResponse($body, Response::HTTP_OK, $codeText, $messageCode, $headers);
    }

    public function nicoResponse($body, int $status = Response::HTTP_OK, $codeText = 'OK', string $messageCode = 'snap::snap.ok', array $headers = []): JsonResponse
    {
        if ($body instanceof Collection) {
            if ($body->count() > 0) {
                $body = new LengthAwarePaginator($body->all(), $body->count(), $body->count());
            } else {
                $body = new Paginator($body->all(), $body->count());
            }
        }
        $status = $this->validateStatusCode($status);
        $split = explode('.', $messageCode);
        return response()->json([
            'body' => $body,
            'status' => [
                "message" => trans($messageCode),
                'code' => end($split),
                'code_text' => $codeText,
            ]
        ], $status)->withHeaders($headers);
    }

    public function validateStatusCode(int $code = 0): int
    {
        $statusCode = array(
            100, 101, 102,
            200, 201, 202, 203, 204, 205, 206, 207, 208, 226,
            300, 301, 302, 303, 304, 305, 306, 307, 308,
            400, 401, 402, 403, 404, 405, 406, 407, 408, 409, 410, 411, 412, 413, 414, 415, 416, 417, 418, 421, 422, 423, 424, 425, 426, 428, 429, 431, 451,
            500, 501, 502, 503, 504, 505, 506, 507, 508, 510, 511
        );
        if (in_array($code, $statusCode)) {
            return $code;
        }

        return 500;
    }

    public function responseServerError($codeText = 'internal server error occured', string $code = 'snap::snap.internal_server_error', array $headers = []): JsonResponse
    {
        return $this->responseError(null, Response::HTTP_INTERNAL_SERVER_ERROR, $codeText, $code, $headers);
    }

    public function responseError($body, int $status = Response::HTTP_INTERNAL_SERVER_ERROR, $codeText = 'server error', $messageCode = 'server_error', array $headers = []): JsonResponse
    {
        return $this->nicoResponse($body, $status, $codeText, $messageCode, $headers);
    }

    public function responseUnAuthorize(string $codeText = 'unauthorized', string $code = 'snap::snap.unauthorized', array $headers = []): JsonResponse
    {
        $split = explode('.', $code);
        return response()->json([
            'body' => null,
            'status' => [
                "message" => trans($code),
                'code' => end($split),
                'code_text' => $codeText,
            ]
        ], Response::HTTP_UNAUTHORIZED)->withHeaders($headers);
    }

    public function responseForbidden(string $codeText = 'forbidden', string $code = 'snap::snap.forbidden', array $headers = []): JsonResponse
    {
        return $this->responseError(null, Response::HTTP_FORBIDDEN, $codeText, $code, $headers);
    }

    public function responseNotFound(string $codeText = 'not found', string $code = 'snap::snap.not_found', array $headers = []): JsonResponse
    {
        return $this->responseError(null, Response::HTTP_NOT_FOUND, $codeText, $code, $headers);
    }

    public function responseBadRequest(string $codeText = 'bad request', string $code = 'snap::snap.bad_request', array $headers = []): JsonResponse
    {
        return $this->responseError(null, Response::HTTP_BAD_REQUEST, $codeText, $code, $headers);
    }

    public function responsePreConditionFailed($body = '', string $codeText = 'precondition failed', string $code = 'snap::snap.precondition_failed', array $headers = []): JsonResponse
    {
        return $this->responseError($body, Response::HTTP_PRECONDITION_FAILED, $codeText, $code, $headers);
    }

    public function responseConflict($body = null, string $codeText = 'conflict', string $code = 'snap::snap.conflict', array $headers = []): JsonResponse
    {
        return $this->responseError($body, Response::HTTP_CONFLICT, $codeText, $code, $headers);
    }

    public function responseExpectationFailed($body = null, string $codeText = 'expectation failed', string $code = 'snap::snap.expectation_failed', array $headers = []): JsonResponse
    {
        return $this->responseError($body, Response::HTTP_EXPECTATION_FAILED, $codeText, $code, $headers);
    }

    public function responseValidationError($body = null, string $codeText = 'form validation failed', $code = 'snap::snap.form_validation_error', array $headers = []): JsonResponse
    {
        return $this->responseError($body, Response::HTTP_EXPECTATION_FAILED, $codeText, $code, $headers);
    }

    public function responseTooManyAttempts(string $codeText = 'too many request', string $code = 'snap::snap.too_many_request', array $headers = []): JsonResponse
    {
        return $this->responseError(Null, Response::HTTP_TOO_MANY_REQUESTS, $codeText, $code, $headers);
    }

}
