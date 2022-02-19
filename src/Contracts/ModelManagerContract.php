<?php

namespace BonsaiCms\MetamodelEloquent\Contracts;

use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquent\Exceptions\ModelAlreadyExistsException;
use BonsaiCms\MetamodelEloquent\Exceptions\PolicyAlreadyExistsException;

interface ModelManagerContract
{
    /*
     * Eloquent models
     */

    function deleteModel(Entity $entity): self;

    function regenerateModel(Entity $entity): self;

    /**
     * @throw ModelAlreadyExistsException
     */
    function generateModel(Entity $entity): self;

    function modelExists(Entity $entity): bool;

    function getModelDirectoryPath(Entity $entity): string;

    function getModelFilePath(Entity $entity): string;

    function getModelContents(Entity $entity): string;

    /*
     * Policies
     */

    function deletePolicy(Entity $entity): self;

    function regeneratePolicy(Entity $entity): self;

    /**
     * @throw PolicyAlreadyExistsException
     */
    function generatePolicy(Entity $entity): self;

    function policyExists(Entity $entity): bool;

    function getPolicyDirectoryPath(Entity $entity): string;

    function getPolicyFilePath(Entity $entity): string;

    function getPolicyContents(Entity $entity): string;
}
