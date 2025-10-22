<?php

namespace MkamelMasoud\StarterCoreKit\Core\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use MkamelMasoud\StarterCoreKit\Contracts\Repositories\BaseRepositoryContract;
use MkamelMasoud\StarterCoreKit\Core\Repositories\Traits\BuildQueryTrait;

/**
 * @template TModel of EloquentModel
 *
 * @implements BaseRepositoryContract<TModel>
 */
abstract class BaseEloquentRepository implements BaseRepositoryContract
{
    use BuildQueryTrait;

    protected EloquentModel $entity;

    /**
     * @var EloquentBuilder<TModel>|null
     */
    protected ?EloquentBuilder $query = null;

    /**
     * @var array<int, string>
     */
    protected array $relations = [];

    protected int $limit = 10;

    public function __construct()
    {
        $this->entity = app($this->entity());
    }

    /**
     * @return class-string<TModel>
     */
    abstract protected function entity(): string;

    public function instance(): EloquentModel
    {
        return $this->entity;
    }

    public function getRecordsLimit(): int
    {
        return $this->limit;
    }

    /**
     * Fetch records with optional filters and return type control.
     *
     * @template TModel of EloquentModel
     *
     * @param  array<string, mixed>  $filters
     * @return EloquentCollection<int, TModel>|EloquentBuilder<TModel>|LengthAwarePaginator<TModel>
     */
    public function fetchData(
        array $filters = [],
        string $dataTypeReturn = 'get',
        ?int $limit = null,
        bool $random = false
    ): EloquentCollection|EloquentBuilder|LengthAwarePaginator {
        $query = $this->buildQuery($filters, $limit, $random);

        return $this->executeQuery($query, $dataTypeReturn);
    }

    public function where(string $column, string $operator, mixed $value): BaseRepositoryContract
    {
        /** @var EloquentBuilder<TModel> $query */
        $this->query ??= $this->entity->newQuery()->with($this->relations);
        $this->query->where($column, $operator, $value);

        return $this;
    }

    public function find(int $id): ?EloquentModel
    {
        return $this->entity
            ->with($this->relations)
            ->find($id);
    }

    /**
     * @param  array<int>  $ids
     * @return EloquentCollection<int, TModel>
     */
    public function findMany(array $ids): EloquentCollection
    {
        return $this->entity
            ->newQuery()
            ->whereIn('id', $ids)
            ->get();
    }

    public function with(array|string $relations): BaseRepositoryContract
    {
        $this->relations = array_merge($this->relations, (array) $relations);

        return $this;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function store(array $data): EloquentModel
    {
        /** @var EloquentModel $entity */
        $entity = $this->entity->create($data);

        return $entity;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data): EloquentModel
    {
        /** @var EloquentModel $entity */
        $entity = $this->find($id);
        $entity->update($data);

        return $entity->refresh();
    }

    public function delete(int $id): bool
    {
        $entity = $this->find($id);

        return (bool) $entity->delete();
    }

    public function forceDelete(int $id): bool
    {
        $entity = $this->find($id);

        return (bool) $entity->forceDelete();
    }
}
