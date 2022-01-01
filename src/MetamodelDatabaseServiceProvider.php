<?php

namespace BonsaiCms\MetamodelEloquent;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use BonsaiCms\MetamodelDatabase\Contracts\ModelManagerContract;

class MetamodelEloquentServiceProvider extends ServiceProvider
{
    /**
     * Register package.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/bonsaicms-metamodel-eloquent.php', 'bonsaicms-metamodel-eloquent'
        );

        if (Config::get('bonsaicms-metamodel-eloquent.bind.modelManager')) {
            $this->app->singleton(ModelManagerContract::class, ModelManager::class);
        }
    }

    /**
     * Bootstrap package.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/bonsaicms-metamodel-eloquent.php' => $this->app->configPath('bonsaicms-metamodel-eloquent.php'),
        ], 'bonsaicms-metamodel-eloquent-config');
    }
}
