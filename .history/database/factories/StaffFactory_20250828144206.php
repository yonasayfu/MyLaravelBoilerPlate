<?php

namespace Database\Factories;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StaffFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Staff::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'phone_number' => $this->faker->phoneNumber(),
            'position' => $this->faker->jobTitle(),
            'department' => $this->faker->randomElement(['IT', 'HR', 'Finance', 'Marketing', 'Operations']),
            'hire_date' => $this->faker->date(),
            'salary' => $this->faker->randomFloat(2, 30000, 150000),
            'profile_photo_path' => null,
            'is_active' => $this->faker->boolean(80), // 80% chance of being active
        ];
    }

    /**
     * Indicate that the staff member is active.
     */
    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the staff member is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }
}
