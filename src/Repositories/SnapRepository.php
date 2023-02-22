<?php

namespace Ensue\Snap\Repositories;

use Ensue\Snap\Constants\SnapConstant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use Ensue\Snap\Exceptions\SnapBadRequestException;
use Ensue\Snap\Foundation\Database\SnapModel;
use Ensue\Snap\Foundation\Status;
use Ensue\Snap\Interfaces\SnapCrudInterface;

abstract class SnapRepository implements SnapCrudInterface
{
    /**
     * @var array
     */
    protected array $events = [];

    /**
     * BaseRepository constructor.
     * @param SnapModel $model
     */
    public function __construct(protected SnapModel $model)
    {
    }

    /**
     * @param array $params
     * @param bool $paginate
     * @param array $attributes
     * @return LengthAwarePaginator|Collection
     */
    public function getList(array $params = [], bool $paginate = true, array $attributes = []): LengthAwarePaginator|Collection
    {
        $builder = $this->attachOrderByQuery($params);
        $filter = $this->getFilter($builder);

        $filter?->attachFilterQuery($params);

        if (Arr::get($params, 'all') === 1 || Arr::get($params, 'all') === '1') {
            $paginate = false;
        }

        $this->onBeforeResult($builder);

        if ($attributes) {
            $builder->select($attributes);
        }

        if ($paginate) {
            return $builder->paginate();
        }

        return $builder->get();

    }

    /**
     * @param array $params
     * @return Builder
     */
    protected function attachOrderByQuery(array $params = []): Builder
    {
        $builder = $this->getQuery();
        $orderColumn = Arr::get($params, 'sort_by', $this->model->defaultSortColumn());
        $orderBy = Arr::get($params, 'sort_order', $this->model->defaultSortOrder());
        $perPage = Arr::get($params, 'per_page', $this->model->getPerPage());

        $this->model->setPerPage($perPage);

        if (!in_array($orderColumn, $this->model->sortableColumns(), true)) {
            $orderColumn = $this->model->defaultSortColumn();
        }

        $orderColumn = $this->model->mapSortKey($orderColumn);

        if (!in_array($orderBy, ['asc', 'desc'])) {
            $orderBy = $this->model->defaultSortOrder();
        }

        if ($orderColumn) {
            $builder->orderBy($orderColumn, $orderBy);
        }

        return $builder;
    }

    /**
     * @return Builder
     */
    protected function getQuery(): Builder
    {
        return $this->model->newQuery();
    }

    /**
     * @param Builder $builder
     */
    abstract public function getFilter(Builder $builder);

    /**
     * @param Builder $builder
     */
    public function onBeforeResult(Builder $builder): void
    {
    }

    /**
     * @param string $id
     * @param array $attributes
     * @return bool
     */
    public function destroy(string $id, array $attributes = []): bool
    {
        $model = $this->getQuery()->findOrFail($id);
        $this->dispatchEvent('deleting', $model);
        $model->delete();
        $this->dispatchEvent('deleted', $model);

        return true;
    }

    /**
     * @param $eventName
     * @param $model
     */
    protected function dispatchEvent($eventName, $model): void
    {
        if (array_key_exists($eventName, $this->events)) {
            Event::dispatch(new $this->events[$eventName]($model));
        }
    }

    /**
     * @param array $attributes
     * @return SnapModel
     */
    public function create(array $attributes): SnapModel
    {
        return $this->save($attributes);
    }

    /**
     * @param array $attributes
     * @param null $id
     * @return SnapModel
     */
    protected function save(array $attributes, $id = null): SnapModel
    {
        if ($id) {
            $model = $this->getQuery()->findOrFail($id);
            if ($model->getAttribute('status') === Status::STATUS_SUSPENDED) {
                throw new SnapBadRequestException("Resource is not editable", SnapConstant::ERR_SUSPENDED_MODEL_NOT_EDITABLE);
            }
        } else {
            $model = $this->model->newInstance();
        }
        $model->fill($attributes);
        if ($id) {
            $this->dispatchEvent('updating', $model);
        } else {
            $this->dispatchEvent('creating', $model);
        }
        $model->save();
        if ($id) {
            $this->dispatchEvent('updated', $model);
        } else {
            $this->dispatchEvent('created', $model);
        }
        return $model;
    }

    /**
     * @param string $id
     * @param array $attributes
     * @return SnapModel
     */
    public function update(string $id, array $attributes): SnapModel
    {
        return $this->save($attributes, $id);
    }

    /**
     * @param string|SnapModel $id
     * @return SnapModel
     */
    public function toggleStatus(string|SnapModel $id): SnapModel
    {
        if (is_numeric($id)) {
            $model = $this->getById($id);
        } elseif ($id instanceof SnapModel) {
            $model = $id;
        } else {
            throw new ModelNotFoundException();
        }
        if ($model->status == Status::STATUS_UNPUBLISHED) {
            $model->status = Status::STATUS_PUBLISHED;
        } elseif ($model->status == Status::STATUS_PUBLISHED) {
            $model->status = Status::STATUS_UNPUBLISHED;
        } elseif ($model->status == Status::STATUS_SUSPENDED) {
            throw new SnapBadRequestException("Cannot modify the model because the status is suspended", SnapConstant::ERR_SUSPENDED_MODEL_NOT_EDITABLE);
        }
        $this->dispatchEvent('updating', $model);
        $model->save();
        $this->dispatchEvent('updated', $model);
        return $model;
    }

    /**
     * @param string $id
     * @param array $attributes
     * @return SnapModel
     */
    public function getById(string $id, array $attributes = []): SnapModel
    {
        return $this->getQuery()->findOrFail($id);
    }

    /**
     * @param string $id
     * @param $field
     * @param $value
     * @return SnapModel
     */
    protected function updateSingle(string $id, $field, $value): SnapModel
    {
        $model = $this->getQuery()->findOrFail($id);
        $model->$field = $value;
        $this->dispatchEvent('updating', $model);
        $model->save();
        $this->dispatchEvent('updated', $model);

        return $model;
    }

}
