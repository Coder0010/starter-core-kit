<?php

namespace MkamelMasoud\StarterCoreKit\Contracts\Repositories;

use Illuminate\Database\Eloquent\Model;

interface WritableRepositoryContract
{
    public function store(array $data): Model;

    public function update(int $id, array $data): Model;
}
