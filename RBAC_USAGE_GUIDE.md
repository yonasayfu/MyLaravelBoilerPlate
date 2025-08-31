# 🛡️ RBAC Usage Guide

This guide explains how to use the Role-Based Access Control (RBAC) system implemented in this Laravel boilerplate.

## 📋 Overview

The RBAC system uses the Spatie Laravel Permission package to manage roles and permissions. It provides fine-grained access control to different parts of the application based on user roles.

## 👥 Available Roles

1. **Super Admin** - Full access to all system features
2. **Admin** - Can manage users and staff, profile management
3. **COO** - Can manage staff and reports
4. **CEO** - Can view reports and analytics
5. **Staff** - Can manage their own profile and assigned tasks
6. **Guest** - Limited access to public content only

## 🔐 Available Permissions

### User Management
- `view-users` - View user list
- `create-users` - Create new users
- `update-users` - Update existing users
- `delete-users` - Delete users

### Staff Management
- `view-staff` - View staff list
- `create-staff` - Create new staff members
- `update-staff` - Update existing staff members
- `delete-staff` - Delete staff members

### Role Management
- `assign-roles` - Assign roles to users
- `view-roles` - View role details

### Profile Management
- `view-profile` - View own profile
- `update-profile` - Update own profile
- `change-password` - Change own password

### Reports and Analytics
- `view-reports` - View reports dashboard
- `export-reports` - Export reports

### System Management
- `manage-settings` - Manage system settings
- `view-logs` - View system logs

### Public Access
- `view-public-content` - View publicly accessible content

## 🛠️ Managing Roles and Permissions

### Seeding Roles and Permissions

Roles and permissions are seeded using the `RolePermissionSeeder`:

```bash
php artisan db:seed --class=RolePermissionSeeder
```

### Assigning Roles to Users

You can assign roles to users using the provided command:

```bash
php artisan user:assign-role {email} {role}
```

Example:
```bash
php artisan user:assign-role admin@example.com admin
```

### Programmatically Assigning Roles

In your code, you can assign roles to users like this:

```php
use App\Models\User;

$user = User::find(1);
$user->assignRole('admin');
```

### Checking Permissions

You can check if a user has a specific permission:

```php
if ($user->can('view-users')) {
    // User can view users
}
```

### Checking Roles

You can check if a user has a specific role:

```php
if ($user->hasRole('admin')) {
    // User is an admin
}
```

## 🔒 Implementing Access Control

### In Controllers

Use Laravel's built-in authorization middleware:

```php
// In routes/web.php
Route::get('/admin/users', [UserController::class, 'index'])
    ->middleware('can:view-users');
```

Or in controller methods:

```php
public function index(Request $request)
{
    $this->authorize('view-users');
    
    // Controller logic here
}
```

### In Vue Components

You can pass user permissions to Vue components and check them in the frontend:

```vue
<template>
    <div v-if="$page.props.auth.user.can.view_users">
        <!-- Show user management interface -->
    </div>
</template>
```

### Creating Custom Permissions

To create new permissions, add them to the `RolePermissionSeeder` and run the seeder:

```php
// In database/seeders/RolePermissionSeeder.php
$permissions = [
    // ... existing permissions
    'new-permission-name'
];
```

Then run:
```bash
php artisan db:seed --class=RolePermissionSeeder
```

## 🧪 Testing RBAC

The RBAC system includes comprehensive tests in `tests/Feature/RbacTest.php`. You can run these tests with:

```bash
php artisan test --filter=RbacTest
```

## 📊 RBAC Dashboard

A demonstration dashboard is available at `/rbac/dashboard` that shows:
- Current user's role
- Available roles in the system
- Permissions assigned to each role

This dashboard is protected and only accessible to users with the `view-reports` permission.