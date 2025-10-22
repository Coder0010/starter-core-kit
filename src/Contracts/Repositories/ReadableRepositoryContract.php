<?php

namespace MkamelMasoud\StarterCoreKit\Contracts\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\LazyCollection;

interface ReadableRepositoryContract
{
    public function fetchData(
        array $filters = [],
        string $dataTypeReturn = 'cursor',
        ?int $limit = null,
        bool $random = false
    ): EloquentCollection|EloquentBuilder|LengthAwarePaginator|SupportCollection|LazyCollection;

    public function where(string $column, string $operator, mixed $value): self;

    public function find(int $id);

    public function findMany(array $ids);

    public function with(array|string $relations): self;
}
