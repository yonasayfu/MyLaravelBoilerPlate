<?php

namespace Tests\Feature;

use App\DTOs\TestDTO;
use App\Services\TestService;
use Illuminate\Http\Request;
use Tests\TestCase;

class BaseClassesTest extends TestCase
{
    /**
     * Test that the base DTO functionality works
     *
     * @return void
     */
    public function test_base_dto_works()
    {
        // Test creating a DTO
        $data = [
            'id' => 1,
            'name' => 'Test Item',
            'description' => 'This is a test item',
            'tags' => ['test', 'example'],
        ];
        
        $dto = TestDTO::create($data);
        
        // Assert that the DTO was created correctly
        $this->assertEquals(1, $dto->id);
        $this->assertEquals('Test Item', $dto->name);
        $this->assertEquals('This is a test item', $dto->description);
        $this->assertEquals(['test', 'example'], $dto->tags);
        
        // Test converting DTO to array
        $array = $dto->toArray();
        $this->assertEquals($data, $array);
        
        // Test object pooling
        $dto->release();
        
        // Create another DTO - should reuse the pooled instance
        $dto2 = TestDTO::create(['id' => 2, 'name' => 'Test Item 2']);
        $this->assertEquals(2, $dto2->id);
        $this->assertEquals('Test Item 2', $dto2->name);
    }

    /**
     * Test that the base service functionality works
     *
     * @return void
     */
    public function test_base_service_works()
    {
        $service = new TestService();
        
        // Create a mock request
        $request = Request::create('/test', 'GET', [
            'search' => 'Test Item 1',
            'sort' => 'name',
            'direction' => 'asc',
        ]);
        
        // Test getting all data with search and sort
        $data = $service->getAllTestData($request);
        
        // Assert that we got the expected data
        $this->assertCount(1, $data);
        $this->assertEquals('Test Item 1', $data[0]['name']);
        
        // Test finding by ID
        $item = $service->findTestDataById(2);
        $this->assertNotNull($item);
        $this->assertEquals('Test Item 2', $item['name']);
        
        // Test finding non-existent item
        $item = $service->findTestDataById(999);
        $this->assertNull($item);
    }

    /**
     * Test that validation rules work
     *
     * @return void
     */
    public function test_validation_rules_work()
    {
        // Test valid data
        $validData = [
            'id' => 1,
            'name' => 'Test Item',
            'description' => 'This is a test item',
            'tags' => ['test', 'example'],
        ];
        
        $validated = TestDTO::validate($validData);
        $this->assertEquals($validData, $validated);
        
        // Test invalid data (missing name)
        $invalidData = [
            'id' => 1,
            'description' => 'This is a test item',
        ];
        
        $this->expectException(\InvalidArgumentException::class);
        TestDTO::validate($invalidData);
    }
}