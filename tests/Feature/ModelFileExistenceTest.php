<?php

use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquent\Contracts\ModelManagerContract;
use BonsaiCms\MetamodelEloquent\Exceptions\ModelAlreadyExistsException;

it('creates a model file when a new entity is created', function () {
    $entity = Entity::factory()
        ->create([
            'name' => 'Article',
            'table' => 'articles',
        ]);

    expect(app(ModelManagerContract::class)->modelExists($entity))->toBeTrue();
    $this->assertFileExists(__DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/Models/Article.generated.php');
});

it('deletes the model file when the entity is deleted', function () {
    $entity = tap(Entity::factory()
        ->create([
            'name' => 'Article',
            'table' => 'articles',
        ]))
        ->delete();

    expect(app(ModelManagerContract::class)->modelExists($entity))->toBeFalse();
    $this->assertFileDoesNotExist(__DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/Models/Article.generated.php');
});

it('throws an exception when generating a model for entity which already exists', function () {
    expect(function () {
        $entity = Entity::factory()
            ->create([
                'name' => 'Article',
                'table' => 'articles',
            ]);
        app(ModelManagerContract::class)->generateModel($entity);
    })->toThrow(ModelAlreadyExistsException::class);
});
