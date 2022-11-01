<?php

namespace Ensue\NicoSystem\Interfaces;

/**
 * Created by PhpStorm.
 * User: Amar
 * Date: 12/30/2016
 * Time: 10:49 PM
 */
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
