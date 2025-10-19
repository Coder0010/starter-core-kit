<?php

namespace MkamelMasoud\StarterCoreKit\Repositories\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection as SupportCollection;

interface ReadableRepositoryContract
{
    public function all(): Builder;

    public function inRandomOrder(int $limit = 3): EloquentCollection;

    public function allPaginated(?int $limit = null): LengthAwarePaginator;

    public function allFromCache(
        ?string $column = null,
        ?string $operator = null,
        mixed $value = null
    ): SupportCollection;

    public function where(string $column, string $operator, mixed $value): self;

    public function find(int $id);

    public function findMany(array $ids);

    public function search(string|array $q, string $column = 'name', int $limit = 24);

    public function with(array|string $relations): self;
}
