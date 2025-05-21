<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Manages application settings.
 */
class SettingsManager
{
    /**
     * Indicates if the settings have been loaded.
     */
    private static bool $loaded = false;

    /**
     * The keys of the settings to load.
     *
     * @var array<int, string>
     */
    protected array $keys = [
        'dhtt_email',
        'dhtt_phone',
        'dhtt_facebook',
        'dhtt_info',
        'dhtt_location',
        'dhtt_google_map',
        'dhtt_organizer_image',
        'dhtt_organizer_url',
    ];

    /**
     * The loaded settings.
     *
     * @var array<string, mixed>
     */
    protected array $settings = [];

    /**
     * Loads the settings from the cache or database.
     *
     * @return array<string, mixed> The loaded settings.
     */
    public function load(): array
    {
        if (! self::$loaded) {
            $this->settings = Cache::rememberForever('app_settings', function (): array {
                /** @var Collection<string, mixed> $settings */
                $settings = Setting::whereIn('key', $this->keys)
                    ->pluck('value', 'key');

                return $settings->toArray();
            });
            self::$loaded = true;
        }

        return $this->settings;
    }

    /**
     * Gets a setting value.
     *
     * @param  string  $key  The setting key.
     * @param  mixed  $default  The default value if the setting is not found.
     * @return mixed The setting value or the default value.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $this->load();

        return $this->settings[$key] ?? $default;
    }

    /**
     * Gets the DHTT email address.
     *
     * @return string|null The DHTT email address.
     */
    public function email(): ?string
    {
        return $this->get('dhtt_email');
    }

    /**
     * Gets the DHTT phone number.
     *
     * @return string|null The DHTT phone number.
     */
    public function phone(): ?string
    {
        return $this->get('dhtt_phone');
    }

    /**
     * Gets the DHTT Facebook URL.
     *
     * @return string|null The DHTT Facebook URL.
     */
    public function facebook(): ?string
    {
        return $this->get('dhtt_facebook');
    }

    /**
     * Gets the DHTT info.
     *
     * @return string|null The DHTT info.
     */
    public function info(): ?string
    {
        return $this->get('dhtt_info');
    }

    /**
     * Gets the DHTT location.
     *
     * @return string|null The DHTT location.
     */
    public function location(): ?string
    {
        return $this->get('dhtt_location');
    }

    /**
     * Gets the DHTT Google Map URL.
     *
     * @return string|null The DHTT Google Map URL.
     */
    public function googleMap(): ?string
    {
        return $this->get('dhtt_google_map');
    }

    /**
     * Gets the DHTT organizer image URL.
     *
     * @return string|null The DHTT organizer image URL.
     */
    public function organizerImage(): ?string
    {
        return $this->get('dhtt_organizer_image');
    }

    /**
     * Clears the settings cache.
     */
    public static function clearCache(): void
    {
        self::$loaded = false;
        Cache::forget('app_settings');
    }

    /**
     * Gets all settings.
     *
     * @return array<string, mixed> All settings.
     */
    public function all(): array
    {
        return $this->load();
    }
}
