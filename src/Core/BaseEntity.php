<?php

namespace MkamelMasoud\StarterCoreKit\Core;

use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\{Cache, Redis};
use Illuminate\Support\Str;
use Illuminate\Cache\TaggableStore;

abstract class BaseEntity extends Model
{
    use SoftDeletes, HasFactory;

    public static bool $shouldClearCache = true;

    protected static function booted()
    {
        foreach (['saved', 'deleted', 'restored'] as $event) {
            static::$event(fn () => static::clearCache($event));
        }
    }

    protected static function clearCache(): void
    {
        $table = static::getCacheModelName();
        if (Cache::getStore() instanceof TaggableStore) {
            logger("Clear cache of table: ( {$table} ) with cache type [ taggable ]");
            // If the cache driver supports tags, flush everything under the '{$table}' tag
            Cache::tags($table)->flush();
        } elseif (Cache::getStore() instanceof \Illuminate\Cache\RedisStore) {
            logger("Clear cache of table: ( {$table} ) with cache type [ redis] ");
            // If using Redis directly, delete keys by prefix (for an old version of redis)
            $redisKeys = Redis::keys($table . '*');
            foreach ($redisKeys as $key) {
                Cache::forget(Str::after($key, config('cache.prefix') . ':'));
            }
        } else {
            logger("Clear cache of table: ( {$table} ) with cache type [ default ]");
            // For file/database/other non-taggable drivers: list known keys manually
            $keys = ["index_{$table}", "show_{$table}"];
            foreach ($keys as $key) {
                Cache::forget($key);
            }
        }
    }
    
    protected static function newClearCache(string $event = 'unknown'): void
    {
        if (!static::$shouldClearCache) return;

        $model = static::getCacheModelName();
        $store = Cache::getStore();
        $driver = config('cache.default');

        // 1️⃣ Taggable cache
        if ($store instanceof TaggableStore) {
            logger("[StarterCoreKit] Cache cleared | model={$model} | driver={$driver} | type=taggable | event={$event}");
            Cache::tags($model)->flush();
            return;
        }

        // 2️⃣ Redis store
        if ($store instanceof \Illuminate\Cache\RedisStore) {
            logger("[StarterCoreKit] Cache cleared | model={$model} | driver={$driver} | type=redis | event={$event}");
            $connection = $store->connection();
            $cursor = null;

            do {
                [$cursor, $keys] = $connection->scan($cursor, 'MATCH', $model . '*', 'COUNT', 500);
                foreach ($keys as $key) {
                    $connection->del($key);
                }
            } while ($cursor != 0);

            return;
        }

        // 3️⃣ Default (file, database, etc.)
        logger("[StarterCoreKit] Cache cleared | model={$model} | driver={$driver} | type=default | event={$event}");
        foreach (["index_{$model}", "show_{$model}"] as $key) {
            Cache::forget($key);
        }
    }

    public static function getCacheModelName(): string
    {
        return Str::of(class_basename(new static))
            ->snake()
            ->plural()
            ->lower();
    }
}
