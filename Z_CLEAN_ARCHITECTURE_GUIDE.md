# EfficencyInLaravel.md - Clean Architecture Deep Dive

## 🏛️ Clean Architecture principles and layer separation
## 📱 Controllers (thin controllers vs fat controllers)
## 🧠 Services (business logic implementation)
## 📊 DTOs (data transfer objects with validation)
## 🗄️ Models (Eloquent relationships and best practices)
## 🌐 Frontend Architecture (Vue 3 + Inertia.js patterns)
## ⚠️ Exception Handling hierarchy
## 🔔 Events & Listeners for domain events
## 🔐 Policies & Authorization patterns
## 📝 Enums & Configuration management
## 🔄 DRY Principles implementation
## 📁 File Organization structure and purpose



# 🏛️ Clean Architecture Guide 

## 🏗️ Architecture Overview

### **Layer Structure**

```
🌐 Controllers (Presentation) → 🧠 Services (Business Logic) → 🗄️ Models (Data)
```

**Key Principle**: Inner layers (Services, Models) should NOT depend on outer layers (Controllers)

---

## 📱 Controllers (Thin Controllers)

### **✅ GOOD Pattern**

```php
class PatientController extends BaseController
{
    public function store(Request $request)
    {
        // 1. Validate (delegate to DTO)
        $dto = CreatePatientDTO::fromRequest($request);

        // 2. Execute business logic (delegate to service)
        $patient = $this->service->create($dto);

        // 3. Return response
        return redirect()->route('patients.index')
            ->with('success', 'Patient created successfully');
    }
}
```

### **❌ BAD - Fat Controller**

```php
// Don't do business logic in controllers
// Don't do database queries directly
// Don't do email sending
// Don't do complex validations
```

---

## 🧠 Services (Business Logic Layer)

### **Service Responsibilities**

- Domain rules and validations
- Complex calculations
- Data transformations
- External service integrations
- Event dispatching

```php
class PatientService extends PerformanceOptimizedBaseService
{
    protected $model = Patient::class;
    protected $cachePrefix = 'patients';

    public function create(CreatePatientDTO $dto): Patient
    {
        // Business rule validation
        if ($this->isMinor($dto->date_of_birth)) {
            throw new BusinessException('Minor patients require guardian consent');
        }

        DB::beginTransaction();
        try {
            // Generate business logic (patient code)
            $patientCode = $this->generatePatientCode();

            // Create patient
            $patient = Patient::create([
                'full_name' => $dto->full_name,
                'patient_code' => $patientCode,
                // ... other fields
            ]);

            // Business rule: Auto-assign to care team
            $this->assignToDefaultCareTeam($patient);

            // Dispatch domain event
            event(new PatientRegistered($patient));

            DB::commit();
            $this->clearCache(['patients', 'dropdown_patients']);

            return $patient;

        } catch (Exception $e) {
            DB::rollback();
            throw new ServiceException('Failed to create patient: ' . $e->getMessage());
        }
    }

    private function generatePatientCode(): string
    {
        // Business logic implementation
        $prefix = 'PT';
        $year = date('Y');
        $sequence = Patient::whereYear('created_at', $year)->count() + 1;
        return sprintf('%s%s%04d', $prefix, $year, $sequence);
    }
}
```

---

## 📊 DTOs (Data Transfer Objects)

### **Purpose**

- Type safety between layers
- Input validation and sanitization
- Data transformation

```php
class CreatePatientDTO extends BaseDTO
{
    public function __construct(
        public readonly string $full_name,
        public readonly ?Carbon $date_of_birth,
        public readonly ?string $gender,
        public readonly ?string $phone_number,
        public readonly ?string $email,
    ) {}

    public static function fromRequest(Request $request): self
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:Male,Female,Other',
            'phone_number' => 'nullable|string|unique:patients,phone_number',
            'email' => 'nullable|email|unique:patients,email',
        ]);

        return new self(
            full_name: $validated['full_name'],
            date_of_birth: isset($validated['date_of_birth'])
                ? Carbon::parse($validated['date_of_birth']) : null,
            gender: $validated['gender'] ?? null,
            phone_number: $validated['phone_number'] ?? null,
            email: $validated['email'] ?? null,
        );
    }
}
```

---

## 🗄️ Models (Data Access Layer)

### **Model Responsibilities**

- Database relationships
- Attribute casting and mutators
- Query scopes
- Simple data-related business rules

