<?php


namespace App\Repository\Eloquent;

use App\Repository\Client\Department\DepartmentRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class BaseRepository implements EloquentRepositoryInterface
{

    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return Model[]|Collection
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    /**
     * @param $id
     * @return Model
     */
    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * @param int $modelId
     * @param array $attributes
     * @return bool
     */
    public function update(int $modelId, array $attributes): bool
    {
        $model = $this->find($modelId);

        return $model->update($attributes);

    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        return $this->model->query();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function deleteById(int $id): bool
    {
        return $model = $this->model->find($id)->delete();
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function firstOrCreate(array $attributes)
    {
        return $this->model->firstOrCreate($attributes);
    }

    /**
     * @param array $attributes1
     * @param array $attributes2
     * @return mixed
     */
    public function createOrUpdate(array $attributes1, array $attributes2)
    {
        return $this->model->updateOrCreate($attributes1, $attributes2);
    }

    /**
     * @param $slug
     * @return array|\Illuminate\Database\Eloquent\Collection|Model[]|mixed
     */
    public function getByRoleCompany($slug)
    {
        foreach (request()->user()->roles as $role) {
            foreach ($role->permissions as $permission) {
                if ($permission->slug === $slug) $company_id[] = $role->company_id;
                if ($role->company_id) continue;
                return $this->model->all();
            }
        }
        if (!isset($company_id[0]))return[];

        return $this->model->whereIn('company_id', $company_id)->get();
    }

    /**
     * @param $id
     * @param $slug
     * @return mixed|void
     */
    public function firstByRoleCompanyAndModelId($id, $slug)
    {
        $model = $this->find($id);

        $this->firstByRoleCompany($model, $slug);
    }

    /**
     * @param $model
     * @param $slug
     * @return mixed
     */
    public function firstByRoleCompany($model, $slug)
    {
        foreach (request()->user()->roles as $role) {
                foreach ($role->permissions as $permission) {
                    if ($model->company_id === $role->company_id)if ($permission->slug === $slug) return $model;
                    if ($role->company_id) continue;
                    return $model;
                }
        }

        abort(403);
    }

}
