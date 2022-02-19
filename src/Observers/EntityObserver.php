<?php

namespace BonsaiCms\MetamodelEloquent\Observers;

use Illuminate\Support\Facades\Config;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquent\Contracts\ModelManagerContract;

class EntityObserver
{
    public function __construct(
        protected ModelManagerContract $manager
    ) {}

    /**
     * Handle the Entity "created" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Entity  $entity
     * @return void
     */
    public function created(Entity $entity)
    {
        if (Config::get('bonsaicms-metamodel-eloquent.observeModels.entity.model.'.__FUNCTION__)) {
            $this->manager->regenerateModel($entity);
        }

        if (Config::get('bonsaicms-metamodel-eloquent.observeModels.entity.policy.'.__FUNCTION__)) {
            $this->manager->regeneratePolicy($entity);
        }
    }

    /**
     * Handle the Entity "updated" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Entity  $entity
     * @return void
     */
    public function updated(Entity $entity)
    {
        if (Config::get('bonsaicms-metamodel-eloquent.observeModels.entity.model.'.__FUNCTION__)) {
            $this->manager->regenerateModel($entity);
        }

        if (Config::get('bonsaicms-metamodel-eloquent.observeModels.entity.policy.'.__FUNCTION__)) {
            $this->manager->regeneratePolicy($entity);
        }
    }

    /**
     * Handle the Entity "deleted" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Entity  $entity
     * @return void
     */
    public function deleted(Entity $entity)
    {
        if (Config::get('bonsaicms-metamodel-eloquent.observeModels.entity.model.'.__FUNCTION__)) {
            $this->manager->regenerateModel($entity);
        }

        if (Config::get('bonsaicms-metamodel-eloquent.observeModels.entity.policy.'.__FUNCTION__)) {
            $this->manager->regeneratePolicy($entity);
        }
    }
}
