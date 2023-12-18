<?php

declare(strict_types=1);

namespace Trianity\SzamlazzHu\Providers;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;

class PackageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        AboutCommand::add(
            'Szamlazz.Hu SzamlaAgent Package',
            fn () => ['Version' => '9.0.1']
        );

        $this->publishes([
            dirname(__FILE__, 3).'/config/szamlazzhu.php' => base_path('config/szamlazzhu.php'),
            dirname(__FILE__, 3).'/lang' => lang_path('vendor/szamlazzhu'),
        ], 'szamlazzhu-config');

        if ($this->app->runningInConsole()) {
            $this->commands([

            ]);
        }
        $this->loadTranslationsFrom(dirname(__FILE__, 3).'/lang', 'szamlazzhu');
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            dirname(__FILE__, 3).'/config/szamlazzhu.php',
            'szamlazzhu'
        );
    }
}
