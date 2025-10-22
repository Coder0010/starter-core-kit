<?php

namespace MkamelMasoud\StarterCoreKit\Contracts\Repositories;

use Illuminate\Database\Eloquent\Model as EloquentModel;

interface BaseRepositoryContract extends DeletableRepositoryContract, ReadableRepositoryContract, WritableRepositoryContract
{
    /**
     * Return model instance.
     */
    public function instance(): EloquentModel;
}
