<?php

namespace MkamelMasoud\StarterCoreKit\Traits\Service;

use Illuminate\Database\Eloquent\Model;
use MkamelMasoud\StarterCoreKit\Contracts\Repositories\BaseRepositoryContract;
use MkamelMasoud\StarterCoreKit\Core\BaseDto;

/**
 * @phpstan-template TDto of BaseDto
 * @phpstan-template TRepo of BaseRepositoryContract
 */
trait ServiceSupportTrait
{
    /**
     * Validate that the DTO is the correct type.
     */
    protected function validateDto(BaseDto $dto): void
    {
        $dtoClass = $this->getDtoClass();

        if (! $dto instanceof $dtoClass) {
            throw new \InvalidArgumentException("DTO must be instance of $dtoClass");
        }
    }

    /**
     * Validate that the DTO is the correct type.
     */
    protected function validateRepo(BaseRepositoryContract $repo): void
    {
        $repoClass = $this->getRepoClass();

        if (! $repo instanceof $repoClass) {
            throw new \InvalidArgumentException("Repo must be instance of $repoClass");
        }
    }

    protected function beforeSaveAction(BaseDto $dto, ?string $existingFile = null): BaseDto
    {
        return $dto;
    }

    protected function beforeDeleteAction(Model $model): void
    {
        // by default leave it blank for all service
    }
}
