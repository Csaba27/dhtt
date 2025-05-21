<?php

declare(strict_types=1);

namespace App\Facades;

use App\Services\SettingsManager;
use Illuminate\Support\Facades\Facade;

/**
 * Facade for the SettingsManager service.
 *
 * @see SettingsManager
 */
class Settings extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return SettingsManager::class;
    }
}
