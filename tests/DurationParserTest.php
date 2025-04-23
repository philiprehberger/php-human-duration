<?php

declare(strict_types=1);

namespace PhilipRehberger\HumanDuration\Tests;

use InvalidArgumentException;
use PhilipRehberger\HumanDuration\Duration;
use PHPUnit\Framework\TestCase;

class DurationParserTest extends TestCase
{
    public function test_parse_shorthand(): void
    {
        $duration = Duration::parse('1h 30m 15s');
        $this->assertSame(5415, $duration->totalSeconds());
    }

    public function test_parse_verbose(): void
    {
        $duration = Duration::parse('2 hours, 45 minutes');
        $this->assertSame(9900, $duration->totalSeconds());
    }

    public function test_parse_decimal(): void
    {
        $duration = Duration::parse('1.5h');
        $this->assertSame(5400, $duration->totalSeconds());
    }

    public function test_parse_single_unit(): void
    {
        $duration = Duration::parse('90m');
        $this->assertSame(5400, $duration->totalSeconds());
    }

    public function test_parse_with_days(): void
    {
        $duration = Duration::parse('1d 2h');
        $this->assertSame(93600, $duration->totalSeconds());
    }

    public function test_parse_invalid_input_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Duration::parse('not a duration');
    }

    public function test_parse_empty_string_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Duration::parse('');
    }

    public function test_parse_unknown_unit_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Duration::parse('5 widgets');
    }

    public function test_from_iso_full(): void
    {
        $duration = Duration::fromIso('PT1H30M15S');
        $this->assertSame(5415, $duration->totalSeconds());
    }

    public function test_from_iso_with_days(): void
    {
        $duration = Duration::fromIso('P1DT2H');
        $this->assertSame(93600, $duration->totalSeconds());
    }

    public function test_from_iso_minutes_only(): void
    {
        $duration = Duration::fromIso('PT90M');
        $this->assertSame(5400, $duration->totalSeconds());
    }

    public function test_from_iso_invalid_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Duration::fromIso('INVALID');
    }

    public function test_to_iso_full(): void
    {
        $duration = Duration::fromSeconds(5415);
        $this->assertSame('PT1H30M15S', $duration->toIso());
    }

    public function test_to_iso_days_and_hours(): void
    {
        $duration = Duration::fromSeconds(93600);
        $this->assertSame('P1DT2H', $duration->toIso());
    }

    public function test_to_iso_zero(): void
    {
        $duration = Duration::fromSeconds(0);
        $this->assertSame('PT0S', $duration->toIso());
    }

    public function test_to_iso_days_only(): void
    {
        $duration = Duration::fromSeconds(172800);
        $this->assertSame('P2D', $duration->toIso());
    }

    public function test_percentage(): void
    {
        $duration = Duration::fromMinutes(90);
        $result = $duration->percentage(25);
        $this->assertSame(1350, $result->totalSeconds());
    }

    public function test_percentage_preserves_immutability(): void
    {
        $duration = Duration::fromMinutes(90);
        $duration->percentage(25);
        $this->assertSame(5400, $duration->totalSeconds());
    }

    public function test_fraction(): void
    {
        $duration = Duration::fromMinutes(90);
        $result = $duration->fraction(1, 3);
        $this->assertSame(1800, $result->totalSeconds());
    }

    public function test_fraction_preserves_immutability(): void
    {
        $duration = Duration::fromMinutes(90);
        $duration->fraction(1, 3);
        $this->assertSame(5400, $duration->totalSeconds());
    }

    public function test_fraction_zero_denominator_throws_exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Duration::fromSeconds(100)->fraction(1, 0);
    }

    public function test_round_trip_parse_to_human(): void
    {
        $original = Duration::fromSeconds(5415);
        $humanString = $original->toHuman();
        $parsed = Duration::parse($humanString);

        $this->assertTrue($original->equals($parsed));
    }

    public function test_round_trip_iso(): void
    {
        $original = Duration::fromSeconds(93665);
        $iso = $original->toIso();
        $parsed = Duration::fromIso($iso);

        $this->assertTrue($original->equals($parsed));
    }

    public function test_parse_various_unit_aliases(): void
    {
        $this->assertSame(3600, Duration::parse('1hr')->totalSeconds());
        $this->assertSame(3600, Duration::parse('1 hour')->totalSeconds());
        $this->assertSame(7200, Duration::parse('2 hrs')->totalSeconds());
        $this->assertSame(60, Duration::parse('1 min')->totalSeconds());
        $this->assertSame(120, Duration::parse('2 mins')->totalSeconds());
        $this->assertSame(1, Duration::parse('1 sec')->totalSeconds());
        $this->assertSame(2, Duration::parse('2 secs')->totalSeconds());
        $this->assertSame(1, Duration::parse('1 second')->totalSeconds());
        $this->assertSame(86400, Duration::parse('1 day')->totalSeconds());
        $this->assertSame(172800, Duration::parse('2 days')->totalSeconds());
    }

    public function test_parse_decimal_minutes(): void
    {
        $duration = Duration::parse('2.5m');
        $this->assertSame(150, $duration->totalSeconds());
    }
}
