<?php

namespace MkamelMasoud\StarterCoreKit\Core\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\LazyCollection;
use MkamelMasoud\StarterCoreKit\Contracts\Repositories\BaseRepositoryContract;
use MkamelMasoud\StarterCoreKit\Core\Repositories\Traits\BuildQueryTrait;

/**
 *  Base Eloquent repository providing query building and CRUD helpers.
 *
 * @template TModel of EloquentModel
 *
 * @implements BaseRepositoryContract<TModel>
 */
abstract class BaseEloquentRepository implements BaseRepositoryContract
{
    use BuildQueryTrait;

    /**
     * The underlying model instance.
     *
     * @var TModel
     */
    protected EloquentModel $entity;

    /**
     * @var EloquentBuilder<TModel>|null
     */
    protected ?EloquentBuilder $query = null;

    /**
     * @var array<int, string>
     */
    protected array $relations = [];

    /**
     * Default pagination or fetch limit.
     */
    protected int $limit = 10;

    public function __construct()
    {
        /** @var TModel $entity */
        $this->entity = app($this->entity());
    }

    /**
     * Define the model class this repository handles.
     *
     * @return class-string<TModel>
     */
    abstract protected function entity(): string;

    /**
     * Get the current model instance.
     *
     * @return TModel
     */
    public function instance(): EloquentModel
    {
        return $this->entity;
    }

    /**
     * Get the configured record limit.
     */
    public function getRecordsLimit(): int
    {
        return $this->limit;
    }

    /**
     * Fetch records with optional filters and return type control.
     *
     * @param array<string, mixed>          $filters
     * @param 'cursor'|'paginate'|'builder' $dataTypeReturn
     *
     * @return EloquentCollection<int, TModel>
     *                                         | EloquentBuilder<TModel>
     *                                         | LengthAwarePaginator<TModel>
     *                                         | SupportCollection<int, TModel>
     *                                         | LazyCollection<int, TModel>
     */
    public function fetchData(
        array $filters = [],
        string $dataTypeReturn = 'cursor',
        ?int $limit = null,
        bool $random = false
    ): EloquentCollection|EloquentBuilder|LengthAwarePaginator|SupportCollection|LazyCollection {
        $query = $this->buildQuery($filters, $limit, $random);

        return $this->executeQuery($query, $dataTypeReturn);
    }

    /**
     * Apply a simple where condition to the query builder.
     *
     * @return $this
     */
    public function where(string $column, string $operator, mixed $value): BaseRepositoryContract
    {
        /** @var EloquentBuilder<TModel> $query */
        $this->query ??= $this->entity->newQuery()->with($this->relations);
        $this->query->where($column, $operator, $value);

        return $this;
    }

    /**
     * Find a single record by ID.
     *
     * @return TModel|null
     */
    public function find(int $id): ?EloquentModel
    {
        return $this->entity
            ->with($this->relations)
            ->find($id);
    }

    /**
     * Find multiple records by their IDs.
     *
     * @param array<int> $ids
     *
     * @return EloquentCollection<int, TModel>
     */
    public function findMany(array $ids): EloquentCollection
    {
        return $this->entity
            ->newQuery()
            ->whereIn('id', $ids)
            ->get();
    }

    /**
     * Set the relations to be eager loaded.
     *
     * @param array<int, string>|string $relations
     *
     * @return $this
     */
    public function with(array|string $relations): BaseRepositoryContract
    {
        $this->relations = array_merge($this->relations, (array)$relations);

        return $this;
    }

    /**
     * Create and persist a new model instance.
     *
     * @param array<string, mixed> $data
     *
     * @return TModel
     */
    public function store(array $data): EloquentModel
    {
        /** @var EloquentModel $entity */
        $entity = $this->entity->create($data);

        return $entity;
    }

    /**
     * Update an existing model by ID.
     *
     * @param array<string, mixed> $data
     *
     * @return TModel
     */
    public function update(int $id, array $data): EloquentModel
    {
        /** @var EloquentModel $entity */
        $entity = $this->find($id);
        $entity->update($data);

        return $entity->refresh();
    }

    /**
     * Soft delete a record by ID.
     */
    public function delete(int $id): bool
    {
        $entity = $this->find($id);

        return (bool)$entity?->delete();
    }

    public function forceDelete(int $id): bool
    {
        $entity = $this->find($id);

        return (bool)$entity?->forceDelete();
    }
}