```php
class Patient extends Model
{
    protected $fillable = ['full_name', 'date_of_birth', 'gender', 'phone_number', 'email'];

    protected $casts = [
        'date_of_birth' => 'date',
        'emergency_contact' => 'array',
    ];

    // Relationships
    public function visitServices(): HasMany
    {
        return $this->hasMany(VisitService::class);
    }

    public function caregiverAssignments(): HasMany
    {
        return $this->hasMany(CaregiverAssignment::class);
    }

    // Query Scopes
    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('deleted_at');
    }

    // Accessors
    public function getAgeAttribute(): ?int
    {
        return $this->date_of_birth?->age;
    }

    // Model Events
    protected static function booted(): void
    {
        static::created(function (Patient $patient) {
            Cache::forget('dropdown_patients');
            event(new PatientCreated($patient));
        });
    }
}
```

---

## 🌐 Frontend Architecture (Vue 3 + Inertia.js)

### **TypeScript Types**

```typescript
interface Patient {
    id: number;
    full_name: string;
    date_of_birth: string | null;
    gender: 'Male' | 'Female' | 'Other' | null;
    phone_number: string | null;
    email: string | null;
    age: number | null;
}
```

### **Vue Component Pattern**

```vue
<template>
    <div class="patients-index">
        <SearchFilters v-model:search="filters.search" @update="handleFiltersUpdate" />
        <DataTable :data="patients.data" :columns="columns" />
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

interface Props {
    patients: PaginatedResponse<Patient>;
    filters: FilterParams;
}
const props = defineProps<Props>();

const filters = ref({
    search: props.filters.search || '',
    sort: props.filters.sort || 'full_name',
});

const handleFiltersUpdate = debounce(() => {
    router.get(route('patients.index'), filters.value, {
        preserveState: true,
        preserveScroll: true,
    });
}, 300);
</script>
```

### **Data Flow**

```
Vue Component → Inertia.js → Controller → Service → Model → Database
              ← Response ← Response ← Response ← Response ←
```

---

## ⚠️ Exception Handling

### **Exception Hierarchy**

```php
abstract class BaseException extends Exception
{
    protected string $userMessage;

    public function getUserMessage(): string
    {
        return $this->userMessage ?? 'An error occurred';
    }
}

class BusinessException extends BaseException {}
class ServiceException extends BaseException {}
class ValidationException extends BaseException {}
```

### **Usage in Services**

```php
// Business rule violation
if ($patient->age < 18 && !$hasGuardianConsent) {
    throw new BusinessException('Minor patients require guardian consent');
}

// Service-level error
try {
    $result = $externalService->process($data);
} catch (Exception $e) {
    throw new ServiceException('External service unavailable');
}
```

---

## 🔔 Events & Listeners

### **Domain Events**

```php
class PatientRegistered
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Patient $patient,
        public readonly Carbon $registeredAt,
    ) {}
}
```

### **Event Listeners**

```php
class SendWelcomeEmail
{
    public function handle(PatientRegistered $event): void
    {
        Mail::to($event->patient->email)
            ->queue(new WelcomePatientEmail($event->patient));
    }
}

// Register in EventServiceProvider
protected $listen = [
    PatientRegistered::class => [
        SendWelcomeEmail::class,
        AssignToCareTeam::class,
    ],
];
```

---

## 🔐 Policies & Authorization

### **Policy Pattern**

```php
class PatientPolicy
{
    public function view(User $user, Patient $patient): bool
    {
        // Admin can view all
        if ($user->hasRole('admin')) {
            return true;
        }

        // Staff can only view assigned patients
        if ($user->hasRole('staff')) {
            return $patient->caregiverAssignments()
                ->where('staff_id', $user->staff?->id)
                ->exists();
        }

        return false;
    }

    public function update(User $user, Patient $patient): bool
    {
        // Business rule: Can't update archived patients
        if ($patient->status === 'Archived') {
            return false;
        }

        return $user->hasPermission('update-patients');
    }
}
```

---

## 📝 Enums & Configuration

### **Type-Safe Enums**

```php
enum PatientStatus: string
{
    case ACTIVE = 'Active';
    case INACTIVE = 'Inactive';
    case ARCHIVED = 'Archived';

    public function getColor(): string
    {
        return match($this) {
            self::ACTIVE => 'green',
            self::INACTIVE => 'yellow',
            self::ARCHIVED => 'gray',
        };
    }
}
```

