<?php

namespace App\Support;

use App\Models\SystemSetting;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Schema;

class EventDates
{
    protected const KEYS = [
        'registration_open_at',
        'early_bird_deadline_at',
        'registration_close_at',
        'event_start_at',
        'event_end_at',
    ];

    protected static ?array $cache = null;

    public static function get(string $key): CarbonInterface
    {
        $value = static::values()[$key] ?? static::defaultValues()[$key] ?? null;

        if (! $value) {
            throw new \InvalidArgumentException("Unknown event date key [{$key}]");
        }

        return Carbon::parse($value);
    }

    public static function value(string $key): string
    {
        return static::get($key)->toDateTimeString();
    }

    public static function earlyBirdActive(): bool
    {
        return now()->lt(static::get('early_bird_deadline_at'));
    }

    public static function registrationOpenAt(): CarbonInterface
    {
        return static::get('registration_open_at');
    }

    public static function registrationCloseAt(): CarbonInterface
    {
        return static::get('registration_close_at');
    }

    public static function registrationWindowOpen(): bool
    {
        $now = now();

        return $now->greaterThanOrEqualTo(static::registrationOpenAt())
            && $now->lessThanOrEqualTo(static::registrationCloseAt());
    }

    protected static function values(): array
    {
        if (static::$cache !== null) {
            return static::$cache;
        }

        $values = static::defaultValues();

        if (Schema::hasTable('system_settings')) {
            $rows = SystemSetting::whereIn('key', static::KEYS)
                ->pluck('value', 'key')
                ->toArray();

            foreach ($rows as $key => $value) {
                if (is_string($value) && trim($value) !== '') {
                    $values[$key] = $value;
                }
            }
        }

        static::$cache = $values;

        return static::$cache;
    }

    public static function keys(): array
    {
        return static::KEYS;
    }

    protected static function defaultValues(): array
    {
        $now = now();

        return [
            'registration_open_at' => $now->copy()->startOfDay()->toDateTimeString(),
            'early_bird_deadline_at' => $now->copy()->addMonth()->endOfDay()->toDateTimeString(),
            'registration_close_at' => $now->copy()->addMonths(6)->endOfDay()->toDateTimeString(),
            'event_start_at' => $now->copy()->addMonths(7)->setTime(8, 0, 0)->toDateTimeString(),
            'event_end_at' => $now->copy()->addMonths(7)->addDays(3)->setTime(16, 0, 0)->toDateTimeString(),
        ];
    }
}
