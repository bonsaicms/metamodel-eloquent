<?php

use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquent\Contracts\ModelManagerContract;
use BonsaiCms\MetamodelEloquent\Exceptions\PolicyAlreadyExistsException;

it('should create a new policy for an entity', function () {
    $entity = Entity::factory()
        ->create([
            'name' => 'Article',
            'table' => 'articles',
        ]);

    $this->assertFileEquals(
        expected: generated_path('policies/BasicPolicy.php'),
        actual: app_path('Policies/ArticlePolicy.generated.php')
    );
});

it('should create a new policy for an entity with parent class', function () {
    config()->set('bonsaicms-metamodel-eloquent.generate.policies.parentClass', \TestApp\Some\Custom\Namespace\SomeParentPolicyClass::class);

    $entity = Entity::factory()
        ->create([
            'name' => 'BlueDog',
            'table' => 'blue_dogs',
        ]);

    $this->assertFileEquals(
        expected: generated_path('policies/PolicyWithParentClass.php'),
        actual: app_path('Policies/BlueDogPolicy.generated.php')
    );
});
