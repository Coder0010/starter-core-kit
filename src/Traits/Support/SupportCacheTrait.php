<?php

namespace MkamelMasoud\StarterCoreKit\Traits\Support;

use Closure;
use Illuminate\Cache\RedisStore;
use Illuminate\Cache\TaggableStore;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

trait SupportCacheTrait
{
    /**
     * Resolve cache context and configuration for a given table.
     */
    private function resolveCacheConfigs(string $table, ?string $cacheKey = null): array
    {
        $tag = "{$table}_tag";
        $store = Cache::getStore();
        $isUseTags = $store instanceof TaggableStore;
        $cacheDriver = $isUseTags ? Cache::tags([$tag]) : Cache::store();

        $context = [
            'tag'        => $tag,
            'store'      => $store,
            'isUseTags'  => $isUseTags,
            'cacheDriver'=> $cacheDriver,
        ];

        if ($cacheKey !== null) {
            $context['fullKey'] = $isUseTags ? $cacheKey : "{$tag}:{$cacheKey}";
        }

        return $context;
    }

    /**
     * Remember or store cache data with unified handling for taggable and non-taggable stores.
     */
    public function cacheRemember(
        string $table,
        string $cacheKey,
        Closure $callBack
    ): mixed {
        if (! resolveIsCacheEnabled()) {
            return $callBack();
        }

        try {
            $ttl = resolveCacheTtl();
            $cacheContext = $this->resolveCacheConfigs($table, $cacheKey);
            $cacheDriver = $cacheContext['cacheDriver'];
            $fullKey = $cacheContext['fullKey'];

            // 🧩 Return cached value if present
            if ($cacheDriver->has($fullKey)) {
                logger()->info("✅ Retrieved cache for table [{$table}] with key [{$fullKey}].");
                return $cacheDriver->get($fullKey);
            }

            // Execute callable to fetch fresh data
            $data = $callBack();

            // ✅ Determine cacheability
            $isCacheable = match (true) {
                $data instanceof EloquentCollection      => $data->isNotEmpty(),
                $data instanceof LengthAwarePaginator    => $data->count() > 0,
                is_array($data)                          => ! empty($data),
                default                                  => ! is_null($data),
            };

            if ($isCacheable) {
                $cacheDriver->put($fullKey, $data, $ttl);
                logger()->info("💾 Stored    cache for table [{$table}] with key [{$fullKey}] (TTL: {$ttl}).");
            } else {
                logger()->info("⚠️ Skipped caching for table [{$table}] with key [{$fullKey}] — result empty.");
            }

            return $data;
        } catch (\Throwable $e) {
            logger()->error("❌ Cache failure for [{$table}] key [{$cacheKey}] | {$e->getMessage()}");
            return $callBack();
        }
    }

    /**
     * Clear cache for a specific model or table.
     */
    public function clearCache(string $table): void
    {
        if (! resolveIsCacheEnabled()) {
            return;
        }

        try {
            $cacheContext = $this->resolveCacheConfigs($table);
            $tag = $cacheContext['tag'];
            $store = $cacheContext['store'];
            $isUseTags = $cacheContext['isUseTags'];
            $cacheDriver = $cacheContext['cacheDriver'];

            // 🧩 Taggable stores (Redis / Memcached)
            if ($isUseTags) {
                $cacheDriver->flush();
                logger()->info("🧹 Cleared cache for [{$table}] using tag [{$tag}] (TaggableStore).");
                return;
            }

            // 🧩 Redis without tags — safer SCAN iteration
            if ($store instanceof RedisStore) {
                $prefix = config('cache.prefix') . ':';
                $pattern = "{$prefix}{$table}:*";
                $cursor = 0;

                do {
                    [$cursor, $keys] = Redis::scan($cursor, 'MATCH', $pattern, 'COUNT', 100);
                    foreach ($keys as $key) {
                        $cacheKey = Str::after($key, $prefix);
                        Cache::forget($cacheKey);
                        logger()->info("🧹 Cleared Redis cache key [{$cacheKey}] for table [{$table}].");
                    }
                } while ($cursor !== 0);

                return;
            }

            // 🧩 Fallback for file/array drivers
            $variants = [
                "{$tag}:index",
                "{$tag}:show",
                "{$tag}:all",
                "{$tag}:random",
            ];

            foreach ($variants as $key) {
                Cache::forget($key);
                logger()->info("🧹 Cleared fallback cache key [{$key}] for table [{$table}].");
            }
        } catch (\Throwable $e) {
            logger()->error("❌ Cache clear failure for table [{$table}] | {$e->getMessage()}");
        }
    }
}
