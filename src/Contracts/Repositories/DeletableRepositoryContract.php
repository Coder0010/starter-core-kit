<?php

namespace MkamelMasoud\StarterCoreKit\Contracts\Repositories;

interface DeletableRepositoryContract
{
    public function delete(int $id): bool;

    public function forceDelete(int $id): bool;
}
