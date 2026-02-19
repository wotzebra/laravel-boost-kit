---
name: wotz-laravel-php-standards
description: Apply Wotzebra's base Laravel and PHP code style. Activate for any task that creates, edits, reviews, refactors, or formats Laravel/PHP code.
---

# Wotzebra Laravel & PHP Code Style

## When to Activate

Activate whenever you:
- Create or edit `.php` files
- Touch controllers, models, migrations, requests, DTOs, actions, policies, tests
- Review or refactor backend code

This is the **base skill**. Also activate `wotz-laravel-php-api` for API projects or `wotz-laravel-php-web` for web projects.

## How to Apply

1. Identify what you are changing (controller, action, model, request, test, …)
2. Look up the relevant section in `references/wotz-laravel-php-guidelines.md`
3. Apply Wotzebra rules first, then project-local conventions
4. Call out trade-offs when legacy code conflicts with current standards

## Key Conventions

### PHP & General
- Follow PSR-12 (which supersedes PSR-1 and PSR-2)
- Non-public-facing string identifiers: camelCase
- Typehint everything: properties, parameters, return types
- No docblocks when type hints suffice; use them only for meaningful explanation or `@throws`
- Every test method requires `/** @test */`
- Never use `private`; default to `public`, use `protected` when needed

### Strings
- Single quotes by default
- Interpolation over concatenation: `"Hello {$name}"`

### Control Flow
- Always use braces on `if` statements
- Unhappy path first (early returns), happy path last
- Split complex conditions into multiple `if` blocks
- Multiline conditions: operator at the **start** of each new line
- Avoid `else` when not needed

### Whitespace
- Blank line between every statement group
- Related mini-sequences can stay grouped without blank lines

### Naming
| Type | Convention |
|---|---|
| Enum case names | PascalCase |
| Enum values | snake_case |
| Config files | kebab-case |
| Config keys | snake_case |
| Artisan commands | kebab-case (`foo:bar-baz`) |
| Route URIs | kebab-case, no leading `/` |
| Test methods | `it_` prefix, snake_case |

### HTTP Verbs
- `PATCH`: only when the route can succeed with no data in the body
- `PUT`: when certain data must always be present

### Controllers
- Singular name: `OrganizationController`

### Validation & Form Requests
- Array notation only, each rule on its own line
- FormRequest name = controller + method (e.g. `StoreChatMessageRequest`)
- Use `once()` for DB access in FormRequest methods (built into Laravel 11+; use `spatie/once` for Laravel 10)

### DTOs & Actions
- DTO suffix: `Data` (e.g. `CreateUserData`)
- Prefer nested DTOs over nested arrays
- Action class: no suffix, describes the action (e.g. `CreateUser`)
- Actions must not access `auth()` or request globals; receive data as arguments

### Eloquent
- Model code order: properties → overrides → relations → accessors → custom methods → package methods
- Explicit `$fillable`
- Custom builders in `Models/Builders`, collections in `Models/Collections`, casts in `Models/Casts`
- Register custom builders with `#[UseEloquentBuilder(UserBuilder::class)]` (Laravel 12+)
- Register observers with `#[ObservedBy([UserObserver::class])]` (Laravel 10+)
- Use `DB::transaction($closure)`, never manual `begin`/`commit`
- Never use Eloquent models in migrations

### Tests
- Group by domain, not unit/feature split
- Test methods: `it_` prefix, snake_case
- Use `assertSame` (strict), not `assertEquals`

## Examples

### Early return
```php
if (! $user->hasPermission($permission)) {
    return;
}

$this->publish($post);
```

### Multiline condition — operator at start
```php
if ($conditionA
    && $conditionB
    && $conditionC
) {
    // do stuff
}
```

### Validation array syntax
```php
public function rules(): array
{
    return [
        'email' => [
            'required',
            'email',
        ],
    ];
}
```

### Test method
```php
/** @test */
public function it_can_update_phone_to_e164_format(): void
{
    $expected = '+32470111222';
    $actual = $formatter->toE164('0470 11 12 22');

    $this->assertSame($expected, $actual);
}
```

## Full Reference
`references/wotz-laravel-php-guidelines.md`