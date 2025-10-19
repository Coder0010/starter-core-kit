<?php

namespace MkamelMasoud\StarterCoreKit\Core;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\RecordNotFoundException;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use MkamelMasoud\StarterCoreKit\Repositories\Contracts\BaseRepositoryContract;
use MkamelMasoud\StarterCoreKit\Traits\HandleFileUploadTrait;
use MkamelMasoud\StarterCoreKit\Traits\ServiceHelperTrait;

/**
 * @template TRepo of BaseRepositoryContract
 * @template TDto of BaseDto
 * @template TModel of Model
 *
 * @property-read TRepo $repository
 */
abstract class BaseService
{
    use HandleFileUploadTrait, ServiceHelperTrait;

    /** The repository instance injected via constructor
     * @var BaseRepositoryContract
     */
    protected BaseRepositoryContract $repository;

    public function __construct(BaseRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    /** @return class-string<TDto> */
    abstract protected function getDtoClass(): string;

    /** @return class-string<TRepo> */
    abstract protected function getRepoClass(): string;

    /** @return \Illuminate\Database\Eloquent\Builder<TModel> */
    public function all(): Builder
    {
        return $this->repository->all()->latest();
    }

    public function allPaginated(?int $limit = null): LengthAwarePaginator
    {
        $page = request('page', 1);
        $key = "page:{$page}";

        return $this->cacheRemember($key, fn() => $this->repository->allPaginated($limit));
    }

    public function inRandomOrder(int $limit = 3): EloquentCollection
    {
        $key = "random:limit_{$limit}";

        return $this->cacheRemember($key, fn() => $this->repository->inRandomOrder($limit));
    }

    public function allFromCache(
        ?string $column = null,
        ?string $operator = null,
        mixed $value = null
    ): SupportCollection {
        $key = "all";

        return $this->cacheRemember($key, fn() => $this->repository->allFromCache($column, $operator, $value));
    }

    /** @return TModel */
    public function findOrFail(int $id): Model
    {
        $key = "show:{$id}";

        return $this->cacheRemember($key, function () use ($id) {
            $model = $this->repository->find($id);
            if (!$model) {
                throw new RecordNotFoundException("Record with id {$id} not found");
            }
            return $model;
        });
    }

    /** @return EloquentCollection<int, TModel> */
    public function findMany(array $ids): EloquentCollection
    {
        $key = "many:" . implode('_', $ids);

        return $this->cacheRemember($key, fn() => $this->repository->findMany($ids));
    }

    public function search(string|array $q, string $column = 'name', ?int $limit = null): EloquentCollection
    {
        $query = is_array($q) ? implode('-', $q) : $q;
        $key = "search:" . Str::slug($query);

        return $this->cacheRemember($key, fn() => $this->repository->search(q: $q, limit: $limit));
    }

    /**
     * Store a new record in a transaction-safe way.
     */
    public function store(BaseDto $dto): Model
    {
        $this->validateDto($dto);

        return DB::transaction(function () use ($dto) {
            try {
                $dto = $this->beforeSaveAction($dto);
                return $this->repository->store($dto->toArray());
            } catch (\Exception $e) {
                $this->deleteFileIfExists($dto->file);
                throw $e;
            }
        });
    }

    /**
     * Update an existing record safely and handle file updates.
     */
    public function update(int $id, BaseDto $dto): Model
    {
        $this->validateDto($dto);
        $model = $this->repository->find($id);

        if (!$model) {
            throw new RecordNotFoundException("Record with id {$id} not found");
        }

        return DB::transaction(function () use ($dto, $model) {
            try {
                $dto = $this->beforeSaveAction($dto, $model->file);
                return $this->repository->update($model->id, $dto->toArray())->refresh();
            } catch (\Exception $e) {
                $this->deleteFileIfExists($dto->file);
                throw $e;
            }
        });
    }

    /**
     * Delete a record normally (soft delete if enabled).
     */
    public function delete(int $id): bool
    {
        $model = $this->repository->find($id);
        if (!$model) {
            throw new RecordNotFoundException("Record with id {$id} not found");
        }
        $this->beforeDelete($model);
        return $this->repository->delete($id);
    }

    /**
     * Force delete a record from the database (bypass soft deletes).
     */
    public function forceDelete(int $id): bool
    {
        $model = $this->repository->find($id);
        if (!$model) {
            throw new RecordNotFoundException("Record with id {$id} not found");
        }
        $this->beforeDelete($model);
        return $this->repository->forceDelete($id);
    }
}
