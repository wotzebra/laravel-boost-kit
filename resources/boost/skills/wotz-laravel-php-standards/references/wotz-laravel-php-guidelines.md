# Wotzebra Laravel & PHP Guidelines

---

## 1. General PHP Rules

Follow PSR-12 (which supersedes PSR-1 and PSR-2), just like Laravel. Everything string-like that is not public-facing should use camelCase.

```php
$refreshTokenExpiryMinutes = 30;
```

---

## 2. Linting

Run linting using the project Makefile when available:

```bash
make lint
```

Otherwise:

```bash
vendor/bin/php-cs-fixer fix .
vendor/bin/phpcs --colors --report-full
```

---

## 3. Folder Structure

Follow Laravel conventions. Domain-specific folders should only be introduced in large projects where code can be clearly split into multiple domains.

---

## 4. Docblocks

Since PHP 8.0, do not use docblocks when type hints communicate intent. Only use docblocks to:
- Add meaningful explanation
- Indicate thrown exceptions (`@throws`)

```php
// GOOD:

/**
 * This relation should only be used in combination with `withUser` builder method.
 */
public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}

/** @throws \Some\Special\Exception */
public function create(): void
{
}
```

**Exception:** Every test method requires a `/** @test */` docblock.

```php
/** @test */
public function it_can_create_sets_uuid(): void
{
```

---

## 5. Comments

Comments explain *why*, not *what*. Use proper single-line or block format.

```php
// GOOD:
// If a validation exception is thrown while validating the entry data, then we will add
// the field id to this list of unsaved field ids. That way all the valid entries can
// be saved and the user can be informed which field entries could not be saved.

// BAD:
// This updates the name of the selected user.
```

Block comments use repeated `//` lines (not `/* */`).

---

## 6. Typehinting

Typehint everything: properties, parameters, return types.

Between 2 properties, add a blank line.

```php
public string $title;

public int $amount;

public Carbon $startDate;
```

Return type format: space + `:` + return type.

```php
public function index(Request $request): JsonResponse
{
    // ...
}
```

Long signatures go multiline:

```php
protected function create(
    int $amount,
    string $name,
    string $description,
): Payment {
    // ...
}
```

Multiple types:

```php
public int|float $amount;
```

Nullable types:

```php
public int|float|null $amount;

public ?int $amount;
```

---

## 7. Property and Method Visibility

- Default: `public`
- Restricted access: `protected`
- Never use: `private`

---

## 8. Enums

Case names: PascalCase. Case values: snake_case. Enum class names: singular.

```php
enum NotificationType: string
{
    case CheerReceived = 'cheer_received';
    case Followed = 'followed';
    case FollowRequested = 'follow_requested';
}
```

---

## 9. Constructor Property Promotion

Use promotion when all properties can be promoted. One per line.

```php
// GOOD:
class MyClass
{
    public function __construct(
        protected string $firstArgument,
        protected string $secondArgument,
    ) {}
}

// BAD:
class MyClass
{
    public function __construct(protected string $firstArgument, string $secondArgument)
    {
        $this->secondArgument = $secondArgument;
    }
}
```

---

## 10. Traits

One `use` statement per trait.

```php
class MyClass
{
    use TraitA;
    use TraitB;
}
```

Trait names should be adjective-like or use `Has`/`InteractsWith` prefix.
Good: `Translatable`, `Notifiable`, `HasRoles`, `InteractsWithMedia`

Store traits in a `Concerns` folder alongside the domain (e.g. `Models/Concerns`).

---

## 11. Interfaces

Store in a `Contracts` folder alongside the domain (e.g. `Models/Contracts`).

---

## 12. Strings

Use single quotes by default. Prefer interpolation over concatenation.

```php
// GOOD:
$password = 'secret';
$greeting = "Hello {$name}";

// BAD:
$password = "secret";
$greeting = 'Hello ' . $name;
```

---

## 13. Ternary Operations

Use a ternary only if the full expression fits on one line.

```php
// GOOD:
$name = $isFoo ? 'foo' : 'bar';

// BAD (does not fit one line — use if instead):
$result = 'this is a very long comparison' === 'which does not fit on one line' ?
    'foo' :
    'bar';
```

---

## 14. If Statements

Always use curly brackets.

Put the unhappy path first (early returns), happy path last.

```php
// GOOD:
if (! $user->hasPermission($permission)) {
    return;
}

// happy path ...
```

Split complex combined conditions into separate `if` blocks:

```php
// GOOD:
if (! $conditionA) {
    return;
}

if (! $conditionB) {
    return;
}

// do stuff
```

If splitting is not possible and the condition does not fit on one line, place each condition on its own line with the operator at the **start** of the new line:

