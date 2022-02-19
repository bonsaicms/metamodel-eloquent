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

it('generates oneToMany relationship in the left entity', function () {
    Relationship::factory()
        ->for($this->blueDog, 'leftEntity')
        ->for($this->redCat, 'rightEntity')
        ->create([
            'cardinality' => 'oneToMany',
            'right_foreign_key' => 'blue_dog_id',
            'left_relationship_name' => 'redCats',
            'right_relationship_name' => 'blueDog',
        ]);

    $this->assertFileEquals(
        expected: generated_path('models/BlueDogWithOneToManyRelationship.php'),
        actual: app_path('Models/BlueDog.generated.php')
    );
});

it('generates oneToMany relationship in the right entity', function () {
    Relationship::factory()
        ->for($this->blueDog, 'leftEntity')
        ->for($this->redCat, 'rightEntity')
        ->create([
            'cardinality' => 'oneToMany',
            'right_foreign_key' => 'blue_dog_id',
            'left_relationship_name' => 'redCats',
            'right_relationship_name' => 'blueDog',
        ]);

    $this->assertFileEquals(
        expected: generated_path('models/RedCatWithOneToManyRelationship.php'),
        actual: app_path('Models/RedCat.generated.php')
    );
});
