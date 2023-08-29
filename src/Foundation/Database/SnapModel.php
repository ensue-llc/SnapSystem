<?php
/**
 * Created by PhpStorm.
 * User: Amar
 * Date: 12/30/2016
 * Time: 11:04 PM
 */

namespace Ensue\Snap\Foundation\Database;

use Closure;
use Ensue\Snap\Exceptions\DataAssertionException;
use Ensue\Snap\Foundation\Database\Relation\HasManyExtended;
use Ensue\Snap\Foundation\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

abstract class SnapModel extends Model
{
    /**
     *  True to make responses to snakeCase
     * @var bool
     */
    protected bool $snakecase = true;

    /**
     * @var string
     */
    protected string $defaultSortColumn = "title";

    /**
     * @var string
     */
    protected string $defaultSortOrder = "asc";

    /**
     * @var array
     */
    protected array $sortableColumns = [];

    /**
     * @var array
     */
    protected array $sortKeyMaps = [];

    /**
     * @var int
     */
    protected $perPage = 16;

    /**
     * @var string[]
     */
    protected $casts = [
        'status' => Status::class
    ];

    /**
     * @return array
     */
    public function sortableColumns(): array
    {
        return $this->sortableColumns;
    }

    /**
     * @return string
     */
    public function defaultSortColumn(): string
    {
        return $this->defaultSortColumn;
    }

    /**
     * @return string
     */
    public function defaultSortOrder(): string
    {
        return $this->defaultSortOrder;
    }

    /**
     * @param $key
     * @return string
     */
    public function mapSortKey($key): string
    {
        return $this->sortKeyMaps[$key] ?? $key;
    }

    /**
     * Append descending order in the query
     * @param Builder $query
     * @param string $colName
     * @return Builder
     */
    public function scopeDescending(Builder $query, string $colName): Builder
    {
        return $this->scopeAscending($query, $colName, false);
    }

    /**
     * Append ascending order in the query
     * @param Builder $query
     * @param string $colName
     * @param bool $asc
     * @return Builder
     */
    public function scopeAscending(Builder $query, string $colName, bool $asc = true): Builder
    {
        $order = 'asc';
        if ($asc === false) {
            $order = 'desc';
        }
        return $query->orderBy($colName, $order);
    }

    /**
     * Add a publish check to query
     * @param Builder $query
     * @param string $colName
     * @return Builder
     */
    public function scopePublished(Builder $query, string $colName = 'status'): Builder
    {
        return $query->where($colName, Status::Published);
    }

    /**
     * @param Builder $query
     * @param string $colName
     * @return Builder
     */
    public function scopeUnpublished(Builder $query, string $colName = 'status'): Builder
    {
        return $query->where($colName, Status::Unpublished);
    }

    /**
     * @param $related
     * @param Closure|null $closure
     * @param null $foreignKey
     * @param null $localKey
     * @return HasOne
     */
    public function hasOneExtended($related, Closure $closure = null, $foreignKey = null, $localKey = null): HasOne
    {
        $foreignKey = $foreignKey ?: $this->getForeignKey();

        $instance = new $related;

        $localKey = $localKey ?: $this->getKeyName();

        $query = $instance->newQuery();

        if ($closure !== null) {
            $closure($query);
        }

        return new HasOne($query, $this, $instance->getTable() . '.' . $foreignKey, $localKey);
    }

    /**
     * @param $related
     * @param Closure|null $closure
     * @param null $foreignKey
     * @param null $localKey
     * @return HasManyExtended
     */
    public function hasManyExtended($related, Closure $closure = null, $foreignKey = null, $localKey = null): HasManyExtended
    {
        $foreignKey = $foreignKey ?: $this->getForeignKey();

        $instance = new $related;

        $localKey = $localKey ?: $this->getKeyName();

        $query = $instance->newQuery();

        if ($closure !== null) {
            $closure($query);
        }

        return new HasManyExtended($query, $this, $instance->getTable() . '.' . $foreignKey, $localKey);
    }

    /**
     * Override the set attribute to assert the data
     * @override
     * @param string $key
     * @param mixed $value
     * @return Model
     * @throws DataAssertionException
     */
    public function setAttribute($key, $value): Model
    {
        if ($this->hasAssertion($key)) {
            $method = 'assert' . Str::studly($key) . 'Attribute';
            if ($this->{$method}($value) !== true) {
                throw (new DataAssertionException())->setModel($this)->setDataName($key);
            }
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Determine if an assertion exists for an attribute.
     *
     * @param string $key
     * @return bool
     */
    public function hasAssertion(string $key): bool
    {
        return method_exists($this, 'assert' . Str::studly($key) . 'Attribute');
    }

    /**
     * Override method
     * @return array
     */
    public function toArray(): array
    {
        if ($this->snakecase === true) {
            return parent::toArray();
        }

        $items = parent::toArray();
        $returns = [];
        foreach ($items as $key => $value) {
            $returns[camel_case($key)] = $value;
        }
        return $returns;
    }

}