```php
// GOOD:
if ($conditionA
    && $conditionB
    && $conditionC
) {
    // do stuff
}

// BAD:
if ($conditionA &&
    $conditionB &&
    $conditionC
) {
    // do stuff
}
```

Avoid `else` when it can be written without one:

```php
// GOOD:
if ($condition) {
    // ...
    return;
}

// condition failed ...

// BAD:
if ($condition) {
    // ...
} else {
    // condition failed ...
}
```

---

## 15. Whitespace

Add blank lines between statement groups. Tightly related statements can stay grouped.

```php
// GOOD:
public function update(UpdateDeviceRequest $request): Response
{
    $device = $request->device();

    if ($request->has('firebase_token')) {
        $device->setFirebaseToken($request->get('firebase_token'));
    }

    $device->setLocale();

    return response(null)->setStatusCode(204);
}
```

Related statements can be grouped without blank lines:

```php
$reply = Reply::make();
$reply->notice()->associate($data->notice);
$reply->user()->associate($data->user);
$reply->save();

$reply->contactInfos()->sync($data->contactInfos->pluck('id'));
```

---

## 16. Configuration

- Filenames: kebab-case (`foo-bar.php`)
- Keys: snake_case (`foo_bar`)
- External service credentials → `config/services.php` (no new config file)
- New env vars → add to `.env.example` without a value
- Prefer granular time units (minutes/seconds) for easier QA testing

---

## 17. Artisan Commands

Command names in kebab-case:

```bash
// GOOD:
php artisan foo:bar-baz

// BAD:
php artisan foo:barBaz
```

---

## 18. Routing

URI segments in kebab-case. No leading `/`. Register routes individually. Add middleware after the route definition.

```php
// GOOD:
Route::get('messages', [MessageController::class, 'index']);
Route::get('message', [MessageController::class, 'show'])->middleware('api');

// BAD:
Route::get('/message', [MessageController::class, 'show']);
Route::apiResource('message', MessageController::class);
Route::middleware('api')->get('message', [MessageController::class, 'show']);
```

---

## 19. HTTP Verbs

Use `PATCH` only when a route can succeed without any data in the body.
Use `PUT` when certain data must always be present in the request body.

---

## 20. Controllers

Use singular resource name:

```php
// GOOD:
class OrganizationController {}

// BAD:
class OrganizationsController {}
```

---

## 21. Validation

Always use array notation; never pipe notation. Each rule on its own line.

```php
// GOOD:
public function rules(): array
{
    return [
        'email' => [
            'required',
            'email',
        ],
    ];
}

// BAD:
public function rules(): array
{
    return [
        'email' => 'required|email',
    ];
}
```

---

## 22. Form Requests

Use a `FormRequest` for route validation. Name after controller + method (or HTTP verb for GET):

```
StoreChatMessageRequest    --> ChatMessageController:store
UpdateMessageRequest       --> MessageController:update
ShowMessageRequest         --> MessageController:show
```

- Authorization only → do it in the controller, no FormRequest needed
- Validation only → remove the `authorize` method

FormRequests can also format/process data. Use `once()` from `spatie/once` for DB access in custom methods:

```php
class UpdateCurrentUserRequest extends FormRequest
{
    public function languages(): Language
    {
        return once(function () {
            return Language::find($this->get('language_ids'));
        });
    }
}
```

---

## 23. DTOs

Use DTO classes (`*Data`) to avoid long parameter lists. Avoid nested arrays; use nested DTOs instead.

```php
class TrainingLocationData
{
    public function __construct(
        public PlaceData $place,
        public string $name,
        public ?StorageUploadedFile $image,
    ) {}
}
```

DTOs can include static factory methods:

```php
public static function fromRequest(UpdateCurrentUserRequest $request): self
{
    return new self(
        firstname: $request->get('firstname'),
        lastname: $request->get('lastname'),
    );
}
```

---

## 24. Actions

Use Action classes for complex flows. The method name should reflect the action (`create`, `update`, …).

```php
class CreateMessage
{
    public function create(CreateMessageData $data): Message
    {
        // ...
    }
}

app(CreateMessage::class)->create($data);
```

Actions must only use provided data. Never access `auth()->user()` or the request inside an action — pass them as arguments instead.

---

## 25. Authorization

Use policies. Policy ability names should be descriptive (e.g. `viewAny`, `view`, `create`, `update`, `delete`).

---

## 26. Translations

Always use `__()`.

---

## 27. Class Naming

