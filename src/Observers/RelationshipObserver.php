<?php

namespace BonsaiCms\MetamodelEloquent\Observers;

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
        $this->manager->regenerateModel($relationship->leftEntity);
        $this->manager->regenerateModel($relationship->rightEntity);
    }

    /**
     * Handle the Relationship "updated" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Relationship  $relationship
     * @return void
     */
    public function updated(Relationship $relationship)
    {
        $this->manager->regenerateModel($relationship->leftEntity);
        $this->manager->regenerateModel($relationship->rightEntity);
    }

    /**
     * Handle the Relationship "deleted" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Relationship  $relationship
     * @return void
     */
    public function deleted(Relationship $relationship)
    {
        $this->manager->regenerateModel($relationship->leftEntity);
        $this->manager->regenerateModel($relationship->rightEntity);
    }

    /**
     * Handle the Relationship "forceDeleted" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Relationship  $relationship
     * @return void
     */
//    public function forceDeleted(Relationship $relationship)
//    {
//        dd("forceDeleted");
//    }
}
