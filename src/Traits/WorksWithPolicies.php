<?php

namespace BonsaiCms\MetamodelEloquent\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use BonsaiCms\MetamodelEloquent\Stub;
use BonsaiCms\Metamodel\Models\Entity;
use Illuminate\Support\Facades\Config;
use BonsaiCms\Support\PhpDependenciesCollection;
use BonsaiCms\MetamodelEloquent\Exceptions\PolicyAlreadyExistsException;

trait WorksWithPolicies
{
    function deletePolicy(Entity $entity): self
    {
        if ($this->policyExists($entity)) {
            File::delete($this->getPolicyFilePath($entity));
        }

        return $this;
    }

    function regeneratePolicy(Entity $entity): self
    {
        $this->deletePolicy($entity);

        if ($entity->exists) {
            $this->generatePolicy($entity);
        }

        return $this;
    }

    /**
     * @throw PolicyAlreadyExistsException
     */
    function generatePolicy(Entity $entity): self
    {
        if ($this->policyExists($entity)) {
            throw new PolicyAlreadyExistsException($entity);
        }

        File::ensureDirectoryExists(
            $this->getPolicyDirectoryPath($entity)
        );

        File::put(
            path: $this->getPolicyFilePath($entity),
            contents: $this->getPolicyContents($entity)
        );

        return $this;
    }

    function policyExists(Entity $entity): bool
    {
        return File::exists(
            $this->getPolicyFilePath($entity)
        );
    }

    function getPolicyDirectoryPath(Entity $entity): string
    {
        return Config::get('bonsaicms-metamodel-eloquent.generate.policies.folder').'/';
    }

    function getPolicyFilePath(Entity $entity): string
    {
        return $this->getPolicyDirectoryPath($entity)
            .$entity->name.
            Config::get('bonsaicms-metamodel-eloquent.generate.policies.fileSuffix');
    }

    function getPolicyContents(Entity $entity): string
    {
        $parentClass = Config::get('bonsaicms-metamodel-eloquent.generate.policies.parentClass');
        $hasParentClass = ($parentClass !== null);

        return Stub::make('policy/policy', [
            // Global variables
            'namespace' => Config::get('bonsaicms-metamodel-eloquent.generate.policies.namespace'),
            'className' => $entity->name,
            'extendsKeyword' => $hasParentClass
                ? ' extends '
                : '',
            'parentClass' => $hasParentClass
                ? class_basename($parentClass)
                : '',
            'modelClassFullName' => '\\' . Config::get('bonsaicms-metamodel-eloquent.generate.models.namespace') . '\\' . $entity->name,
            'modelClassBaseName' => $entity->name,
            'modelVariableName' => Str::camel($entity->name),

            // Dependencies
            'dependencies' => $this->resolvePolicyDependencies($entity),
        ]);
    }

    protected function resolvePolicyDependencies(Entity $entity): string
    {
        $dependencies = new PhpDependenciesCollection(
            Config::get('bonsaicms-metamodel-eloquent.generate.policies.dependencies')
        );

        $dependencies->push(
            Config::get('bonsaicms-metamodel-eloquent.generate.policies.parentClass')
        );

        $dependencies->push(
            Config::get('bonsaicms-metamodel-eloquent.generate.models.namespace') . '\\' . $entity->name
        );

        return $dependencies->toPhpUsesString(
            Config::get('bonsaicms-metamodel-eloquent.generate.policies.namespace')
        );
    }
}
