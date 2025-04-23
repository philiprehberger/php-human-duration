<?php

declare(strict_types=1);

namespace PhilipRehberger\HumanDuration;

use InvalidArgumentException;

/**
 * Parses human-readable and ISO 8601 duration strings into Duration instances.
 */
final class DurationParser
{
    private const UNIT_MAP = [
        's' => 1,
        'sec' => 1,
        'secs' => 1,
        'second' => 1,
        'seconds' => 1,
        'm' => 60,
        'min' => 60,
        'mins' => 60,
        'minute' => 60,
        'minutes' => 60,
        'h' => 3600,
        'hr' => 3600,
        'hrs' => 3600,
        'hour' => 3600,
        'hours' => 3600,
        'd' => 86400,
        'day' => 86400,
        'days' => 86400,
    ];

    /**
     * Parse a human-readable duration string into a Duration instance.
     *
     * Supported formats:
     * - Shorthand: "1h 30m 15s", "90m", "2h", "45s"
     * - Verbose: "2 hours, 45 minutes", "1 hour 30 minutes 15 seconds"
     * - Decimal: "1.5h", "2.5m"
     * - Days: "1d 2h", "2 days 3 hours"
     */
    public static function parse(string $input): Duration
    {
        $input = trim($input);

        if ($input === '') {
            throw new InvalidArgumentException('Duration string cannot be empty.');
        }

        $pattern = '/(\d+(?:\.\d+)?)\s*([a-zA-Z]+)/';
        $matches = [];

        if (! preg_match_all($pattern, $input, $matches, PREG_SET_ORDER)) {
            throw new InvalidArgumentException("Unable to parse duration string: \"{$input}\"");
        }

        $totalSeconds = 0.0;

        foreach ($matches as $match) {
            $value = (float) $match[1];
            $unit = strtolower($match[2]);

            if (! isset(self::UNIT_MAP[$unit])) {
                throw new InvalidArgumentException("Unknown duration unit: \"{$unit}\"");
            }

            $totalSeconds += $value * self::UNIT_MAP[$unit];
        }

        return Duration::fromSeconds((int) round($totalSeconds));
    }

    /**
     * Parse an ISO 8601 duration string into a Duration instance.
     *
     * Supported formats: "PT1H30M15S", "P1DT2H", "PT90M"
     */
    public static function parseIso(string $iso): Duration
    {
        $iso = trim($iso);

        if (! preg_match('/^P(?:(\d+)D)?(?:T(?:(\d+)H)?(?:(\d+)M)?(?:(\d+)S)?)?$/', $iso, $matches)) {
            throw new InvalidArgumentException("Invalid ISO 8601 duration: \"{$iso}\"");
        }

        $days = ($matches[1] ?? '') !== '' ? (int) $matches[1] : 0;
        $hours = ($matches[2] ?? '') !== '' ? (int) $matches[2] : 0;
        $minutes = ($matches[3] ?? '') !== '' ? (int) $matches[3] : 0;
        $seconds = ($matches[4] ?? '') !== '' ? (int) $matches[4] : 0;

        $totalSeconds = ($days * 86400) + ($hours * 3600) + ($minutes * 60) + $seconds;

        return Duration::fromSeconds($totalSeconds);
    }
}
