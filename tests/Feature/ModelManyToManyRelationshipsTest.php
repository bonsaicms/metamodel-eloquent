<?php

use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\Metamodel\Models\Relationship;

beforeEach(function () {
    $this->blueDog = Entity::factory()
        ->create([
            'name' => 'BlueDog',
            'table' => 'blue_dog_table',
        ]);

    $this->redCat = Entity::factory()
        ->create([
            'name' => 'RedCat',
            'table' => 'red_cat_table',
        ]);
});

it('generates manyToMany relationship in the left entity', function () {
    Relationship::factory()
        ->for($this->blueDog, 'leftEntity')
        ->for($this->redCat, 'rightEntity')
        ->create([
            'type' => 'manyToMany',
            'pivot_table' => 'blue_dog_red_cat_pivot_table',
            'left_foreign_key' => 'blue_dog_id',
            'right_foreign_key' => 'red_cat_id',
            'left_relationship_name' => 'redCats',
            'right_relationship_name' => 'blueDogs',
        ]);

    $this->assertFileEquals(
        expected: __DIR__.'/../generatedModels/BlueDogWithManyToManyRelationship.php',
        actual: __DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/Models/BlueDog.generated.php'
    );
});

it('generates manyToMany relationship in the right entity', function () {
    Relationship::factory()
        ->for($this->blueDog, 'leftEntity')
        ->for($this->redCat, 'rightEntity')
        ->create([
            'type' => 'manyToMany',
            'pivot_table' => 'blue_dog_red_cat_pivot_table',
            'left_foreign_key' => 'blue_dog_id',
            'right_foreign_key' => 'red_cat_id',
            'left_relationship_name' => 'redCats',
            'right_relationship_name' => 'blueDogs',
        ]);

    $this->assertFileEquals(
        expected: __DIR__.'/../generatedModels/RedCatWithManyToManyRelationship.php',
        actual: __DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/Models/RedCat.generated.php'
    );
});
