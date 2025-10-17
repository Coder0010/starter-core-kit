<?php

namespace MkamelMasoud\StarterCoreKit\Core;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\RecordNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use MkamelMasoud\StarterCoreKit\Contracts\ServiceContract;
use MkamelMasoud\StarterCoreKit\Repositories\Contracts\BaseRepositoryContract;
use MkamelMasoud\StarterCoreKit\Traits\HandleFileUploadTrait;
use MkamelMasoud\StarterCoreKit\Traits\ServiceHelperTrait;

/**
 * @template TRepo of BaseRepositoryContract
 * @template TDto of BaseDto
 */
abstract class BaseService implements ServiceContract
{
    use HandleFileUploadTrait, ServiceHelperTrait;

    /** @var BaseRepositoryContract */
    protected BaseRepositoryContract $repository;

    public function __construct(BaseRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    /** @return class-string<TDto> */
    abstract protected function getDtoClass(): string;

    /** @return class-string<TRepo> */
    abstract protected function getRepoClass(): string;


    public function all(): Builder
    {
        return $this->repository->all()->latest();
    }

    public function getFromCache(?string $column = null, ?string $operator = null, mixed $value = null): Collection
    {
        return $this->repository->getFromCache($column, $operator, $value);
    }

    public function show($id): Model
    {
        $model = $this->repository->find($id);

        if (!$model) {
            throw new RecordNotFoundException("Record with id {$id} not found");
        }

        return $model;
    }

    /**
     * Store a new record.
     *
     * @param BaseDto $dto
     *
     * @return Model
     */
    public function store(BaseDto $dto): Model
    {
        $this->validateDto($dto);

        return DB::transaction(function () use ($dto) {
            try {
                $dto = $this->beforeSaveAction($dto);

                return $this->repository->create($dto->toArray());
            } catch (\Exception $e) {
                $this->deleteFileIfExists($dto->file);
            }
        });
    }

    /**
     * Update an existing record.
     *
     * @param int     $id
     * @param BaseDto $dto
     *
     * @return Model
     * @throws RecordNotFoundException
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
     * Delete a record.
     *
     * @throws RecordNotFoundException
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
     * Delete a record.
     *
     * @throws RecordNotFoundException
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
