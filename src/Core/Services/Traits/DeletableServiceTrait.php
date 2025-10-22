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
trait DeletableServiceTrait
{
    /**
     * Delete a record, either soft or hard delete.
     *
     * @param int            $id
     * @param 'soft'|'force' $type
     *
     * @return bool
     */
    public function delete(int $id, string $type = 'soft'): bool
    {
        $model = $this->repository->find($id);

        if (!$model) {
            throw new RecordNotFoundException(message: "Record with id {$id} not found");
        }

        return (bool)DB::safeTransaction(successCallback: function () use ($id, $type, $model) {
            if ($type === 'force') {
                $this->repository->forceDelete(id: $id);

                DB::afterCommit(function () use ($model) {
                    logger()->info('Model deleted successfully');
                    $this->beforeDeleteAction(model: $model);
                });
            } else {
                $this->repository->delete(id: $id);
            }

            DB::afterCommit(function () use ($model) {
                $this->clearCache($model->getTable());
            });
            return true;
        });
    }

}
