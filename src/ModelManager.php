<?php

namespace BonsaiCms\MetamodelEloquent;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use BonsaiCms\Metamodel\Models\Entity;
use Illuminate\Support\Facades\Config;
use BonsaiCms\Metamodel\Models\Attribute;
use BonsaiCms\Metamodel\Models\Relationship;
use BonsaiCms\Support\PhpDependenciesCollection;
use BonsaiCms\MetamodelEloquent\Contracts\ModelManagerContract;
use BonsaiCms\MetamodelEloquent\Exceptions\ModelAlreadyExistsException;

class ModelManager implements ModelManagerContract
{
    const CAST_ATTRIBUTES = [
        'boolean' => "'boolean'",
        'date' => "'date'",
        'time' => "'time'",
        'datetime' => "'datetime'",
        'arraylist' => 'AsCollection::class',
        'arrayhash' => 'AsArrayObject::class',
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

        File::ensureDirectoryExists(
            $this->getModelDirectoryPath($entity)
        );

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

    public function getModelDirectoryPath(Entity $entity): string
    {
        return Config::get('bonsaicms-metamodel-eloquent.generate.folder').'/';
    }

    public function getModelFilePath(Entity $entity): string
    {
        return $this->getModelDirectoryPath($entity)
            .$entity->name.
            Config::get('bonsaicms-metamodel-eloquent.generate.modelFileSuffix');
    }

    public function getModelContents(Entity $entity): string
    {
        $stub = new Stub('model');

        // Global variables
        $stub->namespace = Config::get('bonsaicms-metamodel-eloquent.generate.namespace');
        $stub->parentModel = class_basename(Config::get('bonsaicms-metamodel-eloquent.generate.parentModel'));
        $stub->className = $entity->name;

        // Dependencies
        $stub->dependencies = $this->resolveDependencies($entity);

        // Properties
        $stub->properties = $this->resolveProperties($entity);

        // Methods
        $stub->methods = $this->resolveMethods($entity);

        return $stub->generate();
    }

    protected function resolveDependencies(Entity $entity): string
    {
        $dependencies = new PhpDependenciesCollection;

        $dependencies->push(Config::get('bonsaicms-metamodel-eloquent.generate.parentModel'));

        foreach ($entity->leftRelationships as $leftRelationship) {
            if ($leftRelationship->cardinality === 'oneToOne') {
                $dependencies->push(\Illuminate\Database\Eloquent\Relations\HasOne::class);
            }
            if ($leftRelationship->cardinality === 'oneToMany') {
                $dependencies->push(\Illuminate\Database\Eloquent\Relations\HasMany::class);
            }
            if ($leftRelationship->cardinality === 'manyToMany') {
                $dependencies->push(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class);
            }
        }

        foreach ($entity->rightRelationships as $rightRelationship) {
            if (in_array($rightRelationship->cardinality, ['oneToOne', 'oneToMany'])) {
                $dependencies->push(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
            }
            if ($rightRelationship->cardinality === 'manyToMany') {
                $dependencies->push(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class);
            }
        }

        if ($entity->attributes->some(static fn (Attribute $attribute) => ($attribute->data_type === 'arraylist'))) {
            $dependencies->push(\Illuminate\Database\Eloquent\Casts\AsCollection::class);
        }

        if ($entity->attributes->some(static fn (Attribute $attribute) => ($attribute->data_type === 'arrayhash'))) {
            $dependencies->push(\Illuminate\Database\Eloquent\Casts\AsArrayObject::class);
        }

        return $dependencies->toPhpUsesString(
            Config::get('bonsaicms-metamodel-eloquent.generate.namespace')
        );
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
            ->filter(fn ($attribute) => $this->shouldCastAttribute($attribute))
            ->sort();

        if ($attributesToBeCasted->isEmpty()) return '';

        return Stub::make('propertyCasts', [
            'casts' => $attributesToBeCasted
                ->map(function (Attribute $attribute) {
                    return "'{$attribute->column}' => {$this->castAttributeTo($attribute)},";
                })
                ->join(PHP_EOL)
        ]);
    }

    protected function shouldCastAttribute(Attribute $attribute): bool
    {
        return key_exists($attribute->data_type, static::CAST_ATTRIBUTES);
    }

    protected function castAttributeTo(Attribute $attribute): string
    {
        return static::CAST_ATTRIBUTES[$attribute->data_type];
    }

    protected function resolveMethods(Entity $entity): string
    {
        return Stub::make('methods', [
            'methodsRelationships' => $this->resolveMethodsRelationships($entity),
        ]);
    }

    protected function resolveMethodsRelationships(Entity $entity): string
    {
        $resolvedRelationshipMethods = new Collection;

        foreach ($entity->leftRelationships as $leftRelationship) {
            $resolvedRelationshipMethods->push(
                $this->resolveLeftRelationship($leftRelationship)
            );
        }

        foreach ($entity->rightRelationships as $rightRelationship) {
            $resolvedRelationshipMethods->push(
                $this->resolveRightRelationship($rightRelationship)
            );
        }

        return $this
            ->sortResolvedRelationshipMethods($resolvedRelationshipMethods)
            ->pluck('stub')
            ->join(PHP_EOL);
    }

    protected function sortResolvedRelationshipMethods(Collection $methods): Collection
    {
        // TODO: implement some sort

        return $methods;
    }

    protected function resolveLeftRelationship(Relationship $relationship): array
    {
        $stub = '';

        if ($relationship->cardinality === 'oneToOne') {
            $stub = Stub::make('relationshipHasOne', [
                'foreignEntity' => $relationship->rightEntity->name,
                'method' => $relationship->left_relationship_name,
                'foreignKey' => $relationship->right_foreign_key,
            ]);
        }

        if ($relationship->cardinality === 'oneToMany') {
            $stub = Stub::make('relationshipHasMany', [
                'foreignEntity' => $relationship->rightEntity->name,
                'method' => $relationship->left_relationship_name,
                'foreignKey' => $relationship->right_foreign_key,
            ]);
        }

        if ($relationship->cardinality === 'manyToMany') {
            $stub = Stub::make('relationshipBelongsToMany', [
                'foreignEntity' => $relationship->rightEntity->name,
                'method' => $relationship->left_relationship_name,
                'pivotTable' => $relationship->realPivotTableName,
                'foreignPivotKey' => $relationship->left_foreign_key,
                'relatedPivotKey' => $relationship->right_foreign_key,
            ]);
        }

        return [
            'direction' => 'left',
            'relationship' => $relationship,
            'stub' => $stub,
        ];
    }

    protected function resolveRightRelationship(Relationship $relationship): array
    {
        $stub = '';

        if (in_array($relationship->cardinality, ['oneToOne', 'oneToMany'])) {
            $stub = Stub::make('relationshipBelongsTo', [
                'foreignEntity' => $relationship->leftEntity->name,
                'method' => $relationship->right_relationship_name,
                'foreignKey' => $relationship->right_foreign_key,
            ]);
        }

        if ($relationship->cardinality === 'manyToMany') {
            $stub = Stub::make('relationshipBelongsToMany', [
                'foreignEntity' => $relationship->leftEntity->name,
                'method' => $relationship->right_relationship_name,
                'pivotTable' => $relationship->realPivotTableName,
                'foreignPivotKey' => $relationship->right_foreign_key,
                'relatedPivotKey' => $relationship->left_foreign_key,
            ]);
        }

        return [
            'direction' => 'right',
            'relationship' => $relationship,
            'stub' => $stub,
        ];
    }
}
