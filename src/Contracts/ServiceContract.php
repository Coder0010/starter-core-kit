<?php

namespace MkamelMasoud\StarterCoreKit\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\RecordNotFoundException;
use Illuminate\Support\Collection;
use MkamelMasoud\StarterCoreKit\Core\BaseDto;

interface ServiceContract
{
    public function all(): Builder;

    public function getFromCache(?string $column = null, ?string $operator = null, mixed $value = null): Collection;

    public function show($id): Model;

    /**
     * Store a new record.
     */
    public function store(BaseDto $dto): Model;

    /**
     * Update an existing record.
     *
     *
     * @throws RecordNotFoundException
     */
    public function update(int $id, BaseDto $dto): Model;

    /**
     * Delete a record.
     *
     * @throws RecordNotFoundException
     */
    public function delete(int $id): bool;

    /**
     * Delete a record.
     *
     * @throws RecordNotFoundException
     */
    public function forceDelete(int $id): bool;
}
