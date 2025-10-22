<?php

namespace MkamelMasoud\StarterCoreKit\Core\Repositories\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

trait BuildQueryTrait
{
    /**
     * @param  array<string, mixed>  $filters
     */
    protected function buildQuery(
        array $filters = [],
        ?int $limit = null,
        bool $random = false
    ): EloquentBuilder {
        $query = $this->entity->newQuery()->with($this->relations);
        $query = $this->applyFilters($query, $filters);

        return $query
            // @phpstan-ignore-next-line
            ->when($random, fn ($q) => $q->inRandomOrder())
            // @phpstan-ignore-next-line
            ->when($limit, fn ($q) => $q->limit($limit));
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    protected function applyFilters(
        EloquentBuilder $query,
        array $filters
    ): EloquentBuilder {
        if (method_exists($this->entity, 'filter')) {
            return $this->entity->filter($filters);
        }

        if (empty($filters)) {
            return $query;
        }

        return $query->where(function ($sub) use ($filters) {
            foreach ($filters as $key => $value) {
                $sub->orWhere($key, 'like', "%{$value}%");
            }
        });
    }

    protected function executeQuery(
        EloquentBuilder $query,
        string $dataTypeReturn
    ): EloquentCollection|EloquentBuilder|LengthAwarePaginator {
        return match ($dataTypeReturn) {
            'paginate' => $query->paginate($this->getRecordsLimit())->withQueryString(),
            'builder' => $query,
            default => $query->get(),
        };
    }
}
