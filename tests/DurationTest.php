<?php

declare(strict_types=1);

namespace PhilipRehberger\HumanDuration\Tests;

use DateTimeImmutable;
use PhilipRehberger\HumanDuration\Duration;
use PHPUnit\Framework\TestCase;
use Stringable;

class DurationTest extends TestCase
{
    public function test_from_seconds(): void
    {
        $duration = Duration::fromSeconds(3665);
        $this->assertSame(3665, $duration->totalSeconds());
    }

    public function test_from_minutes(): void
    {
        $duration = Duration::fromMinutes(2.5);
        $this->assertSame(150, $duration->totalSeconds());
    }

    public function test_from_hours(): void
    {
        $duration = Duration::fromHours(1.5);
        $this->assertSame(5400, $duration->totalSeconds());
    }

    public function test_between_two_dates(): void
    {
        $start = new DateTimeImmutable('2026-01-01 00:00:00');
        $end = new DateTimeImmutable('2026-01-01 01:30:00');

        $duration = Duration::between($start, $end);
        $this->assertSame(5400, $duration->totalSeconds());
    }

    public function test_to_human_zero(): void
    {
        $this->assertSame('0s', Duration::fromSeconds(0)->toHuman());
    }

    public function test_to_human_seconds_only(): void
    {
        $this->assertSame('5s', Duration::fromSeconds(5)->toHuman());
    }

    public function test_to_human_minutes_and_seconds(): void
    {
        $this->assertSame('1m 5s', Duration::fromSeconds(65)->toHuman());
    }

    public function test_to_human_hours_minutes_seconds(): void
    {
        $this->assertSame('1h 1m 5s', Duration::fromSeconds(3665)->toHuman());
    }

    public function test_to_human_days(): void
    {
        $this->assertSame('1d 1h 1m 1s', Duration::fromSeconds(90061)->toHuman());
    }

    public function test_to_verbose_zero(): void
    {
        $this->assertSame('0 seconds', Duration::fromSeconds(0)->toVerbose());
    }

    public function test_to_verbose_singular_units(): void
    {
        $duration = Duration::fromSeconds(90061); // 1d 1h 1m 1s
        $this->assertSame('1 day, 1 hour, 1 minute, 1 second', $duration->toVerbose());
    }

    public function test_to_verbose_plural_units(): void
    {
        $duration = Duration::fromSeconds(180122); // 2d 2h 2m 2s
        $this->assertSame('2 days, 2 hours, 2 minutes, 2 seconds', $duration->toVerbose());
    }

    public function test_to_compact_without_hours(): void
    {
        $this->assertSame('1:05', Duration::fromSeconds(65)->toCompact());
    }

    public function test_to_compact_with_hours(): void
    {
        $this->assertSame('1:01:05', Duration::fromSeconds(3665)->toCompact());
    }

    public function test_negative_duration(): void
    {
        $duration = Duration::fromSeconds(-65);
        $this->assertSame('-1m 5s', $duration->toHuman());
        $this->assertSame('-1 minute, 5 seconds', $duration->toVerbose());
        $this->assertSame('-1:05', $duration->toCompact());
    }

    public function test_total_minutes(): void
    {
        $duration = Duration::fromSeconds(150);
        $this->assertSame(2.5, $duration->totalMinutes());
    }

    public function test_total_hours(): void
    {
        $duration = Duration::fromSeconds(5400);
        $this->assertSame(1.5, $duration->totalHours());
    }

    public function test_stringable_interface(): void
    {
        $duration = Duration::fromSeconds(65);
        $this->assertInstanceOf(Stringable::class, $duration);
        $this->assertSame('1m 5s', (string) $duration);
    }

    public function test_large_values_with_days(): void
    {
        $duration = Duration::fromSeconds(259200); // 3 days exactly
        $this->assertSame('3d', $duration->toHuman());
        $this->assertSame('3 days', $duration->toVerbose());
    }

    public function test_total_days(): void
    {
        $duration = Duration::fromSeconds(129600); // 1.5 days
        $this->assertSame(1.5, $duration->totalDays());
    }

    public function test_total_days_zero(): void
    {
        $this->assertSame(0.0, Duration::fromSeconds(0)->totalDays());
    }

    public function test_is_greater_than(): void
    {
        $longer = Duration::fromSeconds(120);
        $shorter = Duration::fromSeconds(60);

        $this->assertTrue($longer->isGreaterThan($shorter));
        $this->assertFalse($shorter->isGreaterThan($longer));
        $this->assertFalse($longer->isGreaterThan($longer));
    }

    public function test_is_less_than(): void
    {
        $longer = Duration::fromSeconds(120);
        $shorter = Duration::fromSeconds(60);

        $this->assertTrue($shorter->isLessThan($longer));
        $this->assertFalse($longer->isLessThan($shorter));
        $this->assertFalse($shorter->isLessThan($shorter));
    }

    public function test_equals(): void
    {
        $a = Duration::fromSeconds(300);
        $b = Duration::fromMinutes(5);
        $c = Duration::fromSeconds(301);

        $this->assertTrue($a->equals($b));
        $this->assertFalse($a->equals($c));
    }

    public function test_add(): void
    {
        $a = Duration::fromSeconds(60);
        $b = Duration::fromSeconds(90);
        $result = $a->add($b);

        $this->assertSame(150, $result->totalSeconds());
        // Verify immutability — originals unchanged
        $this->assertSame(60, $a->totalSeconds());
        $this->assertSame(90, $b->totalSeconds());
    }

    public function test_subtract(): void
    {
        $a = Duration::fromSeconds(90);
        $b = Duration::fromSeconds(60);
        $result = $a->subtract($b);

        $this->assertSame(30, $result->totalSeconds());
        // Verify immutability — originals unchanged
        $this->assertSame(90, $a->totalSeconds());
        $this->assertSame(60, $b->totalSeconds());
    }

    public function test_subtract_resulting_in_negative(): void
    {
        $a = Duration::fromSeconds(30);
        $b = Duration::fromSeconds(60);
        $result = $a->subtract($b);

        $this->assertSame(-30, $result->totalSeconds());
        $this->assertSame('-30s', $result->toHuman());
    }
}
