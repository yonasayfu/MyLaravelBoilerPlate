<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CachedDropdownService
{
    /**
     * Cache TTL in seconds
     */
    const CACHE_TTL = 600; // 10 minutes

    /**
     * Get users for dropdown
     *
     * @param bool $includeInactive
     * @return array
     */
    public static function getUsers(bool $includeInactive = false): array
    {
        $cacheKey = 'dropdown_users' . ($includeInactive ? '_all' : '_active');
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($includeInactive) {
            $query = DB::table('users')->select('id', 'name', 'email');
            
            if (!$includeInactive) {
                $query->whereNull('deleted_at');
            }
            
            return $query->orderBy('name')->get()->toArray();
        });
    }

    /**
     * Get staff for dropdown
     *
     * @param bool $includeInactive
     * @return array
     */
    public static function getStaff(bool $includeInactive = false): array
    {
        $cacheKey = 'dropdown_staff' . ($includeInactive ? '_all' : '_active');
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($includeInactive) {
            $query = DB::table('staff')
                ->select('id', 'first_name', 'last_name')
                ->selectRaw("CONCAT(first_name, ' ', last_name) as full_name");
            
            if (!$includeInactive) {
                $query->whereNull('deleted_at');
            }
            
            return $query->orderBy('first_name')->get()->toArray();
        });
    }

    /**
     * Get roles for dropdown
     *
     * @return array
     */
    public static function getRoles(): array
    {
        $cacheKey = 'dropdown_roles';
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () {
            return DB::table('roles')
                ->select('id', 'name')
                ->orderBy('name')
                ->get()
                ->toArray();
        });
    }

    /**
     * Get permissions for dropdown
     *
     * @return array
     */
    public static function getPermissions(): array
    {
        $cacheKey = 'dropdown_permissions';
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () {
            return DB::table('permissions')
                ->select('id', 'name')
                ->orderBy('name')
                ->get()
                ->toArray();
        });
    }

    /**
     * Refresh all dropdown caches
     *
     * @return void
     */
    public static function refreshAll(): void
    {
        self::forgetUsers();
        self::forgetStaff();
        self::forgetRoles();
        self::forgetPermissions();
    }

    /**
     * Forget users cache
     *
     * @return bool
     */
    public static function forgetUsers(): bool
    {
        return Cache::forget('dropdown_users_active') && Cache::forget('dropdown_users_all');
    }

    /**
     * Forget staff cache
     *
     * @return bool
     */
    public static function forgetStaff(): bool
    {
        return Cache::forget('dropdown_staff_active') && Cache::forget('dropdown_staff_all');
    }

    /**
     * Forget roles cache
     *
     * @return bool
     */
    public static function forgetRoles(): bool
    {
        return Cache::forget('dropdown_roles');
    }

    /**
     * Forget permissions cache
     *
     * @return bool
     */
    public static function forgetPermissions(): bool
    {
        return Cache::forget('dropdown_permissions');
    }

    /**
     * Get custom dropdown data
     *
     * @param string $key
     * @param callable $callback
     * @param int $ttl
     * @return mixed
     */
    public static function getCustom(string $key, callable $callback, int $ttl = self::CACHE_TTL)
    {
        $cacheKey = 'dropdown_custom_' . $key;
        
        return Cache::remember($cacheKey, $ttl, $callback);
    }

    /**
     * Forget custom dropdown data
     *
     * @param string $key
     * @return bool
     */
    public static function forgetCustom(string $key): bool
    {
        return Cache::forget('dropdown_custom_' . $key);
    }
}