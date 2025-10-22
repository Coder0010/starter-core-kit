<?php

namespace MkamelMasoud\StarterCoreKit\Core\Services\Traits;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\RecordNotFoundException;
use Illuminate\Support\Facades\DB;
use MkamelMasoud\StarterCoreKit\Contracts\Repositories\BaseRepositoryContract;
use MkamelMasoud\StarterCoreKit\Core\BaseDto;
use MkamelMasoud\StarterCoreKit\Core\BaseService;

/**
 * @mixin BaseService
 * @template TRepo of BaseRepositoryContract<TModel>
 * @template TDto of BaseDto
 * @template TModel of EloquentModel
 * @property-read BaseRepositoryContract $repository
 */
trait WritableServiceTrait
{
    /**
     * Create and persist a new record.
     *
     * @param TDto $dto
     *
     * @return EloquentModel
     *
     */
    public function store(BaseDto $dto): EloquentModel
    {
        $this->validateDto(dto: $dto);

        return DB::safeTransaction(successCallback: function () use ($dto) {
            $dto = $this->beforeSaveAction(dto: $dto);

            $model = $this->repository->store(data: $dto->toArray());
            DB::afterCommit(function () use ($model) {
                $this->clearCache($model->getTable());
            });

            return $model;
        }, failCallback: function () use ($dto) {
            if (property_exists($dto, 'file') && $dto->file !== null) {
                $this->deleteFileIfExists($dto->file);
            }
        });
    }

    /**
     * Update an existing record safely and refresh its cache.
     *
     * @param int     $id
     * @param BaseDto $dto
     *
     * @return EloquentModel
     *
     */
    public function update(int $id, BaseDto $dto): EloquentModel
    {
        $this->validateDto(dto: $dto);
        $model = $this->repository->find(id: $id);

        if (!$model) {
            throw new RecordNotFoundException(message: "Record with id {$id} not found");
        }
        return DB::safeTransaction(successCallback: function () use ($dto, $model) {
            $dto = $this->beforeSaveAction(dto: $dto, existingFile: $model->file ?? null);

            $model = $this->repository->update(id: $model->id, data: $dto->toArray());

            DB::afterCommit(function () use ($model) {
                $this->clearCache($model->getTable());
            });

            return $model->refresh();
        }, failCallback: function () use ($dto) {
            if (property_exists($dto, 'file') && $dto->file !== null) {
                $this->deleteFileIfExists($dto->file);
            }
        });

    }
}
