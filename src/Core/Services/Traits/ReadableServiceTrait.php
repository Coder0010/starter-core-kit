<?php

namespace MkamelMasoud\StarterCoreKit\Core\Services\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\RecordNotFoundException;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\LazyCollection;
use MkamelMasoud\StarterCoreKit\Contracts\Repositories\BaseRepositoryContract;
use MkamelMasoud\StarterCoreKit\Core\BaseDto;
use MkamelMasoud\StarterCoreKit\Core\BaseService;

/**
 * @mixin BaseService
 * @template TRepo of BaseRepositoryContract<TModel>
 * @template TDto of BaseDto
 * @template TModel of EloquentModel
 * @property-read BaseRepositoryContract $repository
 */
trait ReadableServiceTrait
{
    /**
     * Fetch records with optional filters, caching, and output control.
     *
     * @param array<string, mixed> $filters
     * @param 'cursor'|'paginate'|'builder' $dataTypeReturn
     * @param int|null $limit
     * @param bool $random
     * @param string $cachePrefix
     *
     * @return EloquentCollection|EloquentBuilder|LengthAwarePaginator|SupportCollection|LazyCollection |
     *                                                                                                  EloquentBuilder<TModel>
     */
    public function fetchData(
        array $filters = [],
        string $dataTypeReturn = 'cursor',
        ?int $limit = null,
        bool $random = false,
        string $cachePrefix = 'normal'
    ): EloquentCollection|EloquentBuilder|LengthAwarePaginator|SupportCollection|LazyCollection {
        $cacheKey = $this->buildCacheKey($filters, $cachePrefix, $dataTypeReturn);
        $table = $this->entity()->getTable();

        return $this->cacheRemember(
            table: $table,
            cacheKey: $cacheKey,
            callBack: fn() => $this->repository->fetchData(
                filters: $filters,
                dataTypeReturn: $dataTypeReturn,
                limit: $limit,
                random: $random,
            )
        );
    }


    /**
     * Fetch a random subset of records and cache the result.
     *
     * @return EloquentCollection<int, TModel>
     */
    public function inRandomOrder(int $limit = 3): EloquentCollection
    {
        $table = $this->entity()->getTable();
        $cacheKey = "random-{$table}:limit_{$limit}";

        return $this->cacheRemember(
            table: $table,
            cacheKey: $cacheKey,
            callBack: fn() => $this->repository->fetchData(
                dataTypeReturn: 'builder'
            )->inRandomOrder()
                ->limit(value: $limit)
                ->get()
        );
    }

    /**
     * Perform a search using the provided filters and cache the result.
     *
     * @param array<string, mixed> $filters
     *
     * @return LengthAwarePaginator<TModel>|EloquentCollection<int, TModel>
     */
    public function search(array $filters): LengthAwarePaginator|EloquentCollection
    {
        ksort($filters);
        $serializedFilters = collect($filters)
            ->collapse()
            ->map(fn($v, $k) => "{$k}_" . $v)
            ->join('-');
        $table = $this->entity()->getTable();
        $cacheKey = "search-{$table}:{$serializedFilters}";

        return $this->cacheRemember(
            table: $this->entity()->getTable(),
            cacheKey: $cacheKey,
            callBack: fn() => $this->repository->fetchData(
                dataTypeReturn: 'builder',
            )
                ->whereIn('name', $filters['name'])
                ->get(),
        );
    }

    /**
     * Find a record by ID or throw an exception, caching the result.
     *
     *
     * @return TModel
     *
     * @throws RecordNotFoundException
     */
    public function findOrFail(int $id): EloquentModel
    {
        $table = $this->entity()->getTable();
        $cacheKey = "show-{$table}:{$id}";

        return $this->cacheRemember(
            table: $table,
            cacheKey: $cacheKey,
            callBack: function () use ($id) {
                $model = $this->repository->find(id: $id);
                if (!$model) {
                    throw new RecordNotFoundException("Record with id {$id} not found");
                }

                return $model;
            }
        );
    }

    /**
     * Find multiple records by their IDs and cache the result.
     *
     * @param array<int> $ids
     *
     * @return EloquentCollection<int, TModel>
     *
     * @throws RecordNotFoundException
     */
    public function findMany(array $ids): EloquentCollection
    {
        $fetch = $this->repository->findMany(ids: $ids);
        if ($fetch->isNotEmpty()) {
            $table = $this->entity()->getTable();
            $cacheKey = "many-{$table}:" . implode(separator: '_', array: $ids);

            return $this->cacheRemember(
                table: $this->entity()->getTable(),
                cacheKey: $cacheKey,
                callBack: fn() => $fetch
            );
        }

        return $fetch;
    }

    /**
     * Build a consistent cache key for data fetches.
     *
     * @param array<string, mixed> $filters
     */
    private function buildCacheKey(array $filters, string $cachePrefix, string $dataTypeReturn): string
    {
        $serializedFilters = $this->serializeFilters($filters);

        $parts = array_filter([
            $cachePrefix ?: null,
            'fetch-' . $this->entity()->getTable(),
            $serializedFilters ?: null,
            $dataTypeReturn === 'paginate' ? 'page_' . request('page', 1) : null,
        ]);

        return implode(':', $parts);
    }

    /**
     * Serialize a filter array into a cache-friendly string.
     *
     * @param array<string, mixed> $filters
     */
    private function serializeFilters(array $filters): string
    {
        return collect($filters)
            ->map(function ($value, $key) {
                return is_array($value)
                    ? "{$key}_" . implode(separator: ',', array: $value)
                    : "{$key}_{$value}";
            })
            ->join(glue: '|');
    }
}
