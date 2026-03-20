# Extra Guidelines and Skills for Laravel Boost

[![Latest Version on Packagist](https://img.shields.io/packagist/v/wotz/laravel-boost-kit.svg?style=flat-square)](https://packagist.org/packages/wotz/laravel-boost-kit)
[![Total Downloads](https://img.shields.io/packagist/dt/wotz/laravel-boost-kit.svg?style=flat-square)](https://packagist.org/packages/wotz/laravel-boost-kit)

This package adds Laravel & PHP coding guidelines and skills used by Who Owns The Zebra to your AI-assisted development workflow with Laravel Boost.

## Installation

Install the package via Composer:

```bash
composer require wotz/laravel-boost-kit --dev
```

Then install the guidelines with Boost:

```bash
php artisan boost:install
```

Select the guidelines and skills from the list and they'll be installed.

To keep the installed guidelines and skills up to date, add the following command to "post-update-cmd" composer script:

```bash
php artisan boost:update
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for recent changes.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
