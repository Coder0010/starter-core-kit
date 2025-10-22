<?php

namespace MkamelMasoud\StarterCoreKit\Core;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use MkamelMasoud\StarterCoreKit\Contracts\Repositories\BaseRepositoryContract;
use MkamelMasoud\StarterCoreKit\Contracts\ServiceContract;
use MkamelMasoud\StarterCoreKit\Core\Services\Traits\{DeletableServiceTrait,
    ReadableServiceTrait,
    WritableServiceTrait};
use MkamelMasoud\StarterCoreKit\Traits\File\HandleFileUploadTrait;
use MkamelMasoud\StarterCoreKit\Traits\Service\ServiceSupportTrait;
use MkamelMasoud\StarterCoreKit\Traits\Support\SupportCacheTrait;

/**
 *  Base service layer providing caching, transactions, and CRUD orchestration.
 *
 * @template TRepo of BaseRepositoryContract<TModel>
 * @template TDto of BaseDto
 * @template TModel of EloquentModel
 *
 * @property-read TRepo $repository
 */
abstract class BaseService implements ServiceContract
{
    use HandleFileUploadTrait,
        ServiceSupportTrait,
        SupportCacheTrait,
        ReadableServiceTrait,
        WritableServiceTrait,
        DeletableServiceTrait;

    /**
     * The repository instance injected via constructor
     *
     * @var TRepo
     */
    protected BaseRepositoryContract $repository;

    /**
     * @param TRepo $repository
     */
    public function __construct(BaseRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    /** @return class-string<TDto> */
    abstract protected function getDtoClass(): string;

    /** @return class-string<TRepo> */
    abstract protected function getRepoClass(): string;

    /**
     * Get the underlying entity model instance.
     *
     * @return TModel
     */
    public function entity(): EloquentModel
    {
        return $this->repository->instance();
    }

}
