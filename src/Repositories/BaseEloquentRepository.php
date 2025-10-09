<?php

namespace MkamelMasoud\StarterCoreKit\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use MkamelMasoud\StarterCoreKit\Contracts\BaseRepositoryContract;

abstract class BaseEloquentRepository implements BaseRepositoryContract
{
    /**
     * The entity model.
     */
    protected Model $entity;

    /**
     * Query builder instance (for chaining conditions).
     */
    protected ?Builder $query = null;

    /**
     * Relations to eager load.
     */
    protected array $relations = [];

    /**
     * Default pagination limit.
     */
    protected int $limit = 15;

    /**
     * EloquentRepository constructor.
     */
    public function __construct()
    {
        $this->entity = app($this->entity());
    }

    /**
     * Return the fully qualified model class name.
     */
    abstract protected function entity(): string;

    /**
     * Return the model instance.
     */
    public function instance(): Model
    {
        return $this->entity;
    }

    /**
     * Create a new entity.
     */
    public function create(array $data): Model
    {
        return $this->entity->create($data);
    }

    /**
     * Update an existing entity.
     */
    public function update(int $id, array $data): Model
    {
        $entity = $this->find($id);
        $entity->update($data);
        return $entity;
    }

    /**
     * Delete entity (single or multiple).
     */
    public function delete(int|array $id): bool
    {
        if (is_array($id)) {
            return (bool) $this->entity->destroy($id);
        }

        $entity = $this->find($id);
        return (bool) $entity->delete();
    }

    /**
     * Get all records (with optional eager loading).
     */
    public function all(): Builder
    {
        return $this->entity->newQuery()->with($this->relations);
    }

    /**
     * Paginated data.
     */
    public function paginate(int $limit = 0): LengthAwarePaginator
    {
        $limit = request('limit', $limit ?: $this->limit);

        $query = $this->query ?? $this->entity->newQuery();

        $result = $query
            ->with($this->relations)
            ->when(method_exists($this->entity, 'scopeOrdered'), fn($q) => $q->ordered())
            ->paginate($limit);

        // Reset query for next usage
        $this->query = null;

        return $result;
    }

    /**
     * Add eager load relations.
     */
    public function with(array|string $relations): BaseRepositoryContract
    {
        $this->relations = array_merge($this->relations, (array) $relations);
        return $this;
    }

    /**
     * Add where condition (chainable).
     */
    public function where(string $column, string $operator, mixed $value): BaseRepositoryContract
    {
        $this->query ??= $this->entity->newQuery()->with($this->relations);
        $this->query->where($column, $operator, $value);
        return $this;
    }

    /**
     * Find entity by ID.
     */
    public function find(int $id): Model
    {
        return $this->entity->with($this->relations)->findOrFail($id);
    }
}
