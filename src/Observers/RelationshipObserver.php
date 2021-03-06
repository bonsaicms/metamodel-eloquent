<?php

namespace BonsaiCms\MetamodelEloquent\Observers;

use Illuminate\Support\Facades\Config;
use BonsaiCms\Metamodel\Models\Relationship;
use BonsaiCms\MetamodelEloquent\Contracts\ModelManagerContract;

class RelationshipObserver
{
    public function __construct(
        protected ModelManagerContract $manager
    ) {}

    /**
     * Handle the Relationship "created" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Relationship  $relationship
     * @return void
     */
    public function created(Relationship $relationship)
    {
        if (Config::get('bonsaicms-metamodel-eloquent.observeModels.relationship.model.'.__FUNCTION__)) {
            $this->manager->regenerateModel($relationship->leftEntity);
            $this->manager->regenerateModel($relationship->rightEntity);
        }

        if (Config::get('bonsaicms-metamodel-eloquent.observeModels.relationship.policy.'.__FUNCTION__)) {
            $this->manager->regenerateModel($relationship->leftEntity);
            $this->manager->regenerateModel($relationship->rightEntity);
        }
    }

    /**
     * Handle the Relationship "updated" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Relationship  $relationship
     * @return void
     */
    public function updated(Relationship $relationship)
    {
        if (Config::get('bonsaicms-metamodel-eloquent.observeModels.relationship.model.'.__FUNCTION__)) {
            $this->manager->regenerateModel($relationship->leftEntity);
            $this->manager->regenerateModel($relationship->rightEntity);
        }

        if (Config::get('bonsaicms-metamodel-eloquent.observeModels.relationship.policy.'.__FUNCTION__)) {
            $this->manager->regenerateModel($relationship->leftEntity);
            $this->manager->regenerateModel($relationship->rightEntity);
        }
    }

    /**
     * Handle the Relationship "deleted" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Relationship  $relationship
     * @return void
     */
    public function deleted(Relationship $relationship)
    {
        if (Config::get('bonsaicms-metamodel-eloquent.observeModels.relationship.model.'.__FUNCTION__)) {
            $this->manager->regenerateModel($relationship->leftEntity);
            $this->manager->regenerateModel($relationship->rightEntity);
        }

        if (Config::get('bonsaicms-metamodel-eloquent.observeModels.relationship.policy.'.__FUNCTION__)) {
            $this->manager->regenerateModel($relationship->leftEntity);
            $this->manager->regenerateModel($relationship->rightEntity);
        }
    }
}
