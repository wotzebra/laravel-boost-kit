# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.4.0] - 2026-03-20

### Added
- Refactored guidelines and skills

## [0.3.1] - 2026-03-20

### Added
- Add agent-browser skill

### Fixed
- Folder name of userback skill

## [0.3.0] - 2026-02-20

### Added
- Add Userback skill

## [0.2.0] - 2026-02-20

### Changed
- Renamed package to `wotz/laravel-boost-kit`
- Updated PHP namespace from `Wotzebra\BoostWotzGuidelines` to `Wotz\LaravelBoostKit`
- Renamed service provider to `LaravelBoostKitServiceProvider`

### Changed
- Updated PHP constraint from `^8.2` to `^8.4` for PHP 8.4 and 8.5 support
- Updated `illuminate/support` constraint from `^10.0|^11.0|^12.0` to `^12.0|^13.0` for Laravel 13 support
- Added GitHub Actions CI workflow with PHP 8.4/8.5 and Laravel 12/13 matrix

## [0.2.0] - 2026-02-20

### Changed
- Renamed package to `wotz/laravel-boost-kit`
- Updated PHP namespace from `Wotzebra\BoostWotzGuidelines` to `Wotz\LaravelBoostKit`
- Renamed service provider to `LaravelBoostKitServiceProvider`

## [0.1.1] - 2026-02-19

### Added
- Added the `wotz-laravel-simplifier` Boost skill at `resources/boost/skills/wotz-laravel-simplifier/SKILL.md`
- Added guideline routing in `resources/boost/guidelines/core.blade.php` to activate the simplifier skill for cleanup/refinement tasks

## [0.1.0] - 2026-02-19

### Added
- Initial release of `boost-wotz-guidelines`
- Laravel and PHP coding guidelines optimized for AI assistants
