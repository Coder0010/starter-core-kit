<?php

namespace MkamelMasoud\StarterCoreKit\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use MkamelMasoud\StarterCoreKit\Repositories\Contracts\BaseRepositoryContract;

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

    public function getPerPage(): int
    {
        return $this->limit;
    }

    public function find(int $id): ?Model
    {
        return $this->entity->with($this->relations)->find($id);
    }

    public function all(): Builder
    {
        return $this->entity->newQuery()->with($this->relations);
    }

    public function getFromCache(?string $column = null, ?string $operator = null, mixed $value = null): Collection
    {
        $ttl = 600; // 10 minutes

        $cacheKey = $this->entity::getCacheModelName();

        if ($column && $operator && $value !== null) {
            $cacheKey .= "_search_by_{$column}_{$operator}_{$value}";
        }

        $queryCallback = function () use ($column, $operator, $value) {
            $query = $this->entity::query()->latest();
            if ($column && $operator && $value !== null) {
                $query->where($column, $operator, $value);
            }

            return $query->get();
        };

        if (Cache::getStore() instanceof \Illuminate\Cache\TaggableStore) {
            return Cache::tags([$this->entity::getCacheModelName()])
                ->remember($cacheKey, $ttl, $queryCallback);
        }
        // Fallback to non-taggable
        return Cache::remember('index_'. $cacheKey, $ttl, $queryCallback);
    }

    public function paginate(int $limit = 0)
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

    public function where(string $column, string $operator, mixed $value): BaseRepositoryContract
    {
        $this->query ??= $this->entity->newQuery()->with($this->relations);
        $this->query->where($column, $operator, $value);
        return $this;
    }

    public function with(array|string $relations): BaseRepositoryContract
    {
        $this->relations = array_merge($this->relations, (array)$relations);
        return $this;
    }

    public function create(array $data): Model
    {
        return $this->entity->create($data);
    }

    public function update(int $id, array $data): Model
    {
        $entity = $this->find($id);
        $entity->update($data);
        return $entity->refresh();
    }

    public function delete(int $id): bool
    {
        $entity = $this->find($id);
        return (bool)$entity->delete();
    }

    public function forceDelete(int $id): bool
    {
        $entity = $this->find($id);
        return (bool)$entity->forceDelete();
    }

}
