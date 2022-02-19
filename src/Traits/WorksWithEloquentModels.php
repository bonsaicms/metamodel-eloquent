<?php

namespace BonsaiCms\MetamodelEloquent\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use BonsaiCms\MetamodelEloquent\Stub;
use BonsaiCms\Metamodel\Models\Entity;
use Illuminate\Support\Facades\Config;
use BonsaiCms\Metamodel\Models\Attribute;
use BonsaiCms\Metamodel\Models\Relationship;
use BonsaiCms\Support\PhpDependenciesCollection;
use BonsaiCms\MetamodelEloquent\Exceptions\ModelAlreadyExistsException;

trait WorksWithEloquentModels
{
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

    /**
     * @throw ModelAlreadyExistsException
     */
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

    public function modelExists(Entity $entity): bool
    {
        return File::exists(
            $this->getModelFilePath($entity)
        );
    }

    public function getModelDirectoryPath(Entity $entity): string
    {
        return Config::get('bonsaicms-metamodel-eloquent.generate.models.folder').'/';
    }

    public function getModelFilePath(Entity $entity): string
    {
        return $this->getModelDirectoryPath($entity)
            .$entity->name.
            Config::get('bonsaicms-metamodel-eloquent.generate.models.fileSuffix');
    }

    public function getModelContents(Entity $entity): string
    {
        return Stub::make('model/model', [
            // Global variables
            'namespace' => Config::get('bonsaicms-metamodel-eloquent.generate.models.namespace'),
            'parentClass' => class_basename(Config::get('bonsaicms-metamodel-eloquent.generate.models.parentClass')),
            'className' => $entity->name,

            // Dependencies
            'dependencies' => $this->resolveModelDependencies($entity),

            // Properties
            'properties' => $this->resolveModelProperties($entity),

            // Methods
            'methods' => $this->resolveModelMethods($entity),
        ]);
    }

    protected function resolveModelDependencies(Entity $entity): string
    {
        $dependencies = new PhpDependenciesCollection;

        $dependencies->push(Config::get('bonsaicms-metamodel-eloquent.generate.models.parentClass'));

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
            Config::get('bonsaicms-metamodel-eloquent.generate.models.namespace')
        );
    }

    protected function resolveModelProperties(Entity $entity): string
    {
        return Stub::make('model/properties', [
            'propertyTable' => $this->resolveModelTableProperty($entity),
            'propertyCasts' => $this->resolveModelCastsProperty($entity),
        ]);
    }

    protected function resolveModelTableProperty(Entity $entity): string
    {
        return ($entity->realTableName === Str::snake(Str::pluralStudly(class_basename($entity->name))))
            ? ''
            : Stub::make('model/propertyTable', [
                'tableName' => $entity->realTableName,
            ]);
    }

    protected function resolveModelCastsProperty(Entity $entity): string
    {
        $attributesToBeCasted = $entity
            ->attributes
            ->filter(fn ($attribute) => $this->shouldCastModelAttribute($attribute))
            ->sort();

        if ($attributesToBeCasted->isEmpty()) return '';

        return Stub::make('model/propertyCasts', [
            'casts' => $attributesToBeCasted
                ->map(function (Attribute $attribute) {
                    return "'{$attribute->column}' => {$this->castModelAttributeTo($attribute)},";
                })
                ->join(PHP_EOL)
        ]);
    }

    protected function shouldCastModelAttribute(Attribute $attribute): bool
    {
        return ($this->castModelAttributeTo($attribute) !== null);
    }

    protected function castModelAttributeTo(Attribute $attribute): ?string
    {
        return [
            'boolean' => "'boolean'",
            'date' => "'date'",
            'time' => "'time'",
            'datetime' => "'datetime'",
            'arraylist' => 'AsCollection::class',
            'arrayhash' => 'AsArrayObject::class',
        ][$attribute->data_type] ?? null;

    }

    protected function resolveModelMethods(Entity $entity): string
    {
        return Stub::make('model/methods', [
            'methodsRelationships' => $this->resolveModelRelationshipMethods($entity),
        ]);
    }

    protected function resolveModelRelationshipMethods(Entity $entity): string
    {
        $resolvedRelationshipMethods = new Collection;

        foreach ($entity->leftRelationships as $leftRelationship) {
            $resolvedRelationshipMethods->push(
                $this->resolveModelLeftRelationship($leftRelationship)
            );
        }

        foreach ($entity->rightRelationships as $rightRelationship) {
            $resolvedRelationshipMethods->push(
                $this->resolveModelRightRelationship($rightRelationship)
            );
        }

        return $this
            ->sortResolvedModelRelationshipMethods($resolvedRelationshipMethods)
            ->pluck('stub')
            ->join(PHP_EOL);
    }

    protected function sortResolvedModelRelationshipMethods(Collection $methods): Collection
    {
        // TODO: implement some sort

        return $methods;
    }

    protected function resolveModelLeftRelationship(Relationship $relationship): array
    {
        $stub = '';

        if ($relationship->cardinality === 'oneToOne') {
            $stub = Stub::make('model/relationshipHasOne', [
                'foreignEntity' => $relationship->rightEntity->name,
                'method' => $relationship->left_relationship_name,
                'foreignKey' => $relationship->right_foreign_key,
            ]);
        }

        if ($relationship->cardinality === 'oneToMany') {
            $stub = Stub::make('model/relationshipHasMany', [
                'foreignEntity' => $relationship->rightEntity->name,
                'method' => $relationship->left_relationship_name,
                'foreignKey' => $relationship->right_foreign_key,
            ]);
        }

        if ($relationship->cardinality === 'manyToMany') {
            $stub = Stub::make('model/relationshipBelongsToMany', [
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

    protected function resolveModelRightRelationship(Relationship $relationship): array
    {
        $stub = '';

        if (in_array($relationship->cardinality, ['oneToOne', 'oneToMany'])) {
            $stub = Stub::make('model/relationshipBelongsTo', [
                'foreignEntity' => $relationship->leftEntity->name,
                'method' => $relationship->right_relationship_name,
                'foreignKey' => $relationship->right_foreign_key,
            ]);
        }

        if ($relationship->cardinality === 'manyToMany') {
            $stub = Stub::make('model/relationshipBelongsToMany', [
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
