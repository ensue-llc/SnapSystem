<?php
/**
 * Created by PhpStorm.
 * User: Rocco
 * Date: 10/25/2018
 * Time: 3:59 PM
 */

namespace Ensue\NicoSystem\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class BaseFilter
 * @package Ensue\NicoSystem\Filters
 */
abstract class BaseFilter
{
    /**
     * BaseFilter constructor.
     * @param Builder $builder
     */
    public function __construct(protected Builder $builder)
    {
    }

    /**
     * @param array $filerOptions
     * @return Builder
     */
    public function attachFilterQuery(array $filerOptions): Builder
    {
        //creating the instance for filter
        foreach ($filerOptions as $key => $value) {
            if (method_exists($this, $key)) {
                call_user_func_array([$this, $key], array_filter([$value], 'strlen'));
            }
        }
        return $this->builder;
    }

    /**
     * @param string $title
     */
    public function title(string $title = ''): void
    {
        if ($title !== '') {
            $this->builder->where('title', 'like', "%{$title}%");
        }
    }

    /**
     * @param string $keyword
     */
    public abstract function keyword(string $keyword): void;

    /**
     * @param string $status
     */
    public function status(string $status = ''): void
    {
        if ($status != '' || $status != null) {
            $this->builder->where('status', $status);
        }
    }

}
