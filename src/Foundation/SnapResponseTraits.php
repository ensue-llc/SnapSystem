<?php

namespace Ensue\Snap\Foundation;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpFoundation\Response;

/**
 * Trait NicoResponseTraits
 * @package Ensue\Snap\Foundation
 */
trait SnapResponseTraits
{
    protected bool $api = true;

    public function responseOk(mixed $body, $codeText = 'ok', $messageCode = 'ok', array $headers = []): JsonResponse
    {
        return $this->nicoResponse($body, Response::HTTP_OK, $codeText, $messageCode, $headers);
    }

    public function nicoResponse(mixed $body, int|string $status = Response::HTTP_OK, $codeText = 'OK', string $messageCode = 'ok', array $headers = []): JsonResponse
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
                "message" => $this->translateMessageCode($messageCode),
                'code' => end($split),
                'code_text' => $codeText,
            ]
        ], $status)->withHeaders($headers);
    }

    public function validateStatusCode(int|string $code = 0): int
    {
        $statusCode = array(
            100, 101, 102,
            200, 201, 202, 203, 204, 205, 206, 207, 208, 226,
            300, 301, 302, 303, 304, 305, 306, 307, 308,
            400, 401, 402, 403, 404, 405, 406, 407, 408, 409, 410, 411, 412, 413, 414, 415, 416, 417, 418, 421, 422, 423, 424, 425, 426, 428, 429, 431, 451,
            500, 501, 502, 503, 504, 505, 506, 507, 508, 510, 511
        );
        if (in_array($code, $statusCode, true)) {
            return $code;
        }

        return 500;
    }

    /**
     * @param string $messageCode
     * @return string
     */
    private function translateMessageCode(string $messageCode): string
    {
        if (Lang::has('snap.' . $messageCode)) {
            return trans('snap.' . $messageCode);
        }
        if (Lang::has($messageCode)) {
            return trans($messageCode);
        }
        if (Lang::has('snap::snap.' . $messageCode)) {
            return trans('snap::snap.' . $messageCode);
        }

        return $messageCode;
    }

    public function responseServerError(string $codeText = 'internal server error occurred', string $code = 'internal_server_error', array $headers = []): JsonResponse
    {
        return $this->responseError(null, Response::HTTP_INTERNAL_SERVER_ERROR, $codeText, $code, $headers);
    }

    public function responseError(mixed $body, int $status = Response::HTTP_INTERNAL_SERVER_ERROR, $codeText = 'server error', $messageCode = 'server_error', array $headers = []): JsonResponse
    {
        return $this->nicoResponse($body, $status, $codeText, $messageCode, $headers);
    }

    public function responseUnAuthorize(string $codeText = 'unauthorized', string $code = 'unauthorized', array $headers = []): JsonResponse
    {
        $split = explode('.', $code);
        return response()->json([
            'body' => null,
            'status' => [
                "message" => $this->translateMessageCode($code),
                'code' => end($split),
                'code_text' => $codeText,
            ]
        ], Response::HTTP_UNAUTHORIZED)->withHeaders($headers);
    }

    public function responseForbidden(string $codeText = 'forbidden', string $code = 'forbidden', array $headers = []): JsonResponse
    {
        return $this->responseError(null, Response::HTTP_FORBIDDEN, $codeText, $code, $headers);
    }

    public function responseNotFound(string $codeText = 'not found', string $code = 'not_found', array $headers = []): JsonResponse
    {
        return $this->responseError(null, Response::HTTP_NOT_FOUND, $codeText, $code, $headers);
    }

    public function responseBadRequest(string $codeText = 'bad request', string $code = 'bad_request', array $headers = []): JsonResponse
    {
        return $this->responseError(null, Response::HTTP_BAD_REQUEST, $codeText, $code, $headers);
    }

    public function responsePreConditionFailed($body = '', string $codeText = 'precondition failed', string $code = 'precondition_failed', array $headers = []): JsonResponse
    {
        return $this->responseError($body, Response::HTTP_PRECONDITION_FAILED, $codeText, $code, $headers);
    }

    public function responseConflict(mixed $body = null, string $codeText = 'conflict', string $code = 'conflict', array $headers = []): JsonResponse
    {
        return $this->responseError($body, Response::HTTP_CONFLICT, $codeText, $code, $headers);
    }

    public function responseExpectationFailed(mixed $body = null, string $codeText = 'expectation failed', string $code = 'expectation_failed', array $headers = []): JsonResponse
    {
        return $this->responseError($body, Response::HTTP_EXPECTATION_FAILED, $codeText, $code, $headers);
    }

    public function responseValidationError(mixed $body = null, string $codeText = 'form validation failed', $code = 'form_validation_error', array $headers = []): JsonResponse
    {
        return $this->responseError($body, Response::HTTP_EXPECTATION_FAILED, $codeText, $code, $headers);
    }

    public function responseTooManyAttempts(string $codeText = 'too many request', string $code = 'too_many_request', array $headers = []): JsonResponse
    {
        return $this->responseError(Null, Response::HTTP_TOO_MANY_REQUESTS, $codeText, $code, $headers);
    }

}
