# 🚀 Laravel Clean Architecture Boilerplate

A comprehensive, production-ready Laravel boilerplate following Clean Architecture principles with modern frontend technologies and mobile-first API design.

## 🌟 Features

### 🏗️ **Architecture**
- ✅ **Clean Architecture** with proper layer separation
- ✅ **Performance Optimized** base classes with caching
- ✅ **DTO Pattern** for type safety and data validation
- ✅ **Service Layer** for business logic encapsulation
- ✅ **Event-Driven** architecture with listeners

### 🔐 **Authentication & Authorization**
- ✅ **Laravel Sanctum** for API authentication
- ✅ **Spatie Permission** for role-based access control
- ✅ **Multi-device** login support
- ✅ **Token management** with refresh capabilities

### 👥 **User Management**
- ✅ **Complete CRUD** operations
- ✅ **Role assignment** and permission management
- ✅ **Profile management** with photo uploads
- ✅ **Password security** with strength validation

### 💼 **Staff Management**
- ✅ **Staff profiles** with departments and positions
- ✅ **File uploads** for staff photos
- ✅ **Availability tracking** and scheduling
- ✅ **Performance metrics** and reporting

### 📱 **Frontend Stack**
- ✅ **Vue 3** with Composition API
- ✅ **TypeScript** for type safety
- ✅ **Inertia.js** for SPA-like experience
- ✅ **Tailwind CSS** for styling
- ✅ **Vite** for modern build tooling

### 🌐 **API Layer**
- ✅ **RESTful API** with versioning (v1)
- ✅ **Comprehensive endpoints** for mobile development
- ✅ **Consistent responses** with DTO patterns
- ✅ **Rate limiting** and security headers
- ✅ **OpenAPI documentation** ready

### ⚡ **Performance Features**
- ✅ **Multi-layer caching** (Redis, Query, Response)
- ✅ **Database optimization** with strategic indexes
- ✅ **Query optimization** to prevent N+1 problems
- ✅ **Frontend optimization** with code splitting
- ✅ **Memory management** with object pooling

### 🛠️ **Developer Experience**
- ✅ **Comprehensive testing** framework
- ✅ **Docker** support for containerization
- ✅ **CI/CD** pipeline ready
- ✅ **Code quality** tools (ESLint, Prettier, Pint)
- ✅ **API documentation** generation

### 🚀 **Enhanced Features Added**
- ✅ **Advanced RBAC System** with comprehensive role and permission management
- ✅ **Messaging System** with file attachments, read receipts, and conversation management
- ✅ **Notification System** with multi-channel support, preferences, and statistics
- ✅ **Global Search** with entity-wide search, suggestions, and advanced filtering
- ✅ **Enhanced API** with comprehensive endpoints, resources, and authentication
- ✅ **File Management** with secure uploads, validation, and cloud storage support
- ✅ **Performance Optimization** with multi-layer caching and query optimization
- ✅ **Configuration Management** with feature toggles and environment settings

## 📦 What's Included

### Backend Components
```
app/
├── DTOs/                          # Data Transfer Objects
│   ├── BaseDTO.php               # Base DTO with object pooling
│   ├── ResponseDTO.php           # Standardized API responses
│   ├── CreateUserDTO.php         # User creation DTO
│   ├── UpdateUserDTO.php         # User update DTO
│   ├── UserResponseDTO.php       # User response formatting
│   ├── CreateStaffDTO.php        # Staff creation DTO
│   ├── UpdateStaffDTO.php        # Staff update DTO
│   └── StaffResponseDTO.php      # Staff response formatting
├── Services/                      # Business Logic Layer
│   ├── BaseService.php           # Base service functionality
│   ├── PerformanceOptimizedBaseService.php  # Enhanced performance
│   ├── UserService.php           # User business logic
│   ├── StaffService.php          # Staff business logic
│   └── Validation/               # Validation layer
│       ├── Rules/                # Validation rules
│       └── CustomRules/          # Custom validation rules
├── Http/Controllers/             # Request Handling
│   ├── Base/                     # Base controllers
│   │   ├── BaseController.php    # Web base controller
│   │   └── OptimizedBaseController.php  # Performance optimized
│   ├── Admin/                    # Admin controllers
│   └── Api/V1/                   # Versioned API controllers
│       ├── BaseApiController.php # API base controller
│       ├── AuthController.php    # Authentication endpoints
│       ├── UserController.php    # User API endpoints
│       └── StaffController.php   # Staff API endpoints
└── Models/                       # Data Models
    ├── User.php                  # User model with relationships
    └── Staff.php                 # Staff model with relationships
```

