<?php
namespace NicoSystem\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class BaseFilter
 * @package NicoSystem\Filters
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
    abstract public function keyword(string $keyword): void;

    /**
     * @param string $status
     */
    public function status(string $status = ''): void
    {
        if (!is_null($status)) {
            $this->builder->where('status', $status);
        }
    }

}
