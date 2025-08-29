# Running Services

## Main Application
- **Service**: Laravel Application
- **URL**: http://127.0.0.1:8000
- **Description**: Main application with login and forgot password functionality

## Mailcatcher Application
- **Service**: Mailcatcher (Standalone Laravel App)
- **URL**: http://127.0.0.1:8001
- **Description**: Web interface to view captured emails

## SMTP Server
- **Service**: Mailcatcher SMTP Server
- **Port**: 1025
- **Description**: SMTP server that captures outgoing emails

## Frontend Development Server
- **Service**: Vite Development Server
- **URL**: http://localhost:5173
- **Description**: Serves frontend assets during development

## Environment Configuration
- **APP_URL**: http://127.0.0.1:8000
- **MAIL_MAILER**: smtp
- **MAIL_HOST**: 127.0.0.1
- **MAIL_PORT**: 1025
- **SANCTUM_STATEFUL_DOMAINS**: localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,127.0.0.1:8001,::1,127.0.0.1:5173,[::1]:5173

## Testing the Forgot Password Flow
1. Visit http://127.0.0.1:8000/login
2. Click "Forgot password?" link
3. Enter your email address
4. Click "Send Reset Link"
5. Check captured email at http://127.0.0.1:8001
6. Click the reset link in the email
7. Enter and confirm your new password
8. Click "Reset Password"
9. You should be redirected to the login page with a success message

## Commands to Restart Services
```bash
# Kill all services
pkill -f "php artisan"
pkill -f "vite"

# Start main application
cd /Users/yonassayfu/VSProject/BaseBoilerPlate/laravelBoilerPlate
php artisan serve --port=8000

# Start mailcatcher application (in new terminal)
cd /Users/yonassayfu/VSProject/BaseBoilerPlate/laravelBoilerPlate/mailcatcher
php artisan serve --port=8001

# Start SMTP server (in new terminal)
cd /Users/yonassayfu/VSProject/BaseBoilerPlate/laravelBoilerPlate/mailcatcher
php artisan mail:catch --port=1025

# Start Vite development server (in new terminal)
cd /Users/yonassayfu/VSProject/BaseBoilerPlate/laravelBoilerPlate
npm run dev
```