<?php

namespace MkamelMasoud\StarterCoreKit\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryContract extends
    ReadableRepositoryContract,
    WritableRepositoryContract,
    DeletableRepositoryContract
{
    /**
     * Return model instance.
     */
    public function instance(): Model;
}
