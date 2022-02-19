<?php

use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquent\Contracts\ModelManagerContract;
use BonsaiCms\MetamodelEloquent\Exceptions\PolicyAlreadyExistsException;

it('creates a policy file when a new entity is created', function () {
    $entity = Entity::factory()
        ->create([
            'name' => 'Article',
            'table' => 'articles',
        ]);

    expect(app(ModelManagerContract::class)->policyExists($entity))->toBeTrue();
    $this->assertFileExists(app_path('Policies/ArticlePolicy.generated.php'));
});

it('deletes the policy file when the entity is deleted', function () {
    $entity = tap(Entity::factory()
        ->create([
            'name' => 'Article',
            'table' => 'articles',
        ]))
        ->delete();

    expect(app(ModelManagerContract::class)->policyExists($entity))->toBeFalse();
    $this->assertFileDoesNotExist(app_path('Policies/ArticlePolicy.generated.php'));
});

it('throws an exception when generating a policy for entity which already exists', function () {
    expect(function () {
        $entity = Entity::factory()
            ->create([
                'name' => 'Article',
                'table' => 'articles',
            ]);
        app(ModelManagerContract::class)->generatePolicy($entity);
    })->toThrow(PolicyAlreadyExistsException::class);
});
