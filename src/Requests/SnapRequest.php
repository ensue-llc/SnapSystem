<?php

namespace Ensue\Snap\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Ensue\Snap\Foundation\SnapResponseTraits;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SnapRequest
 * @package Ensue\SnapSystem\Requests
 */
class SnapRequest extends FormRequest
{
    use SnapResponseTraits;

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Get the proper failed validation response for the request.
     *
     * @param array $errors
     * @return Response
     */
    public function response(array $errors): Response
    {
        return $this->responseValidationError($errors);
    }

}
