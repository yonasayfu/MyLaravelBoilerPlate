# 🛡️ Laravel Authentication Features Guide

This guide explains how to clearly use and customize the built-in authentication features from the Laravel 12 Vue 3 Inertia starter kit in both development and production environments.

## 📋 Table of Contents
1. [Overview](#overview)
2. [Registration System](#registration-system)
3. [Login System](#login-system)
4. [Email Verification](#email-verification)
5. [Password Reset](#password-reset)
6. [Password Confirmation](#password-confirmation)
7. [Profile Management](#profile-management)
8. [Development vs Production Usage](#development-vs-production-usage)
9. [Customization Guide](#customization-guide)
10. [Testing Authentication Features](#testing-authentication-features)

## 📖 Overview

The Laravel 12 Vue 3 Inertia starter kit comes with a complete authentication system powered by Laravel Fortify. These features are ready to use out of the box and can be customized to fit your specific needs.

## 🔐 Registration System

### Routes
- **GET** `/register` - Display registration form
- **POST** `/register` - Process registration

### Controller
`App\Http\Controllers\Auth\RegisteredUserController`

### Features
- User registration with validation
- Automatic login after successful registration
- Redirect to intended page or dashboard

### Usage in Development
```bash
# Start development server
php artisan serve

# Visit registration page
http://localhost:8000/register
```

### Usage in Production
```bash
# Ensure environment variables are set in .env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Clear caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Customization Points
1. **Validation Rules**: Modify in `RegisteredUserController::store()`
2. **User Creation**: Customize in `RegisteredUserController::store()`
3. **Redirect Path**: Modify `redirectPath()` method
4. **Events**: Listen to `Registered` event for custom logic

## 🔐 Login System

### Routes
- **GET** `/login` - Display login form
- **POST** `/login` - Process login

### Controller
`App\Http\Controllers\Auth\AuthenticatedSessionController`

### Features
- Secure login with session management
- "Remember me" functionality
- Rate limiting protection
- Redirect to intended page

### Usage in Development
```bash
# Start development server
php artisan serve

# Visit login page
http://localhost:8000/login
```

### Usage in Production
```bash
# Ensure environment variables are set in .env
SESSION_DRIVER=database  # For production
SESSION_LIFETIME=120

# Run migrations for session table
php artisan session:table
php artisan migrate
```

### Customization Points
1. **Authentication Logic**: Modify in `AuthenticatedSessionController::store()`
2. **Rate Limiting**: Adjust in `EnsureLoginIsNotThrottled` middleware
3. **Redirect Path**: Modify `redirectTo()` method in `App\Providers\RouteServiceProvider`
4. **Events**: Listen to `Login` and `Failed` events

## ✉️ Email Verification

### Routes
- **GET** `/verify-email` - Prompt for email verification
- **GET** `/verify-email/{id}/{hash}` - Verify email address
- **POST** `/email/verification-notification` - Resend verification email

### Controllers
- `App\Http\Controllers\Auth\EmailVerificationPromptController`
- `App\Http\Controllers\Auth\VerifyEmailController`
- `App\Http\Controllers\Auth\EmailVerificationNotificationController`

### Features
- Email verification workflow
- Resend verification email
- Signed URL protection
- Rate limiting for resend requests

### Usage in Development
```bash
# Configure mail settings in .env for testing
MAIL_MAILER=log

# Start development server
php artisan serve

# Register a new user to trigger verification
```

### Usage in Production
```bash
# Configure real mail settings in .env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

# Ensure email verification is enabled in Fortify config
# config/fortify.php
'features' => [
    // ... other features
    Features::emailVerification(),
],
```

### Customization Points
1. **Verification Email Template**: Publish and modify `resources/views/emails/verify-email.blade.php`
2. **Redirect After Verification**: Modify in `VerifyEmailController`
3. **Expiration Time**: Adjust in `config/auth.php` under `verification.expire`
4. **Events**: Listen to `Verified` event

## 🔁 Password Reset

### Routes
- **GET** `/forgot-password` - Display password reset request form
- **POST** `/forgot-password` - Send reset link
- **GET** `/reset-password/{token}` - Display password reset form
- **POST** `/reset-password` - Process password reset

### Controllers
- `App\Http\Controllers\Auth\PasswordResetLinkController`
- `App\Http\Controllers\Auth\NewPasswordController`

### Features
- Password reset via email
- Token-based reset links
- Rate limiting protection
- Password confirmation validation

### Usage in Development
```bash
# Configure mail settings in .env for testing
MAIL_MAILER=log

# Start development server
php artisan serve

# Visit forgot password page
http://localhost:8000/forgot-password
```

### Usage in Production
```bash
# Configure real mail settings in .env (same as email verification)

# Ensure password reset is enabled in Fortify config
# config/fortify.php
'features' => [
    // ... other features
    Features::resetPasswords(),
],
```

### Customization Points
1. **Reset Email Template**: Publish and modify `resources/views/emails/reset-password.blade.php`
2. **Password Validation Rules**: Modify in `NewPasswordController::store()`
3. **Expiration Time**: Adjust in `config/auth.php` under `passwords.users.expire`
4. **Events**: Listen to `PasswordReset` event

## 🔐 Password Confirmation

### Routes
- **GET** `/confirm-password` - Display password confirmation form
- **POST** `/confirm-password` - Process password confirmation

### Controller
`App\Http\Controllers\Auth\ConfirmablePasswordController`

### Features
- Re-authentication for sensitive actions
- Rate limiting protection
- Redirect to intended page

### Usage in Development
```bash
# Start development server
php artisan serve

# Apply 'password.confirm' middleware to routes that require confirmation
Route::get('/sensitive-action', function () {
    // Sensitive action
})->middleware(['auth', 'password.confirm']);
```

### Usage in Production
```bash
# Ensure password confirmation timeout is set in .env
'password_timeout' => 10800, // 3 hours
```

### Customization Points
1. **Timeout Duration**: Adjust in `config/auth.php` under `password_timeout`
2. **Redirect Path**: Modify in `ConfirmablePasswordController`
3. **Validation Rules**: Customize in `ConfirmablePasswordController::store()`

## 👤 Profile Management

### Routes
- **GET** `/settings/profile` - Display profile edit form
- **PATCH** `/settings/profile` - Update profile information
- **DELETE** `/settings/profile` - Delete user account
- **GET** `/settings/password` - Display password edit form
- **PUT** `/settings/password` - Update password

### Controllers
- `App\Http\Controllers\Settings\ProfileController`
- `App\Http\Controllers\Settings\PasswordController`

### Features
- Profile updates (name, email)
- Password changes
- Account deletion
- Email verification for email changes

### Usage in Development
```bash
# Start development server
php artisan serve

# Visit profile settings page (requires authentication)
http://localhost:8000/settings/profile
```

### Usage in Production
```bash
# Ensure proper validation rules are in place
# Check App\Actions\Fortify\UpdateUserProfileInformation
# Check App\Actions\Fortify\UpdateUserPassword
```

### Customization Points
1. **Profile Validation**: Modify in `ProfileController::update()`
2. **Password Validation**: Modify in `PasswordController::update()`
3. **Account Deletion**: Customize in `ProfileController::destroy()`
4. **Events**: Listen to `ProfileInformationUpdated` and `PasswordUpdated` events

## 🧪 Development vs Production Usage

### Development Environment
1. **Mail Configuration**
   ```env
   MAIL_MAILER=log
   ```
   Emails will be logged to `storage/logs/laravel.log`

2. **Debugging**
   ```env
   APP_DEBUG=true
   ```
   Enables detailed error messages

3. **Database**
   ```env
   DB_CONNECTION=sqlite
   ```
   SQLite for simple local development

4. **Caching**
   ```bash
   # Disable caching for development
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

### Production Environment
1. **Mail Configuration**
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=your-smtp-host
   MAIL_PORT=587
   MAIL_USERNAME=your-username
   MAIL_PASSWORD=your-password
   MAIL_ENCRYPTION=tls
   ```

2. **Security**
   ```env
   APP_DEBUG=false
   APP_ENV=production
   ```

3. **Database**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

4. **Caching**
   ```bash
   # Enable caching for production
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

5. **Session Configuration**
   ```env
   SESSION_DRIVER=database
   SESSION_LIFETIME=120
   ```

6. **Rate Limiting**
   Adjust rate limiting in `App\Providers\RouteServiceProvider`

## 🎨 Customization Guide

### 1. Customizing Views
The starter kit uses Inertia.js with Vue 3 components. To customize the authentication views:

1. **Locate Vue Components**
   - Registration: `resources/js/pages/auth/Register.vue`
   - Login: `resources/js/pages/auth/Login.vue`
   - Password Reset: `resources/js/pages/auth/ForgotPassword.vue` and `ResetPassword.vue`
   - Profile: `resources/js/pages/settings/Profile.vue` and `Password.vue`

2. **Modify Components**
   ```vue
   <!-- Example: Customizing Register.vue -->
   <template>
     <div>
       <!-- Add custom fields -->
       <TextInput
         id="phone"
         type="tel"
         label="Phone Number"
         v-model="form.phone"
         :error="form.errors.phone"
         required
       />
     </div>
   </template>
   ```

3. **Update Controllers**
   ```php
   // In RegisteredUserController.php
   public function store(Request $request): RedirectResponse
   {
       $request->validate([
           'name' => ['required', 'string', 'max:255'],
           'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
           'password' => ['required', 'confirmed', Rules\Password::defaults()],
           'phone' => ['required', 'string', 'max:20'], // Add phone validation
       ]);

       $user = User::create([
           'name' => $request->name,
           'email' => $request->email,
           'password' => Hash::make($request->password),
           'phone' => $request->phone, // Add phone to user creation
       ]);

       event(new Registered($user));

       Auth::login($user);

       return redirect(RouteServiceProvider::HOME);
   }
   ```

### 2. Customizing Validation
1. **Registration Validation**
   ```php
   // In RegisteredUserController.php
   $request->validate([
       'name' => ['required', 'string', 'max:255'],
       'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
       'password' => ['required', 'confirmed', Rules\Password::defaults()],
       // Add custom fields here
   ]);
   ```

2. **Login Validation**
   ```php
   // In App\Actions\Fortify\AttemptToAuthenticate
   // Or customize in AuthenticatedSessionController
   ```

3. **Password Reset Validation**
   ```php
   // In NewPasswordController.php
   $request->validate([
       'token' => ['required'],
       'email' => ['required', 'email'],
       'password' => ['required', 'confirmed', Rules\Password::defaults()],
   ]);
   ```

### 3. Customizing Redirects
1. **After Login/Registration**
   ```php
   // In App\Providers\RouteServiceProvider
   public const HOME = '/dashboard';
   
   // Or customize in controllers
   protected function redirectTo(): string
   {
       return route('dashboard');
   }
   ```

2. **After Email Verification**
   ```php
   // In VerifyEmailController.php
   return redirect()->intended(RouteServiceProvider::HOME.'?verified=1');
   ```

### 4. Adding Custom Fields to User Registration
1. **Update User Migration**
   ```php
   // In a new migration file
   Schema::table('users', function (Blueprint $table) {
       $table->string('phone')->nullable();
       $table->string('company')->nullable();
   });
   ```

2. **Update User Model**
   ```php
   // In app/Models/User.php
   protected $fillable = [
       'name',
       'email',
       'password',
       'phone',  // Add new fields
       'company',
   ];
   ```

3. **Update Registration Form**
   ```vue
   <!-- In Register.vue -->
   <TextInput
     id="phone"
     type="tel"
     label="Phone Number"
     v-model="form.phone"
     :error="form.errors.phone"
   />
   ```

4. **Update Controller**
   ```php
   // In RegisteredUserController.php
   $user = User::create([
       'name' => $request->name,
       'email' => $request->email,
       'password' => Hash::make($request->password),
       'phone' => $request->phone, // Add new field
   ]);
   ```

## 🧪 Testing Authentication Features

### 1. Feature Tests
```php
// tests/Feature/Auth/RegistrationTest.php
<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;

uses(TestCase::class);

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    Event::fake();

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
    
    Event::assertDispatched(Registered::class);
});
```

### 2. Browser Tests
```php
// tests/Browser/Auth/RegistrationTest.php
<?php

use App\Models\User;
use Laravel\Dusk\Browser;

uses(Laravel\Dusk\TestCase::class);

test('users can register', function () {
    $this->browse(function (Browser $browser) {
        $browser->visit('/register')
                ->type('name', 'Test User')
                ->type('email', 'test@example.com')
                ->type('password', 'password')
                ->type('password_confirmation', 'password')
                ->click('@register-button')
                ->assertPathIs('/dashboard');
    });
});
```

### 3. Unit Tests for Custom Logic
```php
// tests/Unit/Actions/Fortify/CreateNewUserTest.php
<?php

use App\Actions\Fortify\CreateNewUser;
use App\Models\User;

uses(TestCase::class);

test('it creates a new user', function () {
    $action = new CreateNewUser();
    
    $user = $action->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);
    
    expect($user)->toBeInstanceOf(User::class);
    expect($user->name)->toBe('Test User');
    expect(Hash::check('password', $user->password))->toBeTrue();
});
```

## 📚 Best Practices

### 1. Security
- Always validate and sanitize user input
- Use Laravel's built-in validation rules
- Implement proper rate limiting
- Use HTTPS in production
- Store passwords securely with bcrypt

### 2. Performance
- Cache frequently accessed data
- Use database indexing for user queries
- Implement proper session management
- Optimize database queries

### 3. User Experience
- Provide clear error messages
- Implement proper loading states
- Use consistent UI patterns
- Provide helpful tooltips and guidance

### 4. Maintenance
- Keep Laravel and packages updated
- Monitor authentication logs
- Regularly review security practices
- Document customizations

## 🚀 Deployment Checklist

### Before Production Deployment
- [ ] Configure production mail settings
- [ ] Set APP_ENV=production and APP_DEBUG=false
- [ ] Configure database connection
- [ ] Set up HTTPS
- [ ] Configure session driver (database recommended)
- [ ] Set up proper caching
- [ ] Test all authentication flows
- [ ] Verify email deliverability
- [ ] Review security headers
- [ ] Set up monitoring and logging

### Post Deployment
- [ ] Monitor authentication logs
- [ ] Test all flows with real users
- [ ] Set up alerts for failed login attempts
- [ ] Review and optimize performance

This guide provides a comprehensive overview of how to use and customize the built-in authentication features in both development and production environments.






To give an accurate intro and plan tailored to your project, I’ll quickly open the guides you referenced to understand your setup and conventions, starting with `AUTHENTICATION_FEATURES_GUIDE.md` and `Qoder.md` (and the related docs you have open). Then I’ll summarize how Laravel 12 starter kits (Vue 3 + Inertia) are expected to handle password reset/forgot/email verification, and propose a concise plan for both dev-time and real-time.

I’ll read these files now.

# Intro: How the new Laravel 12 + Vue 3 + Inertia starter handles Auth

Based on your [AUTHENTICATION_FEATURES_GUIDE.md](cci:7://file:///Users/yonassayfu/VSProject/BaseBoilerPlate/laravelBoilerPlate/AUTHENTICATION_FEATURES_GUIDE.md:0:0-0:0), the starter kit builds on Laravel Fortify and ships the full Auth surface:

- __Email Verification__: routes `GET /verify-email`, `GET /verify-email/{id}/{hash}`, `POST /email/verification-notification`, signed URLs, rate limiting.
- __Password Forgot/Reset__: routes `GET/POST /forgot-password`, `GET /reset-password/{token}`, `POST /reset-password`, token-based reset links, rate limiting.
- __Password Confirmation__: `GET/POST /confirm-password` for re-auth on sensitive actions.

Inertia + Vue 3 provide the pages, while controllers (e.g., `PasswordResetLinkController`, `NewPasswordController`, `EmailVerification*Controller`) handle server-side workflows. Fortify features must be enabled in `config/fortify.php` as shown in the guide.

Dev vs Prod:
- __Dev__: `MAIL_MAILER=log` to capture emails in `storage/logs/laravel.log`. No SMTP required.
- __Prod__: `MAIL_MAILER=smtp` with correct SMTP settings, valid `MAIL_FROM_*`, proper `APP_URL`, and caches warmed.

References from your repo:
- See [AUTHENTICATION_FEATURES_GUIDE.md](cci:7://file:///Users/yonassayfu/VSProject/BaseBoilerPlate/laravelBoilerPlate/AUTHENTICATION_FEATURES_GUIDE.md:0:0-0:0) sections: Email Verification and Password Reset.
- The guide lists expected routes/controllers and the Fortify features to enable.

# Likely cause of your “forgot password” not working

Common culprits when `.env` looks “correct” but no email/reset flow works:

- __MAIL config issues__:
  - Missing/invalid `MAIL_FROM_ADDRESS` or domain mismatch with SMTP provider.
  - Wrong `MAIL_PORT`/`MAIL_ENCRYPTION` (587 + tls vs 465 + ssl).
  - Provider requires application-specific password or verified sender/domain.
- __APP_URL mismatch__: Signed URL or notification links break if `APP_URL` is wrong (e.g., http vs https, different host).
- __Fortify feature not enabled__: `Features::resetPasswords()` missing in `config/fortify.php`.
- __Tokens table not migrated__: `password_reset_tokens` migration not run.
- __Queues__: If notifications are queued (or you customized them to queue), no worker = no emails sent.
- __Rate limit__: Multiple attempts can be throttled.
- __Caching__: Stale cached config using old .env values (`php artisan config:clear` in dev; re-cache correctly in prod).
- __Dev expectation__: Using `MAIL_MAILER=log` but checking inbox instead of logs.

# Minimal triage checklist (safe to run locally)

- __Verify routes__: `php artisan route:list | grep -E 'forgot|reset|verify-email'`
- __Check mail config in runtime__: `php artisan tinker` then `config('mail')`, `config('app.url')`
- __Check logs__: `storage/logs/laravel.log` for mail or exception messages.
- __Confirm Fortify features__: `config('fortify.features')` includes `resetPasswords` and `emailVerification`.
- __Confirm migrations__: `php artisan migrate:status` for `password_reset_tokens`.
- __Clear caches__: `php artisan config:clear && php artisan route:clear && php artisan view:clear`
- __Try dev mailer__: set `MAIL_MAILER=log` and trigger forgot password; inspect log for reset link.

If you want, I can run safe read-only checks (route list, migrate:status) once you confirm.

# Plan: Development-time vs Real-time for the three Auth flows

## Development-time (local and staging)

- __Password Forgot/Reset__
  - Use `MAIL_MAILER=log` (or Mailtrap/Mailhog) to capture links.
  - Ensure tokens table exists and Fortify reset feature enabled.
  - Add feature tests for:
    - Request reset link sends notification.
    - Reset works with valid token; rejects invalid/expired.
    - Rate limiting behavior.
- __Email Verification__
  - Enable `Features::emailVerification()`.
  - Use log mailer or Mailtrap to open the verify link.
  - Feature tests for:
    - Verification prompt for unverified users.
    - Signed link works once.
    - Resend link throttling.
- __Password Confirmation__
  - Apply `password.confirm` middleware to a sample protected route.
  - Test timeout behavior.

Tooling:
- Keep `APP_DEBUG=true`, clear caches between changes.
- Add Pest feature tests under `tests/Feature/Auth/*` as your guide demonstrates.

## Real-time (production)

- __Mail deliverability__
  - Configure SMTP properly: `MAIL_HOST/PORT/USER/PASSWORD/ENCRYPTION`, set `MAIL_FROM_*`.
  - Verify sender/domain with your provider (Mailgun/SES/etc.).
  - Set `APP_URL=https://yourdomain.com`.
  - If notifications are queued, run a queue worker (supervisor/systemd).
- __Security & reliability__
  - `APP_ENV=production`, `APP_DEBUG=false`.
  - `SESSION_DRIVER=database`, run `php artisan session:table && migrate`.
  - Cache config/routes/views.
  - Monitor logs and failed jobs.
- __User experience__
  - Friendly messages, resend throttling, clear errors on token expiry.
  - Test links from actual inbox on production domain.

# Focused next steps to fix your current Forgot Password issue

- __Confirm runtime config__:
  - Share your current non-secret `.env` mail section (redact username/password), and `APP_URL`, `APP_ENV`, `APP_DEBUG`.
  - Tell me if you expect to receive real emails now, or if you’re okay to switch to `MAIL_MAILER=log` to validate flow first.
- __Run quick diagnostics__ (I can do once you approve):
  - route:list grep for forgot/reset/verify.
  - migrate:status for `password_reset_tokens`.
  - config:show mail and app.url via Tinker.
- __Try a controlled test__:
  - Set `MAIL_MAILER=log` and trigger the forgot password form.
  - Check `storage/logs/laravel.log` for the reset link.
  - If it appears, the flow is fine—then we fix SMTP deliverability (DNS/ports/from-address).

# Lightweight TODO plan

- __Read current Auth implementation and Fortify config__.
- __Verify routes and migrations for reset/verify__.
- __Validate mail config (dev vs prod) and APP_URL__.
- __Reproduce forgot-password, inspect logs__.
- __Add/enable feature tests for reset/verify flows__.
- __Document the verified flow and prod checklist__.

Would you like me to:
1) Run the safe diagnostics now, or
2) Review your `.env` (redact secrets) and guide you step-by-step?

Please also share:
- The exact behavior/error when you submit the forgot password form (redirect? flash message? error in logs?).
- Whether you’re testing locally or on a server, and which SMTP provider you’re using. 

Summary: I reviewed your guides and outlined how the starter kit handles email verification and password reset with Fortify, provided a concise dev vs prod plan, a triage checklist for your current forgot-password issue, and proposed next steps.