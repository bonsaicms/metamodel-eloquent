<?php

namespace BonsaiCms\MetamodelEloquent\Contracts;

use BonsaiCms\Metamodel\Models\Entity;

interface ModelManagerContract
{
    function deleteModel(Entity $entity): self;

    function regenerateModel(Entity $entity): self;

    function generateModel(Entity $entity): self;

    function modelExists(Entity $entity): bool;

    function getModelDirectoryPath(Entity $entity): string;

    function getModelFilePath(Entity $entity): string;

    function getModelContents(Entity $entity): string;
}
