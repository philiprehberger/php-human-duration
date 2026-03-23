<?php

declare(strict_types=1);

namespace PhilipRehberger\HumanDuration;

use DateTimeInterface;
use Stringable;

/**
 * Immutable duration value object.
 */
final class Duration implements Stringable
{
    private function __construct(private readonly int $seconds) {}

    /**
     * Create a duration from seconds.
     */
    public static function fromSeconds(int $seconds): self
    {
        return new self($seconds);
    }

    /**
     * Create a duration from minutes.
     */
    public static function fromMinutes(int|float $minutes): self
    {
        return new self((int) round($minutes * 60));
    }

    /**
     * Create a duration from hours.
     */
    public static function fromHours(int|float $hours): self
    {
        return new self((int) round($hours * 3600));
    }

    /**
     * Create a duration from the difference between two dates.
     */
    public static function between(DateTimeInterface $start, DateTimeInterface $end): self
    {
        $diff = abs($end->getTimestamp() - $start->getTimestamp());

        return new self($diff);
    }

    /**
     * Format as compact human string: "1h 5m 30s"
     */
    public function toHuman(): string
    {
        if ($this->seconds === 0) {
            return '0s';
        }

        $abs = abs($this->seconds);
        $negative = $this->seconds < 0;
        $parts = [];

        $days = intdiv($abs, 86400);
        $remaining = $abs % 86400;
        $hours = intdiv($remaining, 3600);
        $remaining = $remaining % 3600;
        $minutes = intdiv($remaining, 60);
        $secs = $remaining % 60;

        if ($days > 0) {
            $parts[] = "{$days}d";
        }
        if ($hours > 0) {
            $parts[] = "{$hours}h";
        }
        if ($minutes > 0) {
            $parts[] = "{$minutes}m";
        }
        if ($secs > 0) {
            $parts[] = "{$secs}s";
        }

        $result = implode(' ', $parts);

        return $negative ? "-{$result}" : $result;
    }

    /**
     * Format as verbose string: "1 hour, 5 minutes, 30 seconds"
     */
    public function toVerbose(): string
    {
        if ($this->seconds === 0) {
            return '0 seconds';
        }

        $abs = abs($this->seconds);
        $negative = $this->seconds < 0;
        $parts = [];

        $days = intdiv($abs, 86400);
        $remaining = $abs % 86400;
        $hours = intdiv($remaining, 3600);
        $remaining = $remaining % 3600;
        $minutes = intdiv($remaining, 60);
        $secs = $remaining % 60;

        if ($days > 0) {
            $parts[] = $days === 1 ? '1 day' : "{$days} days";
        }
        if ($hours > 0) {
            $parts[] = $hours === 1 ? '1 hour' : "{$hours} hours";
        }
        if ($minutes > 0) {
            $parts[] = $minutes === 1 ? '1 minute' : "{$minutes} minutes";
        }
        if ($secs > 0) {
            $parts[] = $secs === 1 ? '1 second' : "{$secs} seconds";
        }

        $result = implode(', ', $parts);

        return $negative ? "-{$result}" : $result;
    }

    /**
     * Format as compact clock notation: "1:05:30"
     */
    public function toCompact(): string
    {
        $abs = abs($this->seconds);
        $negative = $this->seconds < 0;

        $hours = intdiv($abs, 3600);
        $remaining = $abs % 3600;
        $minutes = intdiv($remaining, 60);
        $secs = $remaining % 60;

        if ($hours > 0) {
            $result = sprintf('%d:%02d:%02d', $hours, $minutes, $secs);
        } else {
            $result = sprintf('%d:%02d', $minutes, $secs);
        }

        return $negative ? "-{$result}" : $result;
    }

    /**
     * Get total seconds.
     */
    public function totalSeconds(): int
    {
        return $this->seconds;
    }

    /**
     * Get total minutes as float.
     */
    public function totalMinutes(): float
    {
        return $this->seconds / 60;
    }

    /**
     * Get total hours as float.
     */
    public function totalHours(): float
    {
        return $this->seconds / 3600;
    }

    /**
     * Get total days as float.
     */
    public function totalDays(): float
    {
        return $this->seconds / 86400;
    }

    /**
     * Check if this duration is greater than another.
     */
    public function isGreaterThan(Duration $other): bool
    {
        return $this->seconds > $other->seconds;
    }

    /**
     * Check if this duration is less than another.
     */
    public function isLessThan(Duration $other): bool
    {
        return $this->seconds < $other->seconds;
    }

    /**
     * Check if this duration equals another.
     */
    public function equals(Duration $other): bool
    {
        return $this->seconds === $other->seconds;
    }

    /**
     * Add another duration, returning a new instance.
     */
    public function add(Duration $other): self
    {
        return new self($this->seconds + $other->seconds);
    }

    /**
     * Subtract another duration, returning a new instance.
     */
    public function subtract(Duration $other): self
    {
        return new self($this->seconds - $other->seconds);
    }

    public function __toString(): string
    {
        return $this->toHuman();
    }
}
