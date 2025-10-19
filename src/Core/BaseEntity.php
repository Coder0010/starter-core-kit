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
            logger("Clear cache of table: ({$table}) with cache type [taggable]");
            Cache::tags($table)->flush();
            return;
        }

        if (Cache::getStore() instanceof \Illuminate\Cache\RedisStore) {
            logger("Clear cache of table: ({$table}) with cache type [redis]");
            $redisKeys = \Illuminate\Support\Facades\Redis::keys("{$table}:*");

            foreach ($redisKeys as $key) {
                // Remove Laravel cache prefix before forgetting
                $cacheKey = \Illuminate\Support\Str::after($key, config('cache.prefix') . ':');
                Cache::forget($cacheKey);
            }
            return;
        }

        logger("Clear cache of table: ({$table}) with cache type [default]");
        // For file/database/array drivers: use the same key pattern
        $keys = [
            "{$table}:index",
            "{$table}:show",
            "{$table}:all",
            "{$table}:random",
        ];

        foreach ($keys as $key) {
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
