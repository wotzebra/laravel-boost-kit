# Wotzebra Guidelines for Laravel Boost

Bring Wotzebra's Laravel & PHP coding guidelines to your AI-assisted development workflow with Laravel Boost.

## Installation

Install the package via Composer:

```bash
composer require wotzebra/boost-wotz-guidelines --dev
```

Then install the guidelines with Boost:

```bash
php artisan boost:install
```

Select the Wotzebra guidelines from the list and they'll be installed to `.ai/guidelines/boost-wotz-guidelines/`.

## What's Included

This package provides AI-optimized versions of Laravel & PHP coding standards, including:

- PSR compliance (PSR-1, PSR-2, PSR-12)
- Type declarations and nullable syntax
- Class structure and property promotion
- Control flow patterns (happy path, early returns)
- Laravel conventions (routes, controllers, configuration)
- Naming conventions (camelCase, kebab-case, snake_case)
- Blade templates and validation
- Testing best practices
- A post-generation simplifier skill for cleanup/refinement

## Included Resources

- Core guideline: `resources/boost/guidelines/core.blade.php`
- Skills:
  - `resources/boost/skills/wotz-laravel-php-standards/SKILL.md`
  - `resources/boost/skills/wotz-laravel-php-api/SKILL.md`
  - `resources/boost/skills/wotz-laravel-php-web/SKILL.md`
  - `resources/boost/skills/wotz-laravel-simplifier/SKILL.md`

## Usage

Once installed, AI assistants using Laravel Boost will automatically reference these guidelines when generating code.

## Keeping Guidelines Up to Date

Re-run the Boost installer after updating the package to refresh guidelines:

```bash
composer update wotzebra/boost-wotz-guidelines
php artisan boost:update
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for recent changes.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
