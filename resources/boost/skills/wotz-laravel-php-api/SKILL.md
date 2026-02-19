---
name: wotz-laravel-php-api
description: Apply Wotzebra's API-specific Laravel conventions for routing, controllers, resources, and tests. Activate alongside wotz-laravel-php-standards when working in an API project.
---

# Wotzebra API Conventions

## When to Activate

Activate when working in an API project alongside `wotz-laravel-php-standards`.

Signs of an API project: `routes/api.php` exists, controllers return `JsonResponse`, no Blade views or Livewire components.

## Routing

Route name singular/plural based on response:

```
// GOOD:
/message/85     --> returns one message
/messages       --> returns multiple messages

// BAD:
/messages/85    --> returns one message
```

Limit nested routes. If a resource has only one parent, don't include the parent ID in the route:

```
// GOOD:
/message/58

// BAD:
/chat/12/message/58
```

## Controllers

Name nested-route controllers after the route:

```php
// GOOD:
class ChatMessageController {}      // GET /chat/12/messages
class MessageFlagController {}      // POST /message/58/flag
class CurrentUserController {}      // GET /user/current
class CurrentUserChatController {}  // GET /user/chats

// BAD:
class MessageController {}          // GET /chat/12/messages
class FlagController {}             // POST /message/58/flag
```

Only CRUD methods allowed, in this order:

- `index` — GET multiple items
- `store` — POST
- `show` — GET single item
- `update` — PUT / PATCH
- `destroy` — DELETE

Non-CRUD behavior → separate controller:

```php
// GOOD:
class MessageFlagController
{
    public function store() {}
}

// BAD:
class MessageController
{
    public function flag() {}
}
```

## Eloquent

The `$fillable` array must **never** contain foreign keys. Setting a relation should be done using explicit relationship methods.

```php
// BAD:
protected $fillable = [
    'user_id',      // foreign key — not allowed in API projects
    'firstname',
];

// GOOD:
protected $fillable = [
    'firstname',
];

// Set the relation explicitly:
$model->user()->associate($user);
```

## Translations

Use PHP translation files for specific keys:
- policies, enums, API error messages, validation messages, notifications, mails

All translations are synced with POEditor.

## Tests

### Folder Structure

Group HTTP tests by their highest-level singular resource prefix:

```
Http/
  CurrentUser/
    ShowCurrentUserTest
    UpdateCurrentUserTest
  Organization/
    ShowOrganizationTest
    GetOrganizationsTest
  Webhooks/
    StripeWebhookTest
Actions/
  CreateUserActionTest
Queries/
  UserBuilder/
    OrderUsersByNameQueryTest
```

### Route Test Naming

`{CrudMethod}{Controller}Test`:

```
ShowCurrentUserTest       --> CurrentUserController:show
GetOrganizationsTest      --> OrganizationController:index
StoreChatMessageTest      --> ChatMessageController:store
```

### Authorization Tests

Every route test class must contain exactly **2 authorization tests**: one that results in allow and one that results in deny. Use mocked policies from `laravel-test-helpers`. This avoids duplicating policy logic in every route test.

```php
/** @test */
public function it_allows_the_owner_to_view_the_message(): void {}

/** @test */
public function it_denies_a_non_owner_from_viewing_the_message(): void {}
```
