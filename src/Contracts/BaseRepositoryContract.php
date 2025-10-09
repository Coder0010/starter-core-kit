<?php

namespace MkamelMasoud\StarterCoreKit\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseRepositoryContract
{
    /**
     * Return model instance.
     */
    public function instance(): Model;

    /**
     * Create a new entity.
     */
    public function create(array $data): Model;

    /**
     * Update entity by ID.
     */
    public function update(int $id, array $data): Model;

    /**
     * Delete entity by ID.
     */
    public function delete(int $id): bool;

    /**
     * Return all records (queryable).
     */
    public function all(): Builder;

    /**
     * Paginate records.
     */
    public function paginate(int $limit = 15): LengthAwarePaginator;

    /**
     * Eager load relationships.
     */
    public function with(array|string $relations): self;

    /**
     * Add where condition.
     */
    public function where(string $column, string $operator, mixed $value): self;

    /**
     * Find entity by ID.
     */
    public function find(int $id): ?Model;

}
