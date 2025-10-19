<?php

namespace MkamelMasoud\StarterCoreKit\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use MkamelMasoud\StarterCoreKit\Core\BaseDto;
use MkamelMasoud\StarterCoreKit\Repositories\Contracts\BaseRepositoryContract;

/**
 * @phpstan-template TDto of BaseDto
 * @phpstan-template TRepo of \BaseRepositoryContract
 */
trait ServiceHelperTrait
{
    /** Cache TTL in minutes (override per service if needed) */
    protected int $cacheTtl = 10;

    /** Enable/disable caching for specific service */
    protected bool $enableCache = true;


    public function getPerPage()
    {
        return $this->repository->getPerPage();
    }


    /**
     * Central cache helper that supports taggable and non-taggable cache stores.
     * Auto-detects the model tag via BaseEntity::getCacheModelName().
     * Each cache key is automatically prefixed with the model name for namespace separation.
     */
    protected function cacheRemember(string $key, \Closure $callback): mixed
    {
        if (!$this->enableCache) {
            return $callback();
        }

        $ttl = now()->addMinutes($this->cacheTtl);

        /** @var \MkamelMasoud\StarterCoreKit\Core\BaseEntity $entity */
        $entity = $this->repository->instance();

        $tag = method_exists($entity, 'getCacheModelName')
            ? $entity::getCacheModelName()
            : Str::of(class_basename($entity))->snake()->plural()->lower()->toString();

        try {
            // ✅ If cache supports tags (Redis/Memcached)
            if (Cache::getStore() instanceof \Illuminate\Cache\TaggableStore) {
                return Cache::tags([$tag])->remember($key, $ttl, $callback);
            }

            // ✅ Otherwise, prefix key with the model tag to match clearCache()
            $prefixedKey = "{$tag}:{$key}";

            return Cache::remember($prefixedKey, $ttl, $callback);
        } catch (\Throwable $e) {
            Log::warning("[BaseService] Cache failure for key: {$key} | {$e->getMessage()}");
            return $callback();
        }
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

    protected function beforeDelete(Model $model): void
    {
        // by default leave it blank for all service
    }

}
