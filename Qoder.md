# 🤖 Qoder Agent Interaction Template

This template defines how the Qoder AI agent should interact with the user for each new module or request, following a learning-focused approach.

## 📚 Key Reference Documents

Before implementing any new module or feature, refer to these essential documents:

1. **[README.md](file:///Users/yonassayfu/VSProject/BaseBoilerPlate/laravelBoilerPlate/README.md)** - Project overview and getting started guide
2. **[ROADMAP_AND_PROGRESS.md](file:///Users/yonassayfu/VSProject/BaseBoilerPlate/laravelBoilerPlate/ROADMAP_AND_PROGRESS.md)** - Implementation roadmap and current progress tracking
3. **[ARCHITECTURE_AND_COMPONENTS.md](file:///Users/yonassayfu/VSProject/BaseBoilerPlate/laravelBoilerPlate/ARCHITECTURE_AND_COMPONENTS.md)** - Architecture patterns, components, and data flow
4. **[PHASE_TRACKING.md](file:///Users/yonassayfu/VSProject/BaseBoilerPlate/laravelBoilerPlate/PHASE_TRACKING.md)** - Tracking of files created in each phase
5. **[AUTHENTICATION_FEATURES_GUIDE.md](file:///Users/yonassayfu/VSProject/BaseBoilerPlate/laravelBoilerPlate/AUTHENTICATION_FEATURES_GUIDE.md)** - Comprehensive guide for using authentication features
6. **[EMAIL_TESTING_GUIDE.md](file:///Users/yonassayfu/VSProject/BaseBoilerPlate/laravelBoilerPlate/EMAIL_TESTING_GUIDE.md)** - Guide for testing email features with real email addresses

These documents provide the foundation for all implementation decisions and should be referenced to maintain consistency.

## 🎯 Interaction Pattern

### 1. Request Analysis
- Understand the user's specific request
- Identify the module or feature to be implemented
- Determine the phase in the roadmap
- Review relevant sections in reference documents

### 2. Learning-Focused Approach
- Explain the purpose and benefits of each component
- Show integration points with existing code
- Provide best practices and design patterns
- Reference architecture documentation for consistency
- No automated code generation without explicit user request

### 3. Step-by-Step Guidance
- Break down implementation into logical steps
- Explain each file's role in the architecture
- Provide code structure and examples
- Wait for user confirmation before proceeding
- Update tracking documents after implementation

## 📋 Template for New Modules/Features

### Module: [Module Name]

#### Purpose
Explain what this module does and why it's needed.

#### Integration Points
- How it connects to existing components
- Which services it uses
- Which controllers interact with it
- Database relationships

#### Implementation Steps
1. **Model Creation**
   - Fields and relationships
   - Methods and scopes
   - Integration with existing models

2. **DTO Creation**
   - Validation rules
   - Data transformation logic
   - Integration with BaseDTO

3. **Service Layer**
   - Business logic implementation
   - Caching strategies
   - Integration with BaseService/PerformanceOptimizedBaseService

4. **Controllers**
   - Web controller (AdminController)
   - API controller (Api/V1/)
   - Integration with OptimizedBaseController/BaseApiController

5. **Database**
   - Migrations
   - Factories for testing
   - Seeders for sample data

6. **MCP Tools (if applicable)**
   - Custom MCP tools for the new module
   - Integration with existing MCP framework
   - Documentation of new tools

7. **Testing**
   - Feature tests
   - Unit tests for services
   - API tests
   - MCP integration tests


## 🧪 Mandatory Testing Requirements

### For Each New Module or Feature

1. **Create Corresponding Test Files**
   - For each new controller, create a feature test
   - For each new service, create a unit test
   - For each new model, create a model test
   - For each new DTO, create a unit test
   - For each new MCP tool, create integration tests

2. **Test File Naming Convention**
   - Controllers: `tests/Feature/[ModuleName]Test.php`
   - Services: `tests/Unit/Services/[ServiceName]Test.php`
   - Models: `tests/Unit/Models/[ModelName]Test.php`
   - DTOs: `tests/Unit/DTOs/[DtoName]Test.php`
   - MCP Tools: `tests/Feature/MCP/[ToolName]Test.php`

3. **Example Test Creation Flow**
   - When creating `app/Models/Staff.php`, immediately create `tests/Unit/Models/StaffTest.php`
   - When creating `app/Http/Controllers/Admin/StaffController.php`, immediately create `tests/Feature/StaffTest.php`
   - When creating `app/Services/StaffService.php`, immediately create `tests/Unit/Services/StaffServiceTest.php`
   - When creating MCP tools, immediately create `tests/Feature/MCP/StaffMcpToolTest.php`

5. **PestPHP Testing Patterns**
   - Use `it()` for individual test cases
   - Use `describe()` to group related tests
   - Use `beforeEach()` for test setup
   - Use appropriate assertions for the data type
   - Include MCP integration tests for new tools

### Example Test Structure for Controllers

```php
<?php

use Tests\TestCase;

uses(TestCase::class);

it('can list staff members', function () {
    // Arrange
    // Create test data
    
    // Act
    $response = $this->get('/admin/staff');
    
    // Assert
    $response->assertStatus(200);
    $response->assertViewHas('staff');
});

it('can create a new staff member', function () {
    // Arrange
    $data = [
        // Valid staff data
    ];
    
    // Act
    $response = $this->post('/admin/staff', $data);
    
    // Assert
    $response->assertStatus(302);
    $this->assertDatabaseHas('staff', [
        // Expected data
    ]);
});
```

### Example Test Structure for Services

```php
<?php

use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    // Setup test data
});

it('can create a staff member', function () {
    // Arrange
    $data = [
        // Valid staff data
    ];
    
    // Act
    $result = $this->staffService->create($data);
    
    // Assert
    expect($result)->toBeInstanceOf(Staff::class);
    $this->assertDatabaseHas('staff', [
        // Expected data
    ]);
});

it('throws validation exception for invalid data', function () {
    // Arrange
    $invalidData = [
        // Invalid staff data
    ];
    
    // Act & Assert
    expect(fn() => $this->staffService->create($invalidData))
        ->toThrow(ValidationException::class);
});
```

## 📚 Documentation Updates

### Files to Update
- [PHASE_TRACKING.md](file:///Users/yonassayfu/VSProject/BaseBoilerPlate/laravelBoilerPlate/PHASE_TRACKING.md) - Track implementation progress
- [ARCHITECTURE_AND_COMPONENTS.md](file:///Users/yonassayfu/VSProject/BaseBoilerPlate/laravelBoilerPlate/ARCHITECTURE_AND_COMPONENTS.md) - Update architecture documentation if needed
- [ROADMAP_AND_PROGRESS.md](file:///Users/yonassayfu/VSProject/BaseBoilerPlate/laravelBoilerPlate/ROADMAP_AND_PROGRESS.md) - Update progress tracking




## 🌿 Git Workflow Guidelines

### For Each New Module Implementation

1. **Create a New Branch**
   ```bash
   git checkout -b feature/module-name
   ```
   - Use descriptive branch names (e.g., `feature/staff-management`, `feature/messaging-system`)
   - Branch from the latest `main` branch

2. **Commit Frequently**
   - Make at least one commit for every 10 code changes
   - Write clear, descriptive commit messages
   - Follow conventional commit format:
     ```
     feat: add staff model with user relationship
     fix: resolve validation issue in staff service
     test: add unit tests for staff controller
     docs: update architecture documentation
     ```

3. **Commit Message Structure**
   - Start with type: `feat:`, `fix:`, `test:`, `docs:`, `refactor:`, `style:`, `chore:`
   - Use present tense ("add" not "added")
   - Capitalize first letter after colon
   - Keep first line under 72 characters

4. **Example Commit Flow**
   ```bash
   # After creating the model
   git add app/Models/Staff.php
   git commit -m "feat: add staff model with user relationship"
   
   # After creating DTOs
   git add app/DTOs/CreateStaffDTO.php app/DTOs/UpdateStaffDTO.php
   git commit -m "feat: create staff DTOs for data validation"
   
   # After creating service
   git add app/Services/StaffService.php
   git commit -m "feat: implement staff service with caching"
   
   # After creating tests
   git add tests/Unit/Models/StaffTest.php tests/Feature/StaffTest.php tests/Unit/Services/StaffServiceTest.php
   git commit -m "test: add comprehensive tests for staff module"
   ```

5. **Before Pushing to Origin**
   - Run tests to ensure everything works:
     ```bash
     php artisan test
     ```
   - Check code style:
     ```bash
     ./vendor/bin/pint
     ```
   - Run Laravel Boost analysis:
     ```bash
     php artisan boost:mcp
     ```
   - Use MCP tools to verify architecture compliance
   - Update documentation in [PHASE_TRACKING.md](file:///Users/yonassayfu/VSProject/BaseBoilerPlate/laravelBoilerPlate/PHASE_TRACKING.md)

6. **Push and Create Pull Request**
   ```bash
   git push origin feature/module-name
   ```
   - Create a pull request on GitHub
   - Request review if working in a team
   - Merge to `main` after approval

7. **After Merging**
   ```bash
   git checkout main
   git pull origin main
   ```

### Git Best Practices

- **Branch Naming**: Use `feature/`, `bugfix/`, `hotfix/` prefixes
- **Testing**: Always include tests with feature implementations
- **Documentation**: Update tracking documents after implementation
- **MCP Integration**: Run Laravel Boost and MCP analysis after major implementations

## 🚀 Laravel Boost and MCP Integration Guidelines

### For Each New Module Implementation

1. **Run Laravel Boost Analysis**
   - After implementing each major component, run Laravel Boost to analyze the code:
     ```bash
     php artisan boost:mcp
     ```
   - Use the MCP tools to check architecture compliance and get suggestions

2. **MCP Tool Usage**
   - Use the following built-in Laravel Boost tools:
     - Database schema analysis
     - Laravel documentation search
     - Code execution with Tinker
   - Use your custom MCP tools:
     - `analyze_boilerplate_architecture` - Check Clean Architecture compliance
     - `list_boilerplate_models` - List all Eloquent models
     - `check_database_tables` - Inspect database schema
     - `get_boilerplate_statistics` - Get project statistics

4. **Performance Monitoring**
   - After implementing performance-critical components, use MCP tools to analyze:
     - Database query performance
     - Memory usage
     - Caching effectiveness
     - Architecture compliance score

5. **Documentation Updates**
   - Update tracking documents after running MCP analysis
   - Record any suggestions or improvements identified by MCP tools
   - Document how the new module integrates with existing MCP tools

### Generate in this exact order:

1. **Migration:** 
   - Table name: [table_name]
   - Fields: [field_list]
   - Relationships: [relationships]
   - Follow my existing migration patterns

2. **Model:**
   - Extend Eloquent Model
   - Define fillable fields
   - Add relationships (belongsTo, hasMany, etc.)
   - Include any necessary scopes
   - Follow my Staff.php model pattern

3. **DTOs:**
   - Create[MODULE]DTO.php for creation
   - Update[MODULE]DTO.php for updates
   - Follow my CreateStaffDTO.php pattern
   - Include validation rules

4. **Service:**
   - Extend PerformanceOptimizedBaseService
   - Implement caching with configurable TTL
   - Add search functionality
   - Follow my StaffService.php pattern

5. **Controllers:**
   - Admin Controller (extends OptimizedBaseController)
   - API Controller (extends BaseApiController)
   - Include CRUD operations
   - Add export functionality
   - Follow my StaffController patterns

6. **Factory & Seeder:**
   - Factory for testing data
   - Seeder with sample records
   - Follow my existing factory patterns

7. **Frontend Pages:**
   - Index.vue (list with search/filter)
   - Create.vue (form)
   - Edit.vue (form)
   - Show.vue (details)
   - Follow my Staff frontend patterns

8. **Sidebar Integration:**
   - Add menu item to AppSidebar.vue
   - Include proper permissions check
   - Follow existing menu structure

9. **Routes:**
   - web.php routes for admin
   - api.php routes for API
   - Include proper middleware
   - Follow my existing route patterns

Reference files: [Attach your Staff module files]