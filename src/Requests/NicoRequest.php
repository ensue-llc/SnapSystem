<?php
/**
 * Created by PhpStorm.
 * User: Amar
 * Date: 12/30/2016
 * Time: 11:48 PM
 */

namespace Ensue\NicoSystem\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Ensue\NicoSystem\Foundation\NicoResponseTraits;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class NicoRequest
 * @package Ensue\NicoSystem\Requests
 */
class NicoRequest extends FormRequest
{
    use NicoResponseTraits;

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
