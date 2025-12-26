<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class StatisticsCache extends Model
{
    use HasFactory;

    protected $table = 'statistics_cache';

    protected $fillable = [
        'key',
        'group',
        'data',
        'computed_at',
        'expires_at',
        'ttl'
    ];

    protected $casts = [
        'data' => 'array',
        'computed_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Get cached statistics by key
     * Returns null if expired or not found
     */
    public static function getCached($key)
    {
        $cache = self::where('key', $key)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        return $cache ? $cache->data : null;
    }

    /**
     * Store statistics with automatic expiration
     */
    public static function setCached($key, $data, $group = 'general', $ttlSeconds = 300)
    {
        $now = Carbon::now();

        return self::updateOrCreate(
            ['key' => $key],
            [
                'group' => $group,
                'data' => $data,
                'computed_at' => $now,
                'expires_at' => $now->copy()->addSeconds($ttlSeconds),
                'ttl' => $ttlSeconds
            ]
        );
    }

    /**
     * Get or compute statistics
     * Uses cached value if valid, otherwise computes and caches
     */
    public static function getOrCompute($key, callable $computeCallback, $group = 'general', $ttlSeconds = 300)
    {
        // Try to get cached value
        $cached = self::getCached($key);

        if ($cached !== null) {
            return $cached;
        }

        // Compute new value
        $data = $computeCallback();

        // Cache the result
        self::setCached($key, $data, $group, $ttlSeconds);

        return $data;
    }

    /**
     * Clear expired cache entries
     */
    public static function clearExpired()
    {
        return self::where('expires_at', '<', Carbon::now())->delete();
    }

    /**
     * Clear cache by group
     */
    public static function clearGroup($group)
    {
        return self::where('group', $group)->delete();
    }

    /**
     * Clear all cache
     */
    public static function clearAll()
    {
        return self::truncate();
    }

    /**
     * Force refresh a specific key
     */
    public static function forceRefresh($key)
    {
        return self::where('key', $key)->delete();
    }

    /**
     * Check if cache is still valid
     */
    public static function isValid($key)
    {
        return self::where('key', $key)
            ->where('expires_at', '>', Carbon::now())
            ->exists();
    }

    /**
     * Get cache info (for debugging)
     */
    public static function getCacheInfo($key)
    {
        $cache = self::where('key', $key)->first();

        if (!$cache) {
            return null;
        }

        return [
            'key' => $cache->key,
            'group' => $cache->group,
            'computed_at' => $cache->computed_at->toDateTimeString(),
            'expires_at' => $cache->expires_at->toDateTimeString(),
            'is_valid' => $cache->expires_at > Carbon::now(),
            'remaining_seconds' => max(0, $cache->expires_at->diffInSeconds(Carbon::now(), false) * -1),
            'ttl' => $cache->ttl
        ];
    }
}
