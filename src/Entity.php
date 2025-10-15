<?php

namespace MkamelMasoud\StarterCoreKit;

use Illuminate\Cache\TaggableStore;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\{Cache, Redis};
use Illuminate\Support\Str;

abstract class Entity extends Model
{
    use SoftDeletes, HasFactory;

    protected $perPage = 10;

    protected static function booted()
    {
        static::saved(function () {
            static::clearCache();
        });

        static::deleted(function () {
            static::clearCache();
        });
    }

    /**
     * Clear all business cache keys.
     */
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

    public static function getCacheModelName()
    {
        return Str::of(class_basename(new static))
            ->snake()
            ->plural()
            ->lower()
        ;
    }
}
