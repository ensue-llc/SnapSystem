<?php

namespace Ensue\NicoSystem\Exceptions;

use Ensue\NicoSystem\Constants\AppConstants;

/**
 * Class NicoException
 * @package Ensue\NicoSystem\Exceptions
 */
class NicoException extends \RuntimeException
{
    /**
     * @var string
     */
    protected string $respCode = AppConstants::ERR_RUNTIME_ERROR;

    /**
     * NicoException constructor.
     *
     * @param string $respCode
     * @param mixed $respBody
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct(
        string $respCode = '',
        protected mixed $respBody = null,
        int $code = 0,
        \Exception $previous = null
    )
    {
        if ($respCode) {
            $this->respCode = $respCode;
        }
        parent::__construct($respCode, $code, $previous);
    }

    /**
     * @return string
     */
    public function getResponseCode(): string
    {
        return $this->respCode;
    }

    /**
     * @return mixed
     */
    public function getResponseBody(): mixed
    {
        return $this->respBody;
    }

    /**
     * Set response code
     * @param int|string $respCode
     * @return NicoException
     */
    public function setResponseCode(int|string $respCode): NicoException
    {
        $this->respCode = $respCode;
        return $this;
    }

    /**
     * Set response body
     * @param mixed $respBody
     * @return NicoException
     */
    public function setResponseBody(mixed $respBody): NicoException
    {
        $this->respBody = $respBody;
        return $this;
    }
}
