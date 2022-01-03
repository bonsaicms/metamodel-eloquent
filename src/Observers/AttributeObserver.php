<?php

namespace BonsaiCms\MetamodelEloquent\Observers;

use BonsaiCms\Metamodel\Models\Attribute;
use BonsaiCms\MetamodelEloquent\Contracts\ModelManagerContract;

class AttributeObserver
{
    public function __construct(
        protected ModelManagerContract $manager
    ) {}

    /**
     * Handle the Attribute "created" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Attribute  $attribute
     * @return void
     */
    public function created(Attribute $attribute)
    {
        $this->manager->regenerateModel($attribute->entity);
    }

    /**
     * Handle the Attribute "updated" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Attribute  $attribute
     * @return void
     */
    public function updated(Attribute $attribute)
    {
        $this->manager->regenerateModel($attribute->entity);
    }

    /**
     * Handle the Attribute "deleted" event.
     *
     * @param  \BonsaiCms\Metamodel\Models\Attribute  $attribute
     * @return void
     */
    public function deleted(Attribute $attribute)
    {
        $this->manager->regenerateModel($attribute->entity);
    }
}
