<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type'];

    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();

        if (!$setting) {
            return $default;
        }

        return self::castValue($setting->value, $setting->type);
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, $value, string $type = 'string'): self
    {
        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_bool($value) ? ($value ? '1' : '0') : (string) $value,
                'type' => $type
            ]
        );
    }

    /**
     * Get all settings as array
     */
    public static function getAllSettings(): array
    {
        $settings = self::all();
        $result = [];

        foreach ($settings as $setting) {
            $result[$setting->key] = self::castValue($setting->value, $setting->type);
        }

        return $result;
    }

    /**
     * Save multiple settings at once
     */
    public static function setMany(array $settings): void
    {
        foreach ($settings as $key => $data) {
            if (is_array($data) && isset($data['value'])) {
                self::set($key, $data['value'], $data['type'] ?? 'string');
            } else {
                // Determine type from value
                $type = 'string';
                if (is_bool($data)) {
                    $type = 'boolean';
                } elseif (is_int($data)) {
                    $type = 'integer';
                } elseif (is_float($data)) {
                    $type = 'float';
                }
                self::set($key, $data, $type);
            }
        }
    }

    /**
     * Get default settings
     */
    public static function getDefaults(): array
    {
        return [
            // General
            'system_name' => ['value' => 'UP Management System', 'type' => 'string'],
            'office_name' => ['value' => 'University of the Philippines', 'type' => 'string'],
            'contact_email' => ['value' => '', 'type' => 'string'],
            'timezone' => ['value' => 'Asia/Manila', 'type' => 'string'],
            'date_format' => ['value' => 'M d, Y', 'type' => 'string'],
            'maintenance_mode' => ['value' => false, 'type' => 'boolean'],

            // Internship
            'default_hours' => ['value' => 480, 'type' => 'integer'],
            'work_start' => ['value' => '08:00', 'type' => 'string'],
            'work_end' => ['value' => '17:00', 'type' => 'string'],
            'grace_period' => ['value' => 15, 'type' => 'integer'],
            'overtime_threshold' => ['value' => 8, 'type' => 'float'],
            'auto_approve_intern' => ['value' => false, 'type' => 'boolean'],
            'require_overtime_approval' => ['value' => true, 'type' => 'boolean'],

            // Notifications
            'email_notifications' => ['value' => true, 'type' => 'boolean'],
            'booking_alerts' => ['value' => true, 'type' => 'boolean'],
            'intern_alerts' => ['value' => true, 'type' => 'boolean'],
            'issue_alerts' => ['value' => true, 'type' => 'boolean'],
            'sound_notifications' => ['value' => true, 'type' => 'boolean'],
            'notification_interval' => ['value' => 30, 'type' => 'integer'],

            // Scheduler
            'booking_duration' => ['value' => 2, 'type' => 'integer'],
            'min_advance_booking' => ['value' => 1, 'type' => 'integer'],
            'max_advance_booking' => ['value' => 90, 'type' => 'integer'],
            'auto_approve_booking' => ['value' => false, 'type' => 'boolean'],
            'weekend_bookings' => ['value' => false, 'type' => 'boolean'],
            'booking_start' => ['value' => '08:00', 'type' => 'string'],
            'booking_end' => ['value' => '17:00', 'type' => 'string'],

            // Appearance
            'primary_color' => ['value' => '#7B1D3A', 'type' => 'string'],
            'accent_color' => ['value' => '#FFBF00', 'type' => 'string'],
            'sidebar_style' => ['value' => 'gradient', 'type' => 'string'],
            'compact_mode' => ['value' => false, 'type' => 'boolean'],
            'animations' => ['value' => true, 'type' => 'boolean'],
        ];
    }

    /**
     * Cast value based on type
     */
    protected static function castValue($value, string $type)
    {
        return match($type) {
            'boolean' => (bool) $value && $value !== '0' && $value !== 'false',
            'integer' => (int) $value,
            'float' => (float) $value,
            'array', 'json' => json_decode($value, true),
            default => $value,
        };
    }
}
