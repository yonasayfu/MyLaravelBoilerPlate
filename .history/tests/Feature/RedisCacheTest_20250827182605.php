<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class RedisCacheTest extends TestCase
{
    /**
     * Test that Redis caching is working properly
     *
     * @return void
     */
    public function test_redis_caching_works()
    {
        // Skip this test if Redis is not available
        if (!extension_loaded('redis')) {
            $this->markTestSkipped('Redis extension is not installed');
        }

        // Test storing and retrieving data from Redis cache
        $key = 'test_key';
        $value = 'test_value';

        // Store value in cache
        Cache::put($key, $value, 60);

        // Retrieve value from cache
        $cachedValue = Cache::get($key);

        // Assert that the value was stored and retrieved correctly
        $this->assertEquals($value, $cachedValue);

        // Test cache deletion
        Cache::forget($key);

        // Assert that the value was deleted
        $this->assertNull(Cache::get($key));
    }

    /**
     * Test that CachedDropdownService works properly
     *
     * @return void
     */
    public function test_cached_dropdown_service_works()
    {
        // Skip this test if Redis is not available
        if (!extension_loaded('redis')) {
            $this->markTestSkipped('Redis extension is not installed');
        }

        // Test that we can store and retrieve data using the custom method
        $key = 'test_dropdown';
        $data = [
            ['id' => 1, 'name' => 'Test 1'],
            ['id' => 2, 'name' => 'Test 2'],
        ];

        // Store data using CachedDropdownService
        $result = \App\Services\CachedDropdownService::getCustom($key, function () use ($data) {
            return $data;
        }, 60);

        // Assert that the data was stored and retrieved correctly
        $this->assertEquals($data, $result);

        // Test cache invalidation
        \App\Services\CachedDropdownService::forgetCustom($key);

        // Retrieve again - should get fresh data
        $freshData = [['id' => 3, 'name' => 'Fresh Data']];
        $result2 = \App\Services\CachedDropdownService::getCustom($key, function () use ($freshData) {
            return $freshData;
        }, 60);

        // Assert that we got fresh data
        $this->assertEquals($freshData, $result2);
    }
}
