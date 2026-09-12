<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    use HasFactory;

    protected $table = 'system_settings';

    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    const CACHE_KEY = 'system_settings_all_dict';

    /**
     * Default system configurations
     */
    public static array $defaults = [
        // General
        'system_name' => 'DocPortal',
        'system_tagline' => 'Doctor Prescription & Clinical Management Suite',
        'system_logo' => '',
        'system_favicon' => '',
        'support_email' => 'support@docportal.com',
        'support_phone' => '+91 98765 43210',
        'footer_text' => 'Doctor Prescription & Clinical Management Suite.',
        'developed_by' => 'Hospital & Clinical Administration.',

        // Appearance
        'theme_mode' => 'light',        // light, dark
        'theme_color' => '#0162e8',     // primary hex
        'sidebar_color' => 'dark',      // dark, light, gradient
        'topbar_color' => 'light',      // light, dark

        // Localization
        'timezone' => 'Asia/Kolkata',
        'date_format' => 'd M Y',
        'currency_symbol' => '₹',
        'currency_code' => 'INR',
        'currency_position' => 'before', // before, after

        // Security / Passwords
        'min_password_length' => '8',
        'pwd_require_uppercase' => '1',
        'pwd_require_number' => '1',
        'pwd_require_special' => '0',
        'session_lifetime' => '120',
        'lockout_enabled' => '1',             // 1 = lockout active, 0 = disabled
        'max_login_attempts' => '5',
        'lockout_duration_minutes' => '15',   // minutes account stays locked after max failed attempts
    ];

    /**
     * Boot the model to auto-clear cache on changes.
     */
    protected static function booted()
    {
        static::saved(function () {
            Cache::forget(self::CACHE_KEY);
        });

        static::deleted(function () {
            Cache::forget(self::CACHE_KEY);
        });
    }

    /**
     * Fetch all settings as key-value array with caching
     */
    public static function getAll(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            try {
                $dbSettings = self::all()->pluck('value', 'key')->toArray();
                return array_merge(self::$defaults, $dbSettings);
            } catch (\Throwable $e) {
                return self::$defaults;
            }
        });
    }

    /**
     * Get a setting by key, fallback to default
     */
    public static function get(string $key, $default = null)
    {
        $all = self::getAll();
        if (array_key_exists($key, $all)) {
            return $all[$key];
        }

        return $default ?? (self::$defaults[$key] ?? null);
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, $value, string $group = 'general')
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );

        Cache::forget(self::CACHE_KEY);
        return $setting;
    }

    /**
     * Convert Hex color to RGB string (e.g. #0162e8 -> "1, 98, 232")
     */
    public static function hexToRgb(string $hex): string
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        if (strlen($hex) !== 6) {
            return '1, 98, 232';
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        return "$r, $g, $b";
    }

    /**
     * Get dynamic password validation rules according to security policy
     */
    public static function passwordValidationRules(): array
    {
        $min = (int) self::get('min_password_length', 8);
        $rules = ['required', 'string', 'min:' . $min];

        if (self::get('pwd_require_uppercase', '1') == '1') {
            $rules[] = 'regex:/[A-Z]/';
        }
        if (self::get('pwd_require_number', '1') == '1') {
            $rules[] = 'regex:/[0-9]/';
        }
        if (self::get('pwd_require_special', '0') == '1') {
            $rules[] = 'regex:/[@$!%*#?&]/';
        }

        return $rules;
    }

    /**
     * Get the minimum password length as a Laravel validation rule string.
     * e.g. 'min:8'
     */
    public static function minPasswordRule(): string
    {
        $min = (int) self::get('min_password_length', 8);
        return 'min:' . $min;
    }
}
