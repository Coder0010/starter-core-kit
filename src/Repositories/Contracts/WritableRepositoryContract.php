<?php

namespace MkamelMasoud\StarterCoreKit\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

interface WritableRepositoryContract
{
    public function create(array $data): Model;
    public function update(int $id, array $data): Model;
}