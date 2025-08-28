<?php

use App\Models\Staff;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('can be created with valid data', function () {
    $staff = Staff::factory()->create([
        'user_id' => $this->user->id,
        'first_name' => 'John',
        'last_name' => 'Doe',
        'phone_number' => '+1234567890',
        'position' => 'Developer',
        'department' => 'IT',
        'hire_date' => '2023-01-15',
        'salary' => 75000.00,
        'is_active' => true,
    ]);

    expect($staff)->toBeInstanceOf(Staff::class);
    expect($staff->first_name)->toBe('John');
    expect($staff->last_name)->toBe('Doe');
    expect($staff->full_name)->toBe('John Doe');
    expect($staff->formatted_salary)->toBe('$75,000.00');
});

it('belongs to a user', function () {
    $staff = Staff::factory()->create([
        'user_id' => $this->user->id,
    ]);

    expect($staff->user)->toBeInstanceOf(User::class);
    expect($staff->user->id)->toBe($this->user->id);
});

it('can be scoped to active staff', function () {
    Staff::factory()->create([
        'user_id' => $this->user->id,
        'is_active' => true,
    ]);

    Staff::factory()->create([
        'user_id' => $this->user->id,
        'is_active' => false,
    ]);

    $activeStaff = Staff::active()->get();

    expect($activeStaff)->toHaveCount(1);
    expect($activeStaff->first()->is_active)->toBeTrue();
});

it('can be searched by name or position', function () {
    Staff::factory()->create([
        'user_id' => $this->user->id,
        'first_name' => 'John',
        'last_name' => 'Doe',
        'position' => 'Developer',
        'department' => 'IT',
    ]);

    Staff::factory()->create([
        'user_id' => $this->user->id,
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'position' => 'Designer',
        'department' => 'Marketing',
    ]);

    $results1 = Staff::search('John')->get();
    $results2 = Staff::search('Developer')->get();
    $results3 = Staff::search('IT')->get();

    expect($results1)->toHaveCount(1);
    expect($results1->first()->first_name)->toBe('John');

    expect($results2)->toHaveCount(1);
    expect($results2->first()->position)->toBe('Developer');

    expect($results3)->toHaveCount(1);
    expect($results3->first()->department)->toBe('IT');
});