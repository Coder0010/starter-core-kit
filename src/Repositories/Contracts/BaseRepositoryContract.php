<?php

namespace MkamelMasoud\StarterCoreKit\Repositories\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseRepositoryContract extends
    ReadableRepositoryContract
    , WritableRepositoryContract
    , DeletableRepositoryContract
{
    /**
     * Return model instance.
     */
    public function instance(): Model;

}
