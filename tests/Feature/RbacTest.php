<?php

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Spatie\Permission\Models\Role;

// Seed roles and permissions before running tests
beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

it('can assign roles to users', function () {
    // Create a user
    $user = User::factory()->create();

    // Create roles
    $adminRole = Role::findByName('admin');
    $staffRole = Role::findByName('staff');

    // Assign roles to user
    $user->assignRole('admin');

    // Verify user has the admin role
    expect($user->hasRole('admin'))->toBeTrue();
    expect($user->hasRole('staff'))->toBeFalse();

    // Verify user has admin permissions
    expect($user->can('view-users'))->toBeTrue();
    expect($user->can('create-users'))->toBeTrue();
    expect($user->can('delete-users'))->toBeTrue();

    // Verify user doesn't have super-admin permissions
    expect($user->can('manage-settings'))->toBeFalse();
});

it('can check role-based access', function () {
    // Create users with different roles
    $adminUser = User::factory()->create();
    $staffUser = User::factory()->create();

    // Assign roles
    $adminUser->assignRole('admin');
    $staffUser->assignRole('staff');

    // Verify admin user has appropriate permissions
    expect($adminUser->can('view-users'))->toBeTrue();
    expect($adminUser->can('create-staff'))->toBeTrue();

    // Verify staff user has limited permissions
    expect($staffUser->can('view-profile'))->toBeTrue();
    expect($staffUser->can('update-profile'))->toBeTrue();
    expect($staffUser->can('view-users'))->toBeFalse();
    expect($staffUser->can('create-staff'))->toBeFalse();
});

it('blocks unauthorized access', function () {
    // Create a staff user
    $user = User::factory()->create();
    $user->assignRole('staff');

    // Try to access admin-only route
    $response = $this->actingAs($user)->get('/rbac/dashboard');

    // Should be blocked with 403
    $response->assertStatus(403);
});

it('allows authorized access to rbac dashboard', function () {
    // Create an admin user
    $user = User::factory()->create();
    $user->assignRole('admin');

    // Try to access RBAC dashboard
    $response = $this->actingAs($user)->get('/rbac/dashboard');

    // Should be successful
    $response->assertStatus(200);
});