### Frontend Components
```
resources/js/
├── components/                   # Vue Components
│   ├── ui/                      # UI framework components
│   ├── AppSidebar.vue           # Navigation sidebar
│   ├── AppHeader.vue            # Application header
│   └── ...                     # Other shared components
├── pages/                       # Page Components
│   ├── Admin/                   # Admin interface pages
│   │   ├── Users/              # User management pages
│   │   └── Staff/              # Staff management pages
│   └── auth/                   # Authentication pages
├── composables/                 # Vue composables
├── types/                      # TypeScript definitions
└── layouts/                    # Page layouts
```

## 🚀 Quick Start

### Prerequisites
- PHP 8.2+
- Node.js 20.x
- Composer 2.5+
- PostgreSQL or MySQL
- Redis (optional, for caching)

### New Commands Added
```bash
# Initialize RBAC system with default roles and permissions
php artisan rbac:initialize

# Force initialization (overwrites existing roles)
php artisan rbac:initialize --force
```

### Installation

1. **Clone the repository**
```bash
git clone <your-repo-url> my-new-project
cd my-new-project
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install Node.js dependencies**
```bash
npm install
```

4. **Environment setup**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configure your database in `.env`**
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. **Configure Redis (optional but recommended)**
```env
CACHE_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

7. **Run migrations and seeders**
```bash
php artisan migrate
php artisan db:seed
```

8. **Build frontend assets**
```bash
npm run build
```

9. **Start the development server**
```bash
composer run dev
# This starts: Laravel server + Vite + Queue worker
```

## 📱 API Documentation

### Authentication Endpoints
```http
POST /api/v1/auth/login
POST /api/v1/auth/register
POST /api/v1/auth/logout
POST /api/v1/auth/refresh
GET  /api/v1/auth/tokens
```

### User Management Endpoints
```http
GET    /api/v1/users/profile
PUT    /api/v1/users/profile
POST   /api/v1/users/change-password
GET    /api/v1/users/permissions
GET    /api/v1/users                 # Admin only
POST   /api/v1/users                 # Admin only
GET    /api/v1/users/{id}            # Admin only
PUT    /api/v1/users/{id}            # Admin only
DELETE /api/v1/users/{id}            # Admin only
```

### Staff Management Endpoints
```http
GET    /api/v1/staff/dropdown
GET    /api/v1/staff/available
GET    /api/v1/staff/search
GET    /api/v1/staff/stats           # Admin only
GET    /api/v1/staff                 # Admin only
POST   /api/v1/staff                 # Admin only
GET    /api/v1/staff/{id}            # Admin only
PUT    /api/v1/staff/{id}            # Admin only
DELETE /api/v1/staff/{id}            # Admin only
```

### Response Format
All API responses follow this consistent format:
```json
{
  "success": true,
  "message": "Operation successful",
  "data": { ... },
  "meta": {
    "pagination": { ... }
  }
}
```

## 🔧 Configuration

### Boilerplate Configuration
Customize the boilerplate through `config/boilerplate.php`:

```php
return [
    'features' => [
        'authentication' => true,
        'user_management' => true,
        'staff_management' => true,
        'messaging' => true,
        'api_endpoints' => true,
        // ... more features
    ],
    'modules' => [
        // Configure enabled modules
    ],
    'performance' => [
        'caching' => [
            'ttl' => [
                'default' => 600,
                'long' => 3600,
                'short' => 300,
            ],
        ],
    ],
    // ... more configuration
];
```

## 🧪 Testing

