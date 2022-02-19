<?php

namespace BonsaiCms\MetamodelEloquent;

use Illuminate\Support\Facades\Config;
use BonsaiCms\Metamodel\Models\Entity;
use Illuminate\Support\ServiceProvider;
use BonsaiCms\Metamodel\Models\Attribute;
use BonsaiCms\Metamodel\Models\Relationship;
use BonsaiCms\MetamodelEloquent\Console\DeleteModels;
use BonsaiCms\MetamodelEloquent\Console\GenerateModels;
use BonsaiCms\MetamodelEloquent\Observers\EntityObserver;
use BonsaiCms\MetamodelEloquent\Console\RegenerateModels;
use BonsaiCms\MetamodelEloquent\Observers\AttributeObserver;
use BonsaiCms\MetamodelEloquent\Contracts\ModelManagerContract;
use BonsaiCms\MetamodelEloquent\Observers\RelationshipObserver;

class MetamodelEloquentServiceProvider extends ServiceProvider
{
    /**
     * Register package.
     *
     * @return void
     */
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(
            __DIR__.'/../config/bonsaicms-metamodel-eloquent.php',
            'bonsaicms-metamodel-eloquent'
        );

        // Bind implementation
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
        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                DeleteModels::class,
                GenerateModels::class,
                RegenerateModels::class,
            ]);
        }

        // Publish config
        $this->publishes([
            __DIR__.'/../config/bonsaicms-metamodel-eloquent.php' => $this->app->configPath('bonsaicms-metamodel-eloquent.php'),
        ], 'bonsaicms-metamodel-eloquent-config');

        // Publish stubs
        $this->publishes([
            __DIR__.'/../resources/stubs/' => $this->app->resourcePath('stubs/bonsaicms/metamodel-eloquent/'),
        ], 'bonsaicms-metamodel-eloquent-stubs');

        // Observe models
        if (Config::get('bonsaicms-metamodel-eloquent.observeModels.entity.enabled')) {
            Entity::observe(EntityObserver::class);
        }

        if (Config::get('bonsaicms-metamodel-eloquent.observeModels.attribute.enabled')) {
            Attribute::observe(AttributeObserver::class);
        }

        if (Config::get('bonsaicms-metamodel-eloquent.observeModels.relationship.enabled')) {
            Relationship::observe(RelationshipObserver::class);
        }
    }
}
