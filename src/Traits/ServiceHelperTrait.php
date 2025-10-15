<?php

namespace MkamelMasoud\StarterCoreKit\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use MkamelMasoud\StarterCoreKit\BaseDto;
use MkamelMasoud\StarterCoreKit\Repositories\Contracts\BaseRepositoryContract;

trait ServiceHelperTrait
{
    public function getPerPage()
    {
        return $this->repository->getPerPage();
    }

    /**
     * Validate that the DTO is the correct type.
     *
     * @param BaseDto $dto
     */
    protected function validateDto(BaseDto $dto): void
    {
        $dtoClass = $this->getDtoClass();

        if (!$dto instanceof $dtoClass) {
            throw new \InvalidArgumentException("DTO must be instance of $dtoClass");
        }
    }

    /**
     * Validate that the DTO is the correct type.
     *
     * @param BaseRepositoryContract $repo
     */
    protected function validateRepo(BaseRepositoryContract $repo): void
    {
        $repoClass = $this->getRepoClass();

        if (!$repo instanceof $repoClass) {
            throw new \InvalidArgumentException("Repo must be instance of $repoClass");
        }
    }

    protected function beforeSaveAction(BaseDto $dto, ?string $existingFile = null): BaseDto
    {
        return $dto;
    }

    protected function beforeDelete($model): void
    {
        // by default leave it blank for all service 
    }

}
