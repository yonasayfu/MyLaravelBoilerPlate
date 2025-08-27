<?php

namespace App\Services;

use Illuminate\Http\Request;

class TestService extends PerformanceOptimizedBaseService
{
    /**
     * Cache prefix for this service
     *
     * @var string
     */
    protected string $cachePrefix = 'test_service';

    /**
     * Test data
     *
     * @var array
     */
    private array $testData = [
        ['id' => 1, 'name' => 'Test Item 1', 'description' => 'This is test item 1'],
        ['id' => 2, 'name' => 'Test Item 2', 'description' => 'This is test item 2'],
        ['id' => 3, 'name' => 'Test Item 3', 'description' => 'This is test item 3'],
    ];

    /**
     * Get all test data
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function getAllTestData(Request $request): array
    {
        $data = $this->testData;

        // Apply search filter
        if ($request->has('search')) {
            $search = $request->input('search');
            $data = array_filter($data, function ($item) use ($search) {
                return stripos($item['name'], $search) !== false || 
                       stripos($item['description'], $search) !== false;
            });
        }

        // Apply sorting
        if ($request->has('sort')) {
            $sort = $request->input('sort');
            $direction = $request->input('direction', 'asc');
            
            if (in_array($sort, ['id', 'name'])) {
                usort($data, function ($a, $b) use ($sort, $direction) {
                    $result = $a[$sort] <=> $b[$sort];
                    return $direction === 'desc' ? -$result : $result;
                });
            }
        }

        return array_values($data);
    }

    /**
     * Find test data by ID
     *
     * @param int $id
     * @return array|null
     */
    public function findTestDataById(int $id): ?array
    {
        foreach ($this->testData as $item) {
            if ($item['id'] == $id) {
                return $item;
            }
        }
        
        return null;
    }

    /**
     * Apply search filter to query
     *
     * @param mixed $query
     * @param string $search
     * @return void
     */
    protected function applySearch($query, string $search): void
    {
        // This is just a placeholder since we're not using Eloquent in this test
        // In a real service, this would apply the search to the database query
    }
}