### **Configuration**

```php
// config/healthcare.php
return [
    'patient' => [
        'code_prefix' => env('PATIENT_CODE_PREFIX', 'PT'),
        'minor_age_threshold' => env('MINOR_AGE_THRESHOLD', 18),
    ],
    'performance' => [
        'cache_ttl' => [
            'dropdown' => env('CACHE_TTL_DROPDOWN', 300),
            'service' => env('CACHE_TTL_SERVICE', 3600),
        ],
    ],
];
```

---

## 🔄 DRY Principles

### **Base Classes**

```php
// Shared functionality across services
abstract class BaseService
{
    protected $model;

    public function getAll(Request $request): LengthAwarePaginator
    {
        return $this->model::query()
            ->when($request->search, fn($q) => $this->applySearch($q, $request->search))
            ->paginate($request->per_page ?? 15);
    }

    abstract protected function applySearch(Builder $query, string $search): Builder;
}

// Shared validation rules
trait HasCommonValidations
{
    protected function phoneValidation(): array
    {
        return ['nullable', 'string', 'regex:/^[\+]?[1-9][\d]{0,15}$/'];
    }

    protected function emailValidation(): array
    {
        return ['nullable', 'email', 'max:255'];
    }
}
```

---

## 🗄️ Eloquent Best Practices

### **Relationships**

```php
// ✅ GOOD: Optimized relationships
public function visitServices(): HasMany
{
    return $this->hasMany(VisitService::class)
        ->orderBy('scheduled_at', 'desc');
}

public function activeVisitServices(): HasMany
{
    return $this->visitServices()
        ->where('status', '!=', 'Cancelled');
}

// ✅ GOOD: Many-to-many with pivot
public function staff(): BelongsToMany
{
    return $this->belongsToMany(Staff::class, 'caregiver_assignments')
        ->withPivot(['assignment_type', 'assigned_at'])
        ->withTimestamps();
}
```

### **Query Optimization**

```php
// ✅ GOOD: Eager loading to prevent N+1
$patients = Patient::with(['visitServices', 'caregiverAssignments.staff'])
    ->where('status', 'Active')
    ->paginate(15);

// ✅ GOOD: Query scopes for reusability
Patient::active()->byGender('Female')->latest()->get();

// ✅ GOOD: Chunking for large datasets
Patient::chunk(100, function ($patients) {
    foreach ($patients as $patient) {
        // Process patient
    }
});
```

---

## 📁 File Organization

### **Directory Structure Purpose**

```
📁 app/
├── 📁 Http/Controllers/          # HTTP request handling only
│   ├── 📁 Base/                  # Shared controller functionality
│   ├── 📁 Admin/                 # Admin-specific controllers
│   └── 📁 Api/V1/                # API versioning
├── 📁 Services/                  # Business logic implementation
│   ├── 📁 Base/                  # Shared service functionality
│   └── 📁 Validation/Rules/      # Custom validation logic
├── 📁 DTOs/                      # Data transfer between layers
├── 📁 Models/                    # Data access and relationships
├── 📁 Events/                    # Domain events
├── 📁 Listeners/                 # Event handlers
├── 📁 Policies/                  # Authorization logic
└── 📁 Enums/                     # Type-safe constants

📁 resources/js/
├── 📁 pages/                     # Vue pages (mirrors Laravel routes)
├── 📁 components/                # Reusable Vue components
├── 📁 composables/               # Vue composition functions
└── 📁 types/                     # TypeScript definitions

📁 database/
├── 📁 migrations/                # Database schema changes
├── 📁 seeders/                   # Test/demo data
└── 📁 factories/                 # Model factories
```

---

## 🎯 Key Principles Summary

1. **Separation of Concerns**: Each layer has distinct responsibilities
2. **Dependency Inversion**: Inner layers don't depend on outer layers
3. **Single Responsibility**: Each class has one reason to change
4. **DRY**: Don't Repeat Yourself - use base classes and traits
5. **Performance First**: Cache aggressively, optimize queries, prevent N+1
6. **Type Safety**: Use TypeScript, DTOs, and PHP 8+ features
7. **Event-Driven**: Use domain events for side effects
8. **Authorization**: Policies for business rules, permissions for access

This architecture ensures maintainable, testable, and performant code! 🚀
