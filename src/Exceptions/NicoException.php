<?php
/**
 * Created by PhpStorm.
 * User: Amar
 * Date: 10/18/2017
 * Time: 4:20 PM
 */

namespace NicoSystem\Exceptions;

/**
 * Class NicoException
 * @package NicoSystem\Exceptions
 */
class NicoException extends \RuntimeException
{
    /**
     * @var string
     */
    protected string $respCode = 'err_runtime_error';

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
     * @return \NicoSystem\Exceptions\NicoException
     */
    public function setResponseBody(mixed $respBody): NicoException
    {
        $this->respBody = $respBody;
        return $this;
    }
}
