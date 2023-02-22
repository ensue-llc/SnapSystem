<?php
/**
 * Created by PhpStorm.
 * User: Amar
 * Date: 1/3/2017
 * Time: 10:45 PM
 */

namespace Ensue\Snap\Foundation;

/**
 * Class Phone
 * @package Ensue\Snap\Foundation
 */
class Phone
{
    public const FORMAT_NONE = "xxxxxxxxxx";
    public const FORMAT_AMERICAN = "(xxx)-xxx-xxxx";
    public const FORMAT_AMARICAN_W_CODE = '+x-(xxx)-xxx-xxxx';
    public const FORMAT_DASHED_10 = "xxx-xxx-xxxx";
    public const FORMAT_DASHED_10_W_CODE = "+x-xxx-xxx-xxxx";

    /**
     * Phone constructor.
     * @param string $value
     * @param string $format
     */
    public function __construct(protected string $value = '', protected string $format = '(xxx)-xxx-xxxx')
    {
        $this->setValue($value);
    }

    public function setValue($value)
    {
        $this->value = $this->getValueWithFormat($value);
    }

    /**
     * @param $value
     * @return array|string
     */
    protected function getValueWithFormat($value): array|string
    {
        $xIndices = [];
        for ($i = 0; $i < strlen($this->format); $i++) {
            if ($this->format[$i] == 'x') {
                $xIndices[] = $i;
            }
        }
        $format = $this->format;
        //replace the x's with the value
        //replace any none digits in the value
        $value = preg_replace('/[^0-9]/', '', $value);
        for ($i = 0; $i < count($xIndices); $i++) {
            $format = substr_replace($format, $value[0], $xIndices[$i], 1);
            //remove the added character from value
            echo $value . "&nbsp;&nbsp;&nbsp;";
            $value = substr($value, 1);

        }
        return $format;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }

}
