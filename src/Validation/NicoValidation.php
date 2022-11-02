<?php

namespace Ensue\NicoSystem\Validation;

use Illuminate\Validation\Validator;

/**
 * Class NicoValidation
 * @package Ensue\NicoSystem\Validation
 */
class NicoValidation extends Validator
{
    /**
     * Validate phone
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return false|int
     */
    public function validatePhone($attribute, $value, $parameters): bool|int
    {
        /*
         * accept phone in
         * +xxxx-xxx-xxx-xxxx or
         * 00xxxx-xxx-xxx-xxxx or
         * xxx-xxx-xxxx or
         * +xxx-xxx-xxx-xxxx or
         * xxxxxxxxxx
         * (xxx)-xxx-xxxx
         */

        return preg_match('%^(?:(?:\(?(?:00|\+)([1-4]\d\d|[1-9]\d?)\)?)?[\-\ \\\/]?)?((?:\(?\d{1,}\)?[\-\ \\\/]?){0,})?$%i', $value) && strlen($value) >= 9;

    }

    /**
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validatePassword($attribute, $value, $parameters): bool
    {
        return true;
    }

    /**
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateNotSame($attribute, $value, $parameters): bool
    {
        $this->requireParameterCount(1, $parameters, 'not_same');

        $other = $parameters[0];

        return $value !== $other;
    }

    /**
     * @param $message
     * @param $attribute
     * @param $rule
     * @param $parameters
     * @return string|array
     */
    public function replaceNotSame($message, $attribute, $rule, $parameters): string|array
    {
        return str_replace(':other', $this->getDisplayableAttribute($parameters[0]), $message);
    }
}
