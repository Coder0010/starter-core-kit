<?php

namespace MkamelMasoud\StarterCoreKit\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\RecordNotFoundException;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\LazyCollection;
use MkamelMasoud\StarterCoreKit\Core\BaseDto;

interface ServiceContract
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
    ): EloquentCollection|EloquentBuilder|LengthAwarePaginator|SupportCollection|LazyCollection;

    /**
     * Fetch a random subset of records and cache the result.
     *
     * @return EloquentCollection<int, TModel>
     */
    public function inRandomOrder(int $limit = 3): EloquentCollection;

    /**
     * Perform a search using the provided filters and cache the result.
     *
     * @param array<string, mixed> $filters
     *
     * @return LengthAwarePaginator<TModel>|EloquentCollection<int, TModel>
     */
    public function search(array $filters): LengthAwarePaginator|EloquentCollection;

    /**
     * Find a record by ID or throw an exception, caching the result.
     *
     *
     * @return TModel
     *
     * @throws RecordNotFoundException
     */
    public function findOrFail(int $id): EloquentModel;

    /**
     * Find multiple records by their IDs and cache the result.
     *
     * @param array<int> $ids
     *
     * @return EloquentCollection<int, TModel>
     *
     * @throws RecordNotFoundException
     */
    public function findMany(array $ids): EloquentCollection;

    /**
     * Create and persist a new record.
     *
     * @param TDto $dto
     *
     * @return EloquentModel
     *
     */
    public function store(BaseDto $dto): EloquentModel;

    /**
     * Update an existing record safely and refresh its cache.
     *
     * @param int     $id
     * @param BaseDto $dto
     *
     * @return EloquentModel
     *
     */
    public function update(int $id, BaseDto $dto): EloquentModel;

    /**
     * Delete a record, either soft or hard delete.
     *
     * @param int            $id
     * @param 'soft'|'force' $type
     *
     * @return bool
     */
    public function delete(int $id, string $type = 'soft'): bool;
}
