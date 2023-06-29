<?php

declare(strict_types=1);

namespace Cortex\Settings\Bootstrap;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Rinvex\Settings\Collections\SettingCollection;

class LoadSettings
{
    /**
     * Bootstrap the given application.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @throws Exception
     *
     * @return void
     */
    public function bootstrap(Application $app)
    {
        // We will spin through all the setting groups and load them into the repository.
        // This will make all the options available to the developer for use across the whole app.
        $app->instance('rinvex.settings', $settings = new SettingCollection([]));

        // First we will see if we have a cache settings file. If we do, we'll load
        // the setting items from that file so that it is very quick. Otherwise
        // we will need to spin through every setting and load them all.
        $settings->set($appSettings = (file_exists($cached = $app->getCachedSettingsPath()) ? require $cached : self::getAppSettings()->toArray()));

        // Override app settings with tenant settings with the same keys
        $settings->set($tenantSettings = self::getTenantSettings()->toArray());

        // Override config options dynamically on the fly
        collect(array_merge($appSettings, $tenantSettings))->filter(fn ($setting) => $setting['override_config'] === true)->each(fn ($setting) => config()->set($setting['key'], $setting['value']));
    }

    /**
     * Get active tenant settings if any.
     *
     * The tenantable global scope is automatically applied for active tenant.
     *
     * @return array
     */
    public static function getTenantSettings()
    {
        return (app()->has('request.tenant') && app('request.tenant')) ? app('rinvex.settings.setting')->get()->keyBy('key')->toBase() : collect();
    }

    /**
     * Get app settings.
     *
     * This is retrieved without Tenantable global scope, and without any tenants attached.
     *
     * @return array
     */
    public static function getAppSettings()
    {
        return app('rinvex.settings.setting')->withoutGlobalScope('tenantable')->withoutAnyTenants()->get()->keyBy('key')->toBase();
    }
}
