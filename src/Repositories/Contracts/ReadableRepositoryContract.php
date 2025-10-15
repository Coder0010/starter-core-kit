<?php

namespace MkamelMasoud\StarterCoreKit\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

interface ReadableRepositoryContract
{
    public function find(int $id): ?Model;
    public function all(): Builder;
    public function getFromCache(?string $column = null, ?string $operator = null, mixed $value = null): Collection;
    public function paginate(int $limit = 15);
    public function where(string $column, string $operator, mixed $value): self;
    public function with(array|string $relations): self;

}
