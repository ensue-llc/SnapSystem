<?php
/**
 * Created by PhpStorm.
 * User: Amar
 * Date: 12/31/2016
 * Time: 12:23 AM
 */

namespace NicoSystem\Foundation;

use App\System\AppConstants;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpFoundation\Response;

/**
 * Trait NicoResponseTraits
 * @package NicoSystem\Foundation
 */
trait NicoResponseTraits
{
    protected bool $api = true;

    /**
     * @param $body
     * @param array $headers
     * @return JsonResponse
     */
    public function responseOk($body, array $headers = []): JsonResponse
    {
        return $this->nicoResponse($body, Response::HTTP_OK, $headers);
    }

    /**
     * @param $body
     * @param int $status
     * @param array $headers
     * @return JsonResponse
     */
    public function nicoResponse($body, int $status = Response::HTTP_OK, array $headers = []): JsonResponse
    {
        if ($body instanceof Collection) {
            $body = new Paginator($body->all(), $body->count());
        }
        $status = $this->validateStatusCode($status);

        return response()->json($body, $status)->withHeaders($headers);
    }

    /**
     * @param int $code
     * @return int
     */
    public function validateStatusCode(int $code = 0): int
    {
        $status_code = array(100, 101, 102, 200, 201, 202, 203, 204, 205, 206, 207, 208, 226,
            300, 301, 302, 303, 304, 305, 306, 307, 308
        , 400, 401, 402, 403, 404, 405, 406, 407, 408, 409, 410, 411, 412, 413, 414, 415, 416, 417, 418, 421, 422, 423, 424, 425, 426, 428, 429, 431, 451
        , 500, 501, 502, 503, 504, 505, 506, 507, 508, 510, 511);
        if (in_array($code, $status_code)) {
            return $code;
        } else {
            return 500;
        }
    }

    /**
     * @param string $code
     * @param array $headers
     * @return JsonResponse
     */
    public function responseServerError(string $code = 'internal_server_error', array $headers = []): JsonResponse
    {
        return $this->responseError(Response::HTTP_INTERNAL_SERVER_ERROR, $code, $headers);
    }

    /**
     * @param int $status
     * @param string $code
     * @param array $headers
     * @return JsonResponse
     */
    public function responseError(int $status = Response::HTTP_INTERNAL_SERVER_ERROR, string $code = 'server_error', array $headers = []): JsonResponse
    {
        $status = $this->validateStatusCode($status);

        if(Lang::has("appconstant." . $code)) {
            $message = trans("appconstant." . $code);
        } else if($status === Response::HTTP_INTERNAL_SERVER_ERROR){
            $message = trans("appconstant." . AppConstants::ERR_CODE_ZERO);
        } else {
            $message = $code;
        }
        return response()->json([
            "message" => $message,
            'code' => $code
        ], $status)->withHeaders($headers);
    }

    /**
     * @param string $code
     * @param array $headers
     * @return JsonResponse
     */
    public function responseUnAuthorize(string $code = 'unauthorized', array $headers = []): JsonResponse
    {
        return $this->responseError(Response::HTTP_UNAUTHORIZED, $code, $headers);
    }

    /**
     * @param string $code
     * @param array $headers
     * @return JsonResponse
     */
    public function responseForbidden(string $code = 'forbidden', array $headers = []): JsonResponse
    {
        return $this->responseError(Response::HTTP_FORBIDDEN, $code, $headers);
    }

    /**
     * @param string $code
     * @param array $headers
     * @return JsonResponse
     */
    public function responseNotFound(string $code = 'not_found', array $headers = []): JsonResponse
    {
        return $this->responseError(Response::HTTP_NOT_FOUND, $code, $headers);
    }

    /**
     * @param string $code
     * @param array $headers
     * @return JsonResponse
     */
    public function responseBadRequest(string $code = 'bad_request', array $headers = []): JsonResponse
    {
        return $this->responseError(Response::HTTP_BAD_REQUEST, $code, $headers);
    }

    /**
     * @param string $code
     * @param array $headers
     * @return JsonResponse
     */
    public function responsePreConditionFailed(string $code = 'precondition_failed', array $headers = []): JsonResponse
    {
        return $this->responseError(Response::HTTP_PRECONDITION_FAILED, $code, $headers);
    }

    /**
     * @param string $code
     * @param array $headers
     * @return JsonResponse
     */
    public function responseConflict(string $code = 'conflict', array $headers = []): JsonResponse
    {
        return $this->responseError(Response::HTTP_CONFLICT, $code, $headers);
    }

    /**
     * @param string $code
     * @param array $headers
     * @return JsonResponse
     */
    public function responseExpectationFailed(string $code = 'expectation_failed', array $headers = []): JsonResponse
    {
        return $this->responseError(Response::HTTP_EXPECTATION_FAILED, $code, $headers);
    }

    /**
     * @param null $body
     * @param string $code
     * @param array $headers
     * @return JsonResponse
     */
    public function responseValidationError($body = null, array $headers = []): JsonResponse
    {
        return $this->nicoResponse($body, Response::HTTP_EXPECTATION_FAILED, $headers);
    }

    /**
     * @param string $code
     * @param array $headers
     * @return JsonResponse
     */
    public function responseTooManyAttempts(string $code = 'too_many_request', array $headers = []): JsonResponse
    {
        return $this->responseError(Response::HTTP_TOO_MANY_REQUESTS, $code, $headers);
    }

}
