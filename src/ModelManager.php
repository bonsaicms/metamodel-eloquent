<?php

namespace BonsaiCms\MetamodelEloquent;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use BonsaiCms\Metamodel\Models\Entity;
use Illuminate\Support\Facades\Config;
use BonsaiCms\Metamodel\Models\Attribute;
use BonsaiCms\MetamodelEloquent\Contracts\ModelManagerContract;
use BonsaiCms\MetamodelEloquent\Exceptions\ModelAlreadyExistsException;

class ModelManager implements ModelManagerContract
{
    // TODO
    const CAST_ATTRIBUTES = [
        'boolean' => 'boolean',
        'date' => 'date',
        'time' => 'time',
        'datetime' => 'datetime',
        'json' => 'array',
    ];

    public function deleteModel(Entity $entity): self
    {
        if ($this->modelExists($entity)) {
            File::delete($this->getModelFilePath($entity));
        }

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
                PHP_EOL.'{'.PHP_EOL.PHP_EOL,
            ], [
                //
                '',
                PHP_EOL.PHP_EOL,
                '{'.PHP_EOL.'    //'.PHP_EOL.'}',
                '{'.PHP_EOL.'    //'.PHP_EOL.'}',
                PHP_EOL.'}'.PHP_EOL,
                PHP_EOL.'{'.PHP_EOL,
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

    public function getModelContents(Entity $entity): string
    {
        $stub = new Stub('model');

        // Global variables
        $stub->namespace = Config::get('bonsaicms-metamodel-eloquent.generate.namespace');
        $stub->parentModelLong = Config::get('bonsaicms-metamodel-eloquent.generate.parentModel');
        $stub->parentModelShort = class_basename($stub->parentModelLong);
        $stub->className = $entity->name;

        // Properties
        $stub->properties = $this->resolveProperties($entity);

        // Methods
        $stub->methods = $this->resolveMethods($entity);

        return $this->postProcessModelContents($stub->generate());
    }

    protected function resolveProperties(Entity $entity): string
    {
        return Stub::make('properties', [
            'propertyTable' => $this->resolvePropertyTable($entity),
            'propertyCasts' => $this->resolvePropertyCasts($entity),
        ]);
    }

    protected function resolvePropertyTable(Entity $entity): string
    {
        return ($entity->realTableName === Str::snake(Str::pluralStudly(class_basename($entity->name))))
            ? ''
            : Stub::make('propertyTable', [
                'tableName' => $entity->realTableName,
            ]);
    }

    protected function resolvePropertyCasts(Entity $entity): string
    {
        $attributesToBeCasted = $entity
            ->attributes
            ->filter(fn ($attribute) => $this->shouldCastAttribute($attribute));

        if ($attributesToBeCasted->isEmpty()) return '';

        return Stub::make('propertyCasts', [
            'casts' =>
                PHP_EOL.
                $attributesToBeCasted->reduce(fn ($carry, $attribute) => $carry.
                    "        '{$attribute->column}' => '{$this->castAttributeTo($attribute)}',".PHP_EOL
                    , '').
                '    '
        ]);
    }

    protected function shouldCastAttribute(Attribute $attribute): bool
    {
        return key_exists($attribute->type, static::CAST_ATTRIBUTES);
    }

    protected function castAttributeTo(Attribute $attribute): string
    {
        return static::CAST_ATTRIBUTES[$attribute->type];
    }

    protected function resolveMethods(Entity $entity): string
    {
        return Stub::make('methods', [
            'methodsRelationships' => $this->resolveMethodsRelationships($entity),
        ]);
    }

    protected function resolveMethodsRelationships(Entity $entity): string
    {
        // TODO
        return '';
    }
}
