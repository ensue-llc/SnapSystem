<?php

namespace Ensue\Snap\Exceptions;

/**
 * Class DataAssertionException
 * @package Ensue\Snap\Exceptions
 */
class DataAssertionException extends \RuntimeException
{
    protected mixed $model;

    protected $message = "Data assertion failed";

    protected $code = 100;

    protected mixed $dataName;

    /**
     * @return mixed
     */
    public function getModel(): mixed
    {
        return $this->model;
    }

    /**
     * @param $model
     * @return $this
     */
    public function setModel($model): static
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDataName(): mixed
    {
        return $this->dataName;
    }

    /**
     * @param $dataName
     * @return $this
     */
    public function setDataName($dataName): static
    {
        $this->dataName = $dataName;
        $this->message = "Data assertion failed for '" . $dataName . "' for model " . get_class($this->model);
        return $this;
    }
}
