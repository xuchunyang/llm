<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->loadConfigurationFile();
    }

    /**
     * Load the configuration file.
     *
     * 参考了 https://github.com/beyondcode/expose/blob/5157e767344802c2b45d8ae25828df23694c01e3/app/Providers/AppServiceProvider.php#L41
     */
    protected function loadConfigurationFile(): void
    {
        $builtInConfig = config('llm');

        $configFile = implode(DIRECTORY_SEPARATOR, [
            $_SERVER['HOME'] ?? $_SERVER['USERPROFILE'] ?? __DIR__,
            '.llm',
            'config.php',
        ]);

        if (file_exists($configFile)) {
            $globalConfig = require $configFile;
            config()->set('llm', array_merge($builtInConfig, $globalConfig));
        }
    }
}
