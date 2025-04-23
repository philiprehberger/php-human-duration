# Changelog

All notable changes to `php-human-duration` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and `php-human-duration` adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.2.0] - 2026-03-31

### Added
- Parse from human-readable strings via `Duration::parse()` (e.g., "1h 30m 15s", "2 hours 45 minutes")
- ISO 8601 duration support via `Duration::fromIso()` and `Duration::toIso()`
- Percentage and fraction operations via `percentage()` and `fraction()`

## [1.1.1] - 2026-03-31

### Changed
- Standardize README to 3-badge format with emoji Support section
- Update CI checkout action to v5 for Node.js 24 compatibility
- Add GitHub issue templates, dependabot config, and PR template

## [1.1.0] - 2026-03-22

### Added
- `totalDays()` method for API consistency with totalSeconds/totalMinutes/totalHours
- Comparison methods: `isGreaterThan()`, `isLessThan()`, `equals()`
- Arithmetic methods: `add()` and `subtract()` returning new immutable Duration instances

## [1.0.2] - 2026-03-17

### Changed
- Standardized package metadata, README structure, and CI workflow per package guide

## [1.0.1] - 2026-03-16

### Changed
- Standardize composer.json: add type, homepage, scripts

## [1.0.0] - 2026-03-13

### Added
- Initial release
- `Duration` immutable value object
- Factory methods: `fromSeconds()`, `fromMinutes()`, `fromHours()`, `between()`
- Format methods: `toHuman()`, `toVerbose()`, `toCompact()`
- Accessor methods: `totalSeconds()`, `totalMinutes()`, `totalHours()`
- `Stringable` interface support
- Negative duration support
