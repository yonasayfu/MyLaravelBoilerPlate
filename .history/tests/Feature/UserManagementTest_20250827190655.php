<?php

namespace Tests\Feature;

use App\DTOs\CreateUserDTO;
use App\DTOs\UpdateUserDTO;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test that the user service can create a user from DTO
     *
     * @return void
     */
    public function test_user_service_can_create_user_from_dto()
    {
        $service = new UserService();

        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone_number' => '+1234567890',
            'is_active' => true,
        ];

        $dto = CreateUserDTO::create($data);
        $user = $service->createFromDTO($dto);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['email'], $user->email);
        $this->assertEquals($data['phone_number'], $user->phone_number);
        $this->assertEquals($data['is_active'], $user->is_active);
        $this->assertNotEmpty($user->password);
        $this->assertNotEquals($data['password'], $user->password); // Password should be hashed
    }

    /**
     * Test that the user service can update a user from DTO
     *
     * @return void
     */
    public function test_user_service_can_update_user_from_dto()
    {
        $service = new UserService();

        // Create a user first
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone_number' => '+1234567890',
            'is_active' => true,
        ];

        $createDto = CreateUserDTO::create($userData);
        $user = $service->createFromDTO($createDto);

        // Update the user
        $updateData = [
            'name' => 'Updated Name',
            'phone_number' => '+0987654321',
        ];

        $updateDto = UpdateUserDTO::create($updateData);
        $updatedUser = $service->updateFromDTO($user->id, $updateDto);

        $this->assertEquals($updateData['name'], $updatedUser->name);
        $this->assertEquals($updateData['phone_number'], $updatedUser->phone_number);
        $this->assertEquals($userData['email'], $updatedUser->email); // Should remain unchanged
    }

    /**
     * Test that the user service can find a user by email
     *
     * @return void
     */
    public function test_user_service_can_find_user_by_email()
    {
        $service = new UserService();

        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'is_active' => true,
        ];

        $dto = CreateUserDTO::create($data);
        $user = $service->createFromDTO($dto);

        $foundUser = $service->findUserByEmail($user->email);

        $this->assertNotNull($foundUser);
        $this->assertEquals($user->id, $foundUser->id);
        $this->assertEquals($user->email, $foundUser->email);
    }

    /**
     * Test that the user service can get all users
     *
     * @return void
     */
    public function test_user_service_can_get_all_users()
    {
        $service = new UserService();

        // Create multiple users
        for ($i = 0; $i < 5; $i++) {
            $data = [
                'name' => $this->faker->name,
                'email' => $this->faker->unique()->safeEmail,
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'is_active' => true,
            ];

            $dto = CreateUserDTO::create($data);
            $service->createFromDTO($dto);
        }

        $request = new \Illuminate\Http\Request();
        $users = $service->getAllUsers($request);

        $this->assertCount(5, $users);
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $users);
    }

    /**
     * Test that CreateUserDTO validation works correctly
     *
     * @return void
     */
    public function test_create_user_dto_validation()
    {
        // Test valid data
        $validData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'phone_number' => '+1234567890',
            'is_active' => true,
        ];

        $dto = CreateUserDTO::create($validData);
        $this->assertEquals('John Doe', $dto->name);
        $this->assertEquals('john@example.com', $dto->email);
        $this->assertEquals('+1234567890', $dto->phone_number);
        $this->assertTrue($dto->is_active);

        // Test invalid email
        $this->expectException(\InvalidArgumentException::class);
        $invalidData = [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'password' => 'password123',
        ];

        CreateUserDTO::create($invalidData);
    }

    /**
     * Test that UpdateUserDTO validation works correctly
     *
     * @return void
     */
    public function test_update_user_dto_validation()
    {
        // Test valid data
        $validData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone_number' => '+1234567890',
            'is_active' => false,
        ];

        $dto = UpdateUserDTO::create($validData);
        $array = $dto->toArray();

        $this->assertEquals('John Doe', $array['name']);
        $this->assertEquals('john@example.com', $array['email']);
        $this->assertEquals('+1234567890', $array['phone_number']);
        $this->assertFalse($array['is_active']);

        // Test partial update
        $partialData = [
            'name' => 'Jane Doe',
        ];

        $dto = UpdateUserDTO::create($partialData);
        $array = $dto->toArray();

        $this->assertEquals('Jane Doe', $array['name']);
        $this->assertCount(1, $array);
    }
}