| Type | Convention | Example |
|---|---|---|
| API Resource | `{Model}Resource` | `UserResource` |
| Job | `{Description}Job` | `SendNotificationsToInactiveUsersJob` |
| Event | Describes when fired, no suffix | `PublishingTrainingPlan`, `TrainingPlanPublished` |
| Listener | `{Description}Listener` | `CleanupTokensListener` |
| Command | `{Description}Command` | `SyncUsersCommand` |
| Notification | `{Description}Notification` | `FollowerAddedNotification` |
| Mailable | `{Description}Mail` | `WelcomeMail` |
| Action | Describes the action, no suffix | `CreateUser`, `ResetPersonalTrainingPlan` |
| Enum | Singular | `NotificationType` |
| Observer | `{Model}Observer` | `UserObserver` |
| Policy | `{Model}Policy` | `UserPolicy` |
| DTO | `{Description}Data` | `CreateUserData`, `ExerciseSessionData` |

**Events:** `PublishingTrainingPlan` = fired before the action. `TrainingPlanPublished` = fired after.

---

## 28. Database Tables

Follow Laravel conventions:
- Model table: plural model name
- Pivot table: singular model names in alphabetical order (e.g. `ingredient_recipe`)

Column order:
1. ID and other identifiers
2. Data columns (identifying columns like `type`/`title` first)
3. Foreign key columns
4. Datetime columns with `_at` suffix (`completed_at`, `status_updated_at`)

---

## 29. Database Migrations

Never use Eloquent models inside migrations — models may change or be deleted in the future.

---

## 30. Database Transactions

Use `DB::transaction($closure)`, never manually `beginTransaction`/`commit`:

```php
// GOOD:
DB::transaction(function () {
    // queries
});

// BAD:
DB::beginTransaction();
// queries
DB::commit();
```

---

## 31. Eloquent

### Model Code Order

1. Properties: `$fillable`, `$hidden`, `$casts`, `$with`, `$perPage`, package-specific variables
2. Methods overriding `Model` base class (e.g. `newEloquentBuilder`, `newCollection`)
3. Relationship methods
4. Accessors and mutators
5. Custom methods
6. Package/Laravel feature methods

### Fillable

Always list every non-relation property explicitly.

```php
protected $fillable = [
    'uuid',
    'firstname',
    'lastname',
    'email',
    'password',
];
```

### Casts

Store in `Models/Casts` folder.

### Eloquent Builders

Store in `Models/Builders` folder, suffix `Builder`. Use custom builders (not model scopes) for complex queries. Register the builder using the `#[UseEloquentBuilder]` attribute (Laravel 12+):

```php
#[UseEloquentBuilder(UserBuilder::class)]
class User extends Model {}

class UserBuilder extends Builder
{
    public function orderByDistanceTo(Coordinate $coordinate): self {}
}
```

Model scopes are only used in packages or when shared across multiple models.

### Observers

Store in `App/Observers` folder. Register the observer using the `#[ObservedBy]` attribute (Laravel 10+):

```php
#[ObservedBy([UserObserver::class])]
class User extends Model {}

class UserObserver
{
    public function created(User $user): void {}
    public function updated(User $user): void {}
}
```

### Eloquent Collections

Store in `Models/Collections` folder, suffix `Collection`:

```php
#[CollectedBy(ArticleCollection::class)]
class Article {}

class ArticleCollection extends Collection
{
    public function isPublished(): self {}
}
```

### Accessors and Mutators

Always provide a return type:

```php
protected function fullname(): Attribute
{
    return Attribute::make(
        get: fn ($value): string => "{$this->firstname} {$this->lastname}",
    );
}
```

---

## 32. Tests

### Folder Structure

Do not separate unit from feature tests. Group by domain/concern.

### Test Methods

Snake case with `it_` prefix:

```php
// GOOD:
public function it_can_update_phone_to_e164_format(): void {}

// BAD:
public function testUpdateFormatsPhoneToE164Format(): void {}
```

### Assertions

Use `assertSame` (strict) over `assertEquals`:

```php
$this->assertSame($expected, $actual);
```

---

## 33. Model Factories

The default state reflects the most common scenario. Provide relation defaults where practical.

```php
public function definition(): array
{
    return [
        'firstname'  => $this->faker->firstName(),
        'lastname'   => $this->faker->lastName(),
        'email'      => $this->faker->unique()->safeEmail(),
        'address_id' => Address::factory(),
    ];
}
```

Use opt-in factory states for media/file attachments to keep tests fast:

```php
public function withAvatar(): static
{
    return $this->afterCreating(function (User $user) {
        $user->addMedia(UploadedFile::fake()->create('avatar.jpg'))
            ->toMediaCollection('avatar');
    });
}
```

---

## Final Decision Rule

When standards conflict:
1. Existing pattern in the touched module
2. Wotzebra Code Style (this document)
3. Generic Laravel defaults