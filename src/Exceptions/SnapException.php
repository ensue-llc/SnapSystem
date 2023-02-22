<?php

namespace Ensue\Snap\Exceptions;

use Ensue\Snap\Constants\SnapConstant;

/**
 * Class NicoException
 * @package Ensue\Snap\Exceptions
 */
class SnapException extends \RuntimeException
{
    /**
     * @var string
     */
    protected string $respCode = SnapConstant::ERR_RUNTIME_ERROR;

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
     * @return SnapException
     */
    public function setResponseCode(int|string $respCode): SnapException
    {
        $this->respCode = $respCode;
        return $this;
    }

    /**
     * Set response body
     * @param mixed $respBody
     * @return SnapException
     */
    public function setResponseBody(mixed $respBody): SnapException
    {
        $this->respBody = $respBody;
        return $this;
    }
}
