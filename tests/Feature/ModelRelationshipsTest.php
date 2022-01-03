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

it('generates oneToOne relationship in the left entity', function () {
    Relationship::factory()
        ->for($this->blueDog, 'leftEntity')
        ->for($this->redCat, 'rightEntity')
        ->create([
            'type' => 'oneToOne',
            'right_foreign_key' => 'blue_dog_id',
            'left_relationship_name' => 'redCat',
            'right_relationship_name' => 'blueDog',
        ]);

    $this->assertFileEquals(
        expected: __DIR__.'/../generatedModels/BlueDogWithOneToOneRelationship.php',
        actual: __DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/Models/BlueDog.generated.php'
    );
});


it('generates oneToOne relationship in the right entity', function () {
    Relationship::factory()
        ->for($this->blueDog, 'leftEntity')
        ->for($this->redCat, 'rightEntity')
        ->create([
            'type' => 'oneToOne',
            'right_foreign_key' => 'blue_dog_id',
            'left_relationship_name' => 'redCat',
            'right_relationship_name' => 'blueDog',
        ]);

    $this->assertFileEquals(
        expected: __DIR__.'/../generatedModels/RedCatWithOneToOneRelationship.php',
        actual: __DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/Models/RedCat.generated.php'
    );
});
