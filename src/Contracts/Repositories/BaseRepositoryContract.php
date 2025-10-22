<?php

namespace MkamelMasoud\StarterCoreKit\Contracts\Repositories;

use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryContract extends DeletableRepositoryContract, ReadableRepositoryContract, WritableRepositoryContract
{
    /**
     * Return model instance.
     */
    public function instance(): Model;
}
