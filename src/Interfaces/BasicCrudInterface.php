<?php

namespace Ensue\NicoSystem\Interfaces;

interface BasicCrudInterface
{
    /**
     * @param string $id
     * @param array $attributes
     */
    public function getById(string $id, array $attributes = []): mixed;

    /**
     * @param array $params
     * @param bool $paginate
     * @param array $attributes
     */
    public function getList(array $params = [], bool $paginate = true, array $attributes = []): mixed;

    /**
     * @param string $id
     * @param array $attributes
     */
    public function destroy(string $id, array $attributes = []): mixed;

    /**
     * @param array $inputs
     */
    public function create(array $inputs): mixed;

    /**
     * @param string $id
     * @param array $attributes
     */
    public function update(string $id, array $attributes): mixed;

    /**
     * @param string $id
     */
    public function toggleStatus(string $id): mixed;
}
