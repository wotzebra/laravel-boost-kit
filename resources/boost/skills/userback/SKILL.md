---
name: userback
description: "Adds Userback feedback widget integration. Activates when the user mentions Userback, feedback widget, user feedback, or wants to add the Userback script to their application."
---

# Userback Integration

## When to Activate

Activate this skill when:

- Adding the Userback feedback widget to a Laravel application
- Configuring a Userback API key
- The user mentions Userback, feedback widget, or user feedback collection

## Overview

Userback is a visual feedback tool. Integration requires:

1. A Blade component that injects the Userback JavaScript widget
2. A way to store the API key (setting, config, or env variable)
3. Including the component in the frontend layout and optionally in Filament

## Implementation Steps

### 1. Create the Blade Component

Create `resources/views/components/implementations/userback.blade.php`:

```blade
@props([
    'key' => setting('site.userback'),
])

@if ($key)
    <script defer>
        window.Userback = window.Userback || {};
        Userback.access_token = '{{ $key }}';
        (function(d) {
            var s = d.createElement('script');s.async = true;
            s.src = 'https://static.userback.io/widget/v1.js';
            (d.head || d.body).appendChild(s);
        })(document);
    </script>
@endif
```

If the project does not use a `setting()` helper, use `config('services.userback.key')` via a config file instead:

```blade
@props([
    'key' => config('services.userback.key'),
])
```

### 2. Store the API Key

**Option A: Using a settings package (e.g. Wotz FilamentSettings)**

Add a `TextInput` field to the site settings schema:

```php
TextInput::make('site.userback')
    ->label('Userback API key'),
```

**Option B: Using config/services.php**

Add to `config/services.php`:

```php
'userback' => [
    'key' => env('USERBACK_API_KEY'),
],
```

Then set `USERBACK_API_KEY` in your `.env` file.

### 3. Include in Frontend Layout

Add the component before the closing `</body>` tag in your main layout:

```blade
<x-implementations.userback />
```

### 4. Include in Filament (Optional)

Only add the Filament render hook if `filament/filament` is installed. In your `AdminPanelProvider` (or equivalent panel provider):

```php
use Filament\View\PanelsRenderHook;
use Illuminate\View\View;

->renderHook(PanelsRenderHook::BODY_END, fn (): View => view('components.implementations.userback'))
```

This ensures the Userback widget also appears inside the Filament admin panel.

## Important Notes

- The component renders nothing when no API key is configured, so it is safe to include unconditionally.
- The script loads asynchronously with `defer` and does not block page rendering.
- Filament integration is optional — skip step 4 if the project does not use Filament.
