<?php


namespace App\Repository\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Interface EloquentRepositoryInterface
 * @package App\Repositories
 */
interface EloquentRepositoryInterface
{
    /**
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model;

    /**
     * @param $id
     * @return Model
     */
    public function find(int $id): ?Model;

    /**
     * @return Model[]|Collection
     */
    public function all(): Collection;

    /**
     * @return Model[]|Collection
     */
    public function query(): Builder;

    /**
     * @param int $modelId
     * @param array $attributes
     * @return bool
     */
    public function update(int $modelId, array $attributes): bool;

    /**
     * @param int $id
     * @return mixed
     */
    public function deleteById(int $id): bool;

    /**
     * @param array $attributes
     * @return mixed
     */
    public function firstOrCreate(array $attributes);

    /**
     * @param array $attributes
     * @return mixed
     */
    public function createOrUpdate(array $attributes);

    /**
     * @param $column
     * @param string $operator
     * @param $value
     * @return mixed
     */
    public function where($column, $operator, $value);
}

