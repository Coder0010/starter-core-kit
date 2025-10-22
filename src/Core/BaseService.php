<?php

namespace MkamelMasoud\StarterCoreKit\Core;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\RecordNotFoundException;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\DB;
use MkamelMasoud\StarterCoreKit\Contracts\Repositories\BaseRepositoryContract;
use MkamelMasoud\StarterCoreKit\Traits\File\HandleFileUploadTrait;
use MkamelMasoud\StarterCoreKit\Traits\Service\ServiceSupportTrait;
use MkamelMasoud\StarterCoreKit\Traits\Support\SupportCacheTrait;
use Throwable;

/**
 * @template TRepo of BaseRepositoryContract
 * @template TDto of BaseDto
 * @template TModel of EloquentModel
 *
 * @property-read TRepo $repository
 */
abstract class BaseService
{
    use HandleFileUploadTrait, ServiceSupportTrait, SupportCacheTrait;

    /**
     * The repository instance injected via constructor
     *
     * @var TRepo
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

    public function entity(): EloquentModel
    {
        return $this->repository->instance();
    }

    public function getRecordsLimit(): int
    {
        return $this->repository->getRecordsLimit();
    }

    public function fetchData(
        array $filters = [],
        string $dataTypeReturn = 'get',
        ?int $limit = null,
        bool $random = false,
        string $cachePrefix = ''
    ): EloquentCollection|EloquentBuilder|LengthAwarePaginator|SupportCollection {
        $serializedFilters = collect($filters)
            ->map(fn ($value, $key) => "{$key}_{$value}")
            ->join(glue: '|');

        $table = $this->entity()->getTable();
        $cacheKey = '';
        if ($cachePrefix != '' || $cachePrefix != null) {
            $cacheKey .= "{$cachePrefix}-";
        }
        $cacheKey .= "fetch-{$table}";
        if ($serializedFilters != '') {
            $cacheKey .= ":{$serializedFilters}";
        }
//        if ($dataTypeReturn == 'paginate') {
//            $cacheKey .= ':page_'.(request('page', 1));
//        }

        return $this->cacheRemember(
            table: $table,
            cacheKey: $cacheKey,
            callBack: fn () => $this->repository->fetchData(
                filters: $filters,
                dataTypeReturn: $dataTypeReturn,
                limit: $limit,
                random: $random,
            )
        );
    }

    public function inRandomOrder(int $limit = 3): EloquentCollection
    {
        return $this->cacheRemember(
            table: $this->entity()->getTable(),
            cacheKey: "random:limit_{$limit}",
            callBack: fn () => $this->repository->fetchData(
                dataTypeReturn: 'builder',
            )->inRandomOrder()
                ->limit(value: $limit)
                ->get()
        );
    }

    public function search(array $filters): LengthAwarePaginator|EloquentCollection
    {
        ksort($filters);
        $serializedFilters = collect($filters)
            ->collapse()
            ->map(fn ($v, $k) => "{$k}_".$v)
            ->join('-');

        return $this->cacheRemember(
            table: $this->entity()->getTable(),
            cacheKey: "search:{$serializedFilters}",
            callBack: fn () => $this->repository->fetchData(
                dataTypeReturn: 'builder',
            )
                ->whereIn('name', $filters['name'])
                ->get()
        );
    }

    /** @return TModel */
    public function findOrFail(int $id): EloquentModel
    {
        return $this->cacheRemember(
            table: $this->entity()->getTable(),
            cacheKey: "show:{$id}",
            callBack: function () use ($id) {
                $model = $this->repository->find(id: $id);
                if (! $model) {
                    throw new RecordNotFoundException("Record with id {$id} not found");
                }

                return $model;
            }
        );
    }

    /** @return EloquentCollection<int, TModel> */
    public function findMany(array $ids): EloquentCollection
    {
        $fetch = $this->repository->findMany(ids: $ids);
        if ($fetch->isNotEmpty()) {
            return $this->cacheRemember(
                table: $this->entity()->getTable(),
                cacheKey: 'many:'.implode(separator: '_', array: $ids),
                callBack: fn () => $fetch
            );
        }

        return $fetch;
    }

    /**
     * Store a new record in a transaction-safe way.
     *
     * @throws Throwable
     */
    public function store(BaseDto $dto): EloquentModel
    {
        $this->validateDto(dto: $dto);

        return DB::transaction(function () use ($dto) {
            try {
                $dto = $this->beforeSaveAction(dto: $dto);

                $model = $this->repository->store(data: $dto->toArray());
                DB::afterCommit(function () use ($model) {
                    $this->clearCache($model->getTable());
                });

                return $model;
            } catch (Throwable $e) {
                logger()->error($e->getMessage());
                if (property_exists($dto, 'file') && $dto->file !== null) {
                    $this->deleteFileIfExists($dto->file);
                }
                throw $e;
            }
        });
    }

    /**
     * Update an existing record safely and handle file updates.
     *
     * @throws Throwable
     */
    public function update(int $id, BaseDto $dto): EloquentModel
    {
        $this->validateDto(dto: $dto);
        $model = $this->repository->find(id: $id);

        if (! $model) {
            throw new RecordNotFoundException(message: "Record with id {$id} not found");
        }

        return DB::transaction(function () use ($dto, $model) {
            try {
                $dto = $this->beforeSaveAction(dto: $dto, existingFile: $model->file ?? null);

                $model = $this->repository->update(id: $model->id, data: $dto->toArray());

                DB::afterCommit(function () use ($model) {
                    $this->clearCache($model->getTable());
                });

                return $model->refresh();
            } catch (Throwable $e) {
                logger()->error($e->getMessage());
                if (property_exists($dto, 'file') && $dto->file !== null) {
                    $this->deleteFileIfExists(path: $dto->file);
                }
                throw $e;
            }
        });
    }

    /**
     * Delete a record normally (soft delete if enabled).
     */
    public function delete(int $id, string $type = 'soft'): bool
    {
        $model = $this->repository->find($id);

        if (! $model) {
            throw new RecordNotFoundException(message: "Record with id {$id} not found");
        }

        try {
            DB::transaction(function () use ($id, $type, $model) {
                if ($type === 'force') {
                    $this->repository->forceDelete(id: $id);

                    DB::afterCommit(function () use ($model) {
                        $this->beforeDeleteAction(model: $model);
                    });
                } else {
                    $this->repository->delete(id: $id);
                }

                DB::afterCommit(function () use ($model) {
                    $this->clearCache($model->getTable());
                });
            });

            return true;
        } catch (Throwable $e) {
            logger()->error($e->getMessage());

            return false;
        }
    }
}