### Run Tests
```bash
# Run all tests
composer test

# Run specific test suite
php artisan test --group=users
php artisan test --group=api

# Run with coverage
php artisan test --coverage
```

### Test Structure
```
tests/
├── Feature/                     # Feature tests
│   ├── Auth/                   # Authentication tests
│   ├── Users/                  # User management tests
│   ├── Staff/                  # Staff management tests
│   └── Api/                    # API endpoint tests
└── Unit/                       # Unit tests
    ├── Services/               # Service layer tests
    ├── DTOs/                   # DTO tests
    └── Models/                 # Model tests
```

## 🐳 Docker Support

### Development with Docker
```bash
# Build and start containers
docker-compose up -d

# Run commands in container
docker-compose exec app php artisan migrate
docker-compose exec app composer install
```

### Production Deployment
```bash
# Build production image
docker build -t my-app .

# Run production container
docker run -d -p 80:80 my-app
```

## 📊 Performance Monitoring

### Built-in Performance Features
- **Query optimization** with automatic N+1 detection
- **Multi-layer caching** with Redis integration
- **Response compression** for faster load times
- **Asset optimization** with Vite chunking
- **Memory management** with object pooling

### Monitoring Tools
- Laravel Debugbar (development)
- Performance test routes (`/performance-test`)
- Cache hit/miss analytics
- Custom benchmarking tools

## 🔒 Security Features

### Built-in Security
- **CSRF protection** on all forms
- **XSS protection** with input sanitization
- **SQL injection prevention** with Eloquent ORM
- **Rate limiting** on API endpoints
- **Password strength validation**
- **Role-based access control**

### Security Headers
```php
// Automatically applied security headers
X-Content-Type-Options: nosniff
X-Frame-Options: DENY
X-XSS-Protection: 1; mode=block
Strict-Transport-Security: max-age=31536000
```

## 🚀 Deployment

### Production Checklist
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure database credentials
- [ ] Set up Redis for caching
- [ ] Configure mail settings
- [ ] Set up SSL certificate
- [ ] Configure backup strategy
- [ ] Set up monitoring

### Optimization Commands
```bash
# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Build optimized assets
npm run build

# Clear development caches
php artisan optimize:clear
```

## 🔄 Development Workflow

### Adding New Modules

1. **Create Model**
```bash
php artisan make:model NewModule -m
```

2. **Create Service**
```php
class NewModuleService extends PerformanceOptimizedBaseService
{
    // Implement business logic
}
```

3. **Create DTOs**
```php
class CreateNewModuleDTO extends BaseDTO { }
class UpdateNewModuleDTO extends BaseDTO { }
class NewModuleResponseDTO extends BaseDTO { }
```

4. **Create Controller**
```php
class NewModuleController extends OptimizedBaseController
{
    // Implement CRUD operations
}
```

5. **Create API Controller**
```php
class NewModuleController extends BaseApiController
{
    // Implement API endpoints
}
```

6. **Add Routes**
```php
// Web routes
Route::resource('new-modules', NewModuleController::class);

// API routes
Route::apiResource('new-modules', Api\V1\NewModuleController::class);
```

### Code Quality Standards
- Follow PSR-12 coding standards
- Use Laravel Pint for PHP formatting
- Use ESLint and Prettier for JavaScript/TypeScript
- Maintain test coverage above 80%
- Document all public methods

## 📚 Additional Resources

- [Clean Architecture Guide](./RCleanArchitectureGuide.md)
- [API Development Guide](./MD/laterMD/API_IMPLEMENTATION_GUIDE.md)
- [Performance Optimization](./MD/laterMD/CLEAN_ARCHITECTURE_OPTIMIZATION_COMPLETE.md)
- [Project Roadmap](./RPROJECT_ROADMAP_WORKFLOW.md)

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- Laravel framework and community
- Vue.js and TypeScript communities
- Spatie packages for Laravel
- Inertia.js for seamless SPA experience
- All contributors and maintainers

---

**Ready to build something amazing?** 🚀

This boilerplate provides everything you need to start a professional Laravel application with modern development practices, clean architecture, and production-ready features.