<?php

namespace MkamelMasoud\StarterCoreKit\Core\Repositories\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\LazyCollection;

trait BuildQueryTrait
{
    /**
     * @param array<string, mixed> $filters
     */
    protected function buildQuery(
        array $filters = [],
        ?int $limit = null,
        bool $random = false
    ): EloquentBuilder {
        $query = $this->entity->newQuery()->with(relations: $this->relations);
        return $this->applyFilters(query: $query, filters: $filters)
            ->when($random, fn ($q) => $q->inRandomOrder())
            ->when($limit, fn ($q) => $q->limit($limit));
    }

    /**
     * @param array<string, mixed> $filters
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
                if (is_array($value)) {
                    $sub->where(function ($inner) use ($key, $value) {
                        foreach ($value as $val) {
                            $inner->orWhere($key, $val);
                        }
                    });
                } else {
                    $sub->orWhere($key, 'like', "%{$value}%");
                }
            }
        });
    }

    protected function executeQuery(
        EloquentBuilder $query,
        string $dataTypeReturn
    ): EloquentCollection|EloquentBuilder|LengthAwarePaginator|SupportCollection|LazyCollection {
        return match ($dataTypeReturn) {
            'builder' => $query,
            'paginate' => $query->paginate($this->getRecordsLimit())->withQueryString(),
            default => $query->cursor()->collect(),
        };
    }
}
