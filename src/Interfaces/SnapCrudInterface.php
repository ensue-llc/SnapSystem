<?php

namespace Ensue\Snap\Interfaces;

use Ensue\Snap\Foundation\Database\SnapModel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface SnapCrudInterface
{
    /**
     * @param string $id
     * @param array $attributes
     */
    public function getById(string $id, array $attributes = []): SnapModel;

    /**
     * @param array $params
     * @param bool $paginate
     * @param array $attributes
     */
    public function getList(array $params = [], bool $paginate = true, array $attributes = []): LengthAwarePaginator|Collection;

    /**
     * @param string $id
     * @param array $attributes
     */
    public function destroy(string $id, array $attributes = []): bool;

    /**
     * @param array $attributes
     */
    public function create(array $attributes): SnapModel;

    /**
     * @param string $id
     * @param array $attributes
     */
    public function update(string $id, array $attributes): SnapModel;

    /**
     * @param string $id
     */
    public function toggleStatus(string $id): SnapModel;
}
