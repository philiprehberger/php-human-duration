# PHP Human Duration

[![Tests](https://github.com/philiprehberger/php-human-duration/actions/workflows/tests.yml/badge.svg)](https://github.com/philiprehberger/php-human-duration/actions/workflows/tests.yml)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/philiprehberger/php-human-duration.svg)](https://packagist.org/packages/philiprehberger/php-human-duration)
[![Total Downloads](https://img.shields.io/packagist/dt/philiprehberger/php-human-duration.svg)](https://packagist.org/packages/philiprehberger/php-human-duration)
[![PHP Version Require](https://img.shields.io/packagist/php-v/philiprehberger/php-human-duration.svg)](https://packagist.org/packages/philiprehberger/php-human-duration)
[![License](https://img.shields.io/github/license/philiprehberger/php-human-duration)](LICENSE)

Convert seconds into human-readable duration strings.

---

## Requirements

| Dependency | Version |
|------------|---------|
| PHP        | ^8.2    |

---

## Installation

```bash
composer require philiprehberger/php-human-duration
```

---

## Usage

### Creating Durations

```php
use PhilipRehberger\HumanDuration\Duration;

// From seconds
$duration = Duration::fromSeconds(3665);

// From minutes
$duration = Duration::fromMinutes(90);

// From hours
$duration = Duration::fromHours(2.5);

// From the difference between two dates
$duration = Duration::between(
    new DateTimeImmutable('2026-01-01 08:00:00'),
    new DateTimeImmutable('2026-01-01 09:30:00'),
);
```

### Formatting Durations

```php
$duration = Duration::fromSeconds(3665);

$duration->toHuman();   // "1h 1m 5s"
$duration->toVerbose(); // "1 hour, 1 minute, 5 seconds"
$duration->toCompact(); // "1:01:05"

// Stringable — casting to string uses toHuman()
echo $duration; // "1h 1m 5s"
```

### Accessing Raw Values

```php
$duration = Duration::fromSeconds(5400);

$duration->totalSeconds(); // 5400
$duration->totalMinutes(); // 90.0
$duration->totalHours();   // 1.5
```

### Negative Durations

```php
$duration = Duration::fromSeconds(-65);

$duration->toHuman();   // "-1m 5s"
$duration->toVerbose(); // "-1 minute, 5 seconds"
$duration->toCompact(); // "-1:05"
```

---

## API

| Method | Return | Description |
|--------|--------|-------------|
| `Duration::fromSeconds(int $seconds)` | `Duration` | Create from seconds |
| `Duration::fromMinutes(int\|float $minutes)` | `Duration` | Create from minutes |
| `Duration::fromHours(int\|float $hours)` | `Duration` | Create from hours |
| `Duration::between(DateTimeInterface $start, DateTimeInterface $end)` | `Duration` | Create from date difference |
| `toHuman()` | `string` | Compact format: `1h 5m 30s` |
| `toVerbose()` | `string` | Verbose format: `1 hour, 5 minutes, 30 seconds` |
| `toCompact()` | `string` | Clock format: `1:05:30` |
| `totalSeconds()` | `int` | Total seconds |
| `totalMinutes()` | `float` | Total minutes |
| `totalHours()` | `float` | Total hours |

---

## Testing

```bash
composer install
vendor/bin/phpunit
```

Code style:

```bash
vendor/bin/pint
```

Static analysis:

```bash
vendor/bin/phpstan analyse
```

---

## Changelog

Please see [CHANGELOG.md](CHANGELOG.md) for recent changes.

---

## License

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.
