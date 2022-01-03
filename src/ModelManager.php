<?php

namespace BonsaiCms\MetamodelEloquent;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use BonsaiCms\Metamodel\Models\Entity;
use Illuminate\Support\Facades\Config;
use BonsaiCms\MetamodelEloquent\Contracts\ModelManagerContract;
use BonsaiCms\MetamodelEloquent\Exceptions\ModelAlreadyExistsException;

class ModelManager implements ModelManagerContract
{
    public function deleteModel(Entity $entity): self
    {
        File::delete($this->getModelFilePath($entity));

        return $this;
    }

    public function regenerateModel(Entity $entity): self
    {
        $this->deleteModel($entity);

        if ($entity->exists) {
            $this->generateModel($entity);
        }

        return $this;
    }

    public function generateModel(Entity $entity): self
    {
        if ($this->modelExists($entity)) {
            throw new ModelAlreadyExistsException($entity);
        }

        File::put(
            path: $this->getModelFilePath($entity),
            contents: $this->getModelContents($entity)
        );

        return $this;
    }

    function modelExists(Entity $entity): bool
    {
        return File::exists($this->getModelFilePath($entity));
    }

    public function getModelContents(Entity $entity): string
    {
        $stub = new Stub('skeleton');

        // Skeleton
        $stub->namespace = Config::get('bonsaicms-metamodel-eloquent.generate.namespace');
        $stub->parentModelLong = Config::get('bonsaicms-metamodel-eloquent.generate.parentModel');
        $stub->parentModelShort = class_basename($stub->parentModelLong);
        $stub->className = $entity->name;

        // Properties
        $stub->properties = (new Stub('properties', [
            'propertyTable' => ($entity->table === Str::snake(Str::pluralStudly(class_basename($entity->name))))
                ? ''
                : tap(new Stub('propertyTable', [
                    'tableName' => $entity->table,
                ]))->generate(),
        ]))->generate();

        return $this->postProcessModelContents($stub->generate());
    }

    protected function postProcessModelContents(string $content): string
    {
        do {
            $replaced = 0;
            $content = str_replace([
                //
                '    '.PHP_EOL,
                PHP_EOL.PHP_EOL.PHP_EOL,
                '{'.PHP_EOL.'}',
                '{'.PHP_EOL.PHP_EOL.'}',
                PHP_EOL.PHP_EOL.'}'.PHP_EOL,
            ], [
                //
                '',
                PHP_EOL.PHP_EOL,
                '{'.PHP_EOL.'    //'.PHP_EOL.'}',
                '{'.PHP_EOL.'    //'.PHP_EOL.'}',
                PHP_EOL.'}'.PHP_EOL,
            ], $content, $replaced);
        } while ($replaced > 0);

        return $content;
    }

    public function getModelFilePath(Entity $entity): string
    {
        return Config::get('bonsaicms-metamodel-eloquent.generate.folder').
            '/'.$entity->name.
            Config::get('bonsaicms-metamodel-eloquent.generate.modelFileSuffix');
    }

    protected function resolveStubName(Entity $entity): string
    {
        return ($entity->table === Str::snake(Str::pluralStudly(class_basename($entity->name))))
            ? 'modelBasic.stub'
            : 'modelWithTableProperty.stub';
    }
}
