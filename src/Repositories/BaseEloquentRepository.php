<?php

namespace MkamelMasoud\StarterCoreKit\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Cache;
use MkamelMasoud\StarterCoreKit\Repositories\Contracts\BaseRepositoryContract;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 * @implements BaseRepositoryContract<TModel>
 */
abstract class BaseEloquentRepository implements BaseRepositoryContract
{
    /**
     * @var TModel
     */
    protected Model $entity;

    /**
     * @var Builder<TModel>|null
     */
    protected ?Builder $query = null;

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

    /**
     * @return TModel
     */
    public function instance(): Model
    {
        return $this->entity;
    }

    public function getPerPage(): int
    {
        return $this->limit;
    }

    /**
     * @return Builder<TModel>
     */
    public function all(): Builder
    {
        return $this->entity->newQuery()->with($this->relations);
    }

    /**
     * @return LengthAwarePaginator<int, TModel>
     */
    public function allPaginated(?int $limit = null): LengthAwarePaginator
    {
        $limit = $limit ?? $this->getPerPage();

        /** @var Builder<TModel> $query */
        $query = $this->query ?? $this->entity->newQuery();

        $result = $query
            ->with($this->relations)
            ->paginate($limit);

        $this->query = null;

        return $result;
    }

    public function inRandomOrder(int $limit = 3): EloquentCollection
    {
        return $this->entity->newQuery()
            ->with($this->relations)
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }

    /**
     * @return Collection<int, TModel>
     */
    public function allFromCache(
        ?string $column = null,
        ?string $operator = null,
        mixed $value = null
    ): SupportCollection {
        $ttl = now()->addMinutes(10);
        $cacheKey = $this->entity::getCacheModelName();

        if ($column && $operator && $value !== null) {
            $cacheKey .= "_search_by_{$column}_{$operator}_{$value}";
        }

        $queryCallback = function () use ($column, $operator, $value): EloquentCollection {
            /** @var Builder<TModel> $query */
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

        return Cache::remember('index_' . $cacheKey, $ttl, $queryCallback);
    }

    public function find(int $id): ?Model
    {
        return $this->entity
            ->with($this->relations)
            ->find($id);
    }

    /**
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
     * @return EloquentCollection<int, TModel>
     */
    public function search(string|array $q, string $column = 'name', ?int $limit = null): EloquentCollection
    {
        $limit = $this->getPerPage();

        /** @var Builder<TModel> $query */
        $query = $this->entity->newQuery();

        // If query is empty
        if (empty($q) || (is_string($q) && trim($q) === '')) {
            return $query
                ->orderByDesc('id')
                ->limit($limit)
                ->get();
        }

        // If it's an array → search multiple names
        if (is_array($q)) {
            return $query
                ->where(function ($sub) use ($q) {
                    foreach ($q as $column) {
                        if (!empty($column)) {
                            $sub->orWhere($column, 'like', '%' . trim($column) . '%');
                        }
                    }
                })
                ->limit($limit)
                ->get();
        }

        // If it's a single string
        return $query
            ->where('name', 'like', '%' . trim($q) . '%')
            ->limit($limit)
            ->get();
    }

    public function where(string $column, string $operator, mixed $value): BaseRepositoryContract
    {
        /** @var Builder<TModel> $query */
        $this->query ??= $this->entity->newQuery()->with($this->relations);
        $this->query->where($column, $operator, $value);

        return $this;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return TModel
     */
    public function store(array $data): Model
    {
        /** @var TModel $entity */
        $entity = $this->entity->create($data);
        return $entity;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return TModel
     */
    public function update(int $id, array $data): Model
    {
        /** @var TModel $entity */
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

    public function with(array|string $relations): BaseRepositoryContract
    {
        $this->relations = array_merge($this->relations, (array)$relations);
        return $this;
    }
}
