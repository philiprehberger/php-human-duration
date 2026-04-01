# PHP Human Duration

[![Tests](https://github.com/philiprehberger/php-human-duration/actions/workflows/tests.yml/badge.svg)](https://github.com/philiprehberger/php-human-duration/actions/workflows/tests.yml)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/philiprehberger/php-human-duration.svg)](https://packagist.org/packages/philiprehberger/php-human-duration)
[![Last updated](https://img.shields.io/github/last-commit/philiprehberger/php-human-duration)](https://github.com/philiprehberger/php-human-duration/commits/main)

Convert seconds into human-readable duration strings.

## Requirements

- PHP 8.2+

## Installation

```bash
composer require philiprehberger/php-human-duration
```

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

### Parsing Durations

```php
use PhilipRehberger\HumanDuration\Duration;

// Shorthand
$duration = Duration::parse('1h 30m 15s'); // 5415 seconds

// Verbose
$duration = Duration::parse('2 hours, 45 minutes'); // 9900 seconds

// Decimal
$duration = Duration::parse('1.5h'); // 5400 seconds

// With days
$duration = Duration::parse('1d 2h'); // 93600 seconds

// ISO 8601
$duration = Duration::fromIso('PT1H30M15S'); // 5415 seconds
$duration = Duration::fromIso('P1DT2H');     // 93600 seconds

// Convert to ISO 8601
$duration->toIso(); // "PT1H30M15S"
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

### Comparing and Combining Durations

```php
$a = Duration::fromMinutes(90);
$b = Duration::fromHours(1);

$a->isGreaterThan($b); // true
$a->isLessThan($b);    // false
$a->equals($b);        // false

$a->add($b)->toHuman();      // "2h 30m"
$a->subtract($b)->toHuman(); // "30m"
```

### Proportional Calculations

```php
$duration = Duration::fromMinutes(90);

// Get 25% of a duration
$quarter = $duration->percentage(25);
$quarter->totalSeconds(); // 1350 (22.5 minutes)

// Get a fraction of a duration
$third = $duration->fraction(1, 3);
$third->totalSeconds(); // 1800 (30 minutes)
```

### Negative Durations

```php
$duration = Duration::fromSeconds(-65);

$duration->toHuman();   // "-1m 5s"
$duration->toVerbose(); // "-1 minute, 5 seconds"
$duration->toCompact(); // "-1:05"
```

## API

| Method | Return | Description |
|--------|--------|-------------|
| `Duration::fromSeconds(int $seconds)` | `Duration` | Create from seconds |
| `Duration::fromMinutes(int\|float $minutes)` | `Duration` | Create from minutes |
| `Duration::fromHours(int\|float $hours)` | `Duration` | Create from hours |
| `Duration::between(DateTimeInterface $start, DateTimeInterface $end)` | `Duration` | Create from date difference |
| `Duration::parse(string $input)` | `Duration` | Parse human-readable string (e.g., "1h 30m", "2 hours") |
| `Duration::fromIso(string $iso)` | `Duration` | Parse ISO 8601 duration (e.g., "PT1H30M") |
| `toHuman()` | `string` | Compact format: `1h 5m 30s` |
| `toVerbose()` | `string` | Verbose format: `1 hour, 5 minutes, 30 seconds` |
| `toCompact()` | `string` | Clock format: `1:05:30` |
| `toIso()` | `string` | ISO 8601 format: `PT1H5M30S` |
| `totalSeconds()` | `int` | Total seconds |
| `totalMinutes()` | `float` | Total minutes |
| `totalHours()` | `float` | Total hours |
| `totalDays()` | `float` | Total days |
| `isGreaterThan(Duration $other)` | `bool` | Check if greater than another duration |
| `isLessThan(Duration $other)` | `bool` | Check if less than another duration |
| `equals(Duration $other)` | `bool` | Check if equal to another duration |
| `add(Duration $other)` | `Duration` | Add two durations, returning a new instance |
| `subtract(Duration $other)` | `Duration` | Subtract a duration, returning a new instance |
| `percentage(float $percent)` | `Duration` | Return given percentage of this duration |
| `fraction(int $numerator, int $denominator)` | `Duration` | Return proportional fraction of this duration |

## Development

```bash
composer install
vendor/bin/phpunit
vendor/bin/pint --test
vendor/bin/phpstan analyse
```

## Support

If you find this project useful:

⭐ [Star the repo](https://github.com/philiprehberger/php-human-duration)

🐛 [Report issues](https://github.com/philiprehberger/php-human-duration/issues?q=is%3Aissue+is%3Aopen+label%3Abug)

💡 [Suggest features](https://github.com/philiprehberger/php-human-duration/issues?q=is%3Aissue+is%3Aopen+label%3Aenhancement)

❤️ [Sponsor development](https://github.com/sponsors/philiprehberger)

🌐 [All Open Source Projects](https://philiprehberger.com/open-source-packages)

💻 [GitHub Profile](https://github.com/philiprehberger)

🔗 [LinkedIn Profile](https://www.linkedin.com/in/philiprehberger)

## License

[MIT](LICENSE)
