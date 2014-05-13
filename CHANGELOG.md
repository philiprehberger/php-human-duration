# Changelog

All notable changes to `php-human-duration` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.0] - 2026-03-13

### Added
- Initial release
- `Duration` immutable value object
- Factory methods: `fromSeconds()`, `fromMinutes()`, `fromHours()`, `between()`
- Format methods: `toHuman()`, `toVerbose()`, `toCompact()`
- Accessor methods: `totalSeconds()`, `totalMinutes()`, `totalHours()`
- `Stringable` interface support
- Negative duration support
