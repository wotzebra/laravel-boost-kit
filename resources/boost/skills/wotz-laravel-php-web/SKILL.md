---
name: wotz-laravel-php-web
description: Apply Wotzebra's web-specific Laravel conventions for routing, Blade, and Livewire. Activate alongside wotz-laravel-php-standards when working in a web (CMS/frontend) project.
---

# Wotzebra Web Conventions

## When to Activate

Activate when working in a web project alongside `wotz-laravel-php-standards`.

Signs of a web project: `routes/web.php` exists, Blade views or Livewire components are present, Livewire is in `composer.json`.

## Routing

- Use **slugs** instead of IDs
- Nested routes are allowed (for SEO reasons)
- Route names everywhere: plural, kebab-cased (e.g. `news-items.show`)
- URLs are plural and kebab-cased (e.g. `/news-items/:slug`)

```php
Route::get('categories/{category:slug}/products/{product:slug}', [ProductController::class, 'show'])
    ->name('products.show');
```

## Views

View files in kebab-case, located by controller or Livewire component:

```
resources/views/news-items/index.blade.php
resources/views/livewire/order/index.blade.php
resources/views/livewire/shopping-cart.blade.php
```

## Blade Templates

Indent with 4 spaces.

## Translations

```php
__('about.how we work')
__('how_we_work.about')
__('product.hello there :name', ['name' => 'Test'])
```

## Livewire

### Folder Structure

Group components by model name. Use the same folder structure in `app/Livewire`, views, and tests:

```
app/Livewire/Event/
  CreateEventPage
  UpdateEventPage
  DeleteEventModal

resources/views/livewire/event/
  create-event-page.blade.php
  update-event-page.blade.php
  delete-event-modal.blade.php
```

### Component Names

Every Livewire component must have a suffix reflecting its visual type: `Page`, `Modal`, `SlideOver`, etc.

### Shared Validation and Fieldset

When multiple components share the same form (e.g. create and update of the same model):

- Share **validation** via a trait. Define all properties and validation rules in the trait. Repeat used properties in the component itself for clarity.
- Share **HTML** via a fieldset Blade component at `resources/views/components/fieldset/{modelname}.blade.php`. Use `@props` to require external data; fetch self-sufficient data (e.g. select options) inside the fieldset with `@php`.

### Wire Elements Pro

Use [Wire Elements Pro](https://wire-elements.dev/) for modals and slide-overs.

### Middleware

For global Livewire authorization, add middleware via a service provider:

```php
Livewire::addPersistentMiddleware([
    App\Http\Middleware\EnsureUserHasRole::class,
]);
```
