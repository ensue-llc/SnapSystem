<?php

namespace Ensue\Snap\Interfaces;

use Ensue\Snap\Foundation\Database\SnapModel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface SnapCrudInterface
{
    /**
     * @param int $id
     * @param array $attributes
     */
    public function getById(int $id, array $attributes = []): SnapModel;

    /**
     * @param array $params
     * @param bool $paginate
     * @param array $attributes
     */
    public function getList(array $params = [], bool $paginate = true, array $attributes = []): LengthAwarePaginator|Collection;

    /**
     * @param int $id
     * @param array $attributes
     */
    public function destroy(int $id, array $attributes = []): bool;

    /**
     * @param array $attributes
     */
    public function create(array $attributes): SnapModel;

    /**
     * @param int $id
     * @param array $attributes
     */
    public function update(int $id, array $attributes): SnapModel;

    /**
     * @param int $id
     */
    public function toggleStatus(int $id): SnapModel;
}
