# Project Coding Guidelines

## Code linting

- Run {{ $assist->composerCommand('lint') }} to lint all code in the project. `PHP-CS-Fixer` and `PHP_CodeSniffer` are used to lint PHP code. `Prettier` is used to lint Blade / Javascript / CSS code.

## PHP

### Type Declarations

- Typehint everything: properties, parameters, return types.

### Property and Method Visibility

- Only use `public` and `protected` visibility for properties and methods. Never use `private`.

### Enums

- Enum case names should be PascalCase
- Enum case values should be snake_case.

### Traits

- One `use` statement per trait.
- Trait names should be adjective-like or use `Has`/`InteractsWith` prefix (e.g. `Translatable`, `Notifiable`, `HasRoles`, `InteractsWithMedia`)
- Put traits in a `Concerns` folder alongside the domain (e.g. `Models/Concerns` when Trait is used on models).

### Interfaces

- Put interfaces in a `Contracts` folder alongside the domain (e.g. `Models/Contracts` when interface is used on models).

### String concatenation

- Use single quotes by default. Prefer interpolation over concatenation.

```php
$password = 'secret';
$greeting = "Hello {$name}";
```

### If statements

- Put the unhappy path first (early returns), happy path last.

```php
if (! $user->hasPermission($permission)) {
    return;
}

// happy path ...
```

- Split complex combined conditions into separate `if` blocks:

```php
if (! $conditionA) {
    return;
}

if (! $conditionB) {
    return;
}

// do stuff
```

- If splitting is not possible and the condition does not fit on one line, place each condition on its own line with the operator at the **end** of the new line:

```php
if ($conditionA &&
    $conditionB &&
    $conditionC
) {
    // do stuff
}
```

- Avoid `else` when it can be written without one.

### Whitespace

- Add blank lines between statement groups. Tightly related statements can stay grouped. Give the code some room to breathe.

### Comments

- Since PHP 8.0, do not use docblocks when type hints communicate intent. Only use docblocks to add meaningful explanation or indicate thrown exceptions (`@throws`).

## Laravel

### Config files

- External service credentials should be defined in `config/services.php`. Do not create new config files for external services.
- When a new env variable is added to a config file, then ask the user if they want to add the env variable to the `.env.example` file as well. If so, add it.

### Controllers

- Controller names should be singular and should try to closely match the route (e.g. `ChatMessageController` for `/chat/{chat}/messages` route).
- Controller methods should be named after the HTTP verb they handle or based on what they return:
    - `index` for listing resources (e.g. `GET /books`)
    - `view` for showing a single resource (e.g. `GET /book/{book}`)
    - `store` for creating a resource (e.g. `POST /book`)
    - `update` for updating a resource (e.g. `PUT /book/{book}` or `PATCH /book/{book}`)
    - `destroy` for deleting a resource (e.g. `DELETE /book/{book}`)
- Keep controllers thin. Controllers should only be responsible for handling the request and returning a response. Move any business logic to actions, models, or other classes.
- Use `[#CurrentUser] User $user` to inject the currently authenticated user into controller methods instead of using `auth()->user()`.

### Validation

- Always use array notation; never pipe notation. Each rule on its own line.

### Form Requests

- Use form requests for validating input, never put validation logic in the controller.
- Name the form request based on the controller and method (e.g. `StoreChatMessageRequest` for `ChatMessageController@store`, `UpdateMessageRequest` for `MessageController@update`, `DestroyBookFavoriteRequest` for `BookFavoriteController@destroy`).
- If the route needs authorization, use the `authorize` method of the form request. If not, remove the `authorize` method.
- When the data of a form request needs to be used by an action class, then create a DTO for the form request data and add a static `fromRequest()` method to the form request that transforms the form request data into the DTO.

### Authorization

- Authorization logic should be placed in policies, never in controllers or actions. Use gates for simple authorization checks that are not related to a specific model.

### DTOs

- DTO classes should be created in the `app\DTO` directory and always have the `Data` suffix.
- Use DTO classes to avoid long parameter lists. Avoid nested arrays; use nested DTOs instead.
- The properties on a DTO class should all be public and have type declarations.

### Actions

- Use Action classes for complex flows or when a flow is used in multiple places. For simple flows that are only used in one place, keep the logic in the controller or model.
- Use the `wotz/laravel-enhanced-actions` package to create action classes. Action classes should have a single public method named `create` that accepts a DTO as an argument and returns a result (if applicable).
- Actions must only use provided data. Never access `auth()->user()` inside an action, pass the user as argument instead. Never access the request inside an action, pass the request data as argument(s) instead (or create a DTO for the request data).

### Migrations

- Never use Eloquent models inside migrations — models may change or be deleted in the future.
- Ensure colums in every table stays in a consistent order:
    1. ID and other identifiers
    2. Data columns (identifying columns like `type`/`title` first)
    3. Foreign key columns
    4. Datetime columns with `_at` suffix (`completed_at`, `status_updated_at`)

### Eloquent Models

#### Fillable

- Always list every non-relation attributes explicitly, but never foreign keys in the fillable array. Relations should be set using Eloquent relationship methods, not by setting foreign key attributes directly.

#### Casts

- Add casts for all attributes (including foreign keys) that are not strings/texts.
- Store custom casts in `Models/Casts` folder.

#### Eloquent Builders

- Custom Eloquent builders should be created in the `Models/Builders` folder and always have the suffix `Builder`.
- Never use model scopes for complex queries. Always create a custom Eloquent builder for a model and add methods to the builder for complex queries. This keeps the model clean and allows for better reusability and testability of query logic.
- Register a model's custom Eloquent builder using the `#[UseEloquentBuilder]` attribute.

#### Integrations with external services

- Use `saloonphp/saloon` package to integrate with external services.

## Tests

### Structure

- Do not separate unit from feature tests. Group by domain/concern.

### PHPUnit

- Avoid `$this->assertEquals()`; Prefer `$this->assertSame()`.
- Use `$response->assertEqualsCanonicalizing()` when asserting against arrays where the order of items does not matter.
- Use `$response->assertOnlyJsonValidationErrors()` instead of `$response->assertJsonValidationErrors()` to ensure that only the expected validation errors are returned.
- Every test method requires a `#[Test]` attribute.
- Test methods do not have a return type.
- Test method name should be descriptive, use snake_case, and start with `it_`.
- Use fluent response assertions when testing API endpoints (e.g. `$this->getJson('/api/users')->assertStatus(200)->assertJson(...)`). Only define a variable for the response when you really need to.
- When a model needs to be asserted against in a test, always retrieve a fresh instance of the model from the database instead of using the instance that was created during the test setup. Also name that variable `$dbModel` to make it clear that it is a fresh instance from the database and so you can easily compare it with the `$model` instance that was created during the test setup.

```php
#[Test]
public function it_can_assert_that_user_can_update_its_name()
{
    $user = User::factory()->create();

    $user->update(['name' => 'Taylor Otwell']);

    $dbUser = User::find($user->id);

    $this->assertSame('Taylor Otwell', $dbUser->name);
}
```

# API Development

- IMPORTANT: Activate `api-development` every time you need to implement a new API endpoint or update an existing API endpoint in the Laravel application.
