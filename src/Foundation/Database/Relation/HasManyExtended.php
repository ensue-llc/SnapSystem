<?php

namespace Ensue\NicoSystem\Foundation\Database\Relation;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HasManyExtended extends HasMany
{
    /**
     * The extra query definition
     * The extra query array of keys ['local_key','parent_key']
     * @var array
     */
    protected array $extraKeys = [];

    /**
     * HasManyExtended constructor.
     * @param Builder $query
     * @param Model $parent
     * @param string $foreignKey
     * @param string $localKey
     * @param array $extraKeyChecks
     */
    public function __construct(Builder $query, Model $parent, $foreignKey, $localKey, array $extraKeyChecks = [])
    {
        $this->extraKeys = $extraKeyChecks;
        parent::__construct($query, $parent, $foreignKey, $localKey);
    }

    /**
     * Match the eagerly loaded results to their many parents.
     *
     * @param array $models
     * @param Collection $results
     * @param string $relation
     * @param string $type
     * @return array
     */
    protected function matchOneOrMany(array $models, Collection $results, $relation, $type): array
    {
        $dictionary = $this->buildDictionary($results);

        // Once we have the dictionary we can simply spin through the parent models to
        // link them up with their children using the keyed dictionary to make the
        // matching very convenient and easy work. Then we'll just return them.
        foreach ($models as $model) {
            $key = $model->getAttribute($this->localKey);

            if (isset($dictionary[$key])) {
                $value = $dictionary[$key];
                $value = $this->checkForExtraQueryParameters($value, $model);
                $value = $this->related->newCollection($value);
                $model->setRelation($relation, $value);
            }
        }

        return $models;
    }

    /**
     * @param $value
     * @param $parent
     * @return array
     */
    protected function checkForExtraQueryParameters($value, $parent): array
    {
        if (empty($this->extraKeys)) {
            return $value;
        }
        $ret = [];
        foreach ($value as $v) {
            $put = true;
            foreach ($this->extraKeys as $extraKey) {
                list($localKey, $parentKey) = $extraKey;
                if ($v->$localKey != $parent->$parentKey) {
                    $put = false;
                    break;
                }
            }
            if ($put) {
                $ret[] = $v;
            }
        }
        return $ret;
    }

}
