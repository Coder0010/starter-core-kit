<?php

namespace MkamelMasoud\StarterCoreKit\Repositories\Contracts;

interface DeletableRepositoryContract
{
    public function delete(int $id): bool;
    public function forceDelete(int $id): bool;

}
