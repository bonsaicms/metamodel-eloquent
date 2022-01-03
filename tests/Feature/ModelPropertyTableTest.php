<?php

use BonsaiCms\Metamodel\Models\Entity;

it('generates a model class', function () {
    Entity::factory()
        ->create([
            'name' => 'Article',
            'table' => 'articles',
        ]);

    $this->assertFileEquals(
        expected: __DIR__.'/../generatedModels/Article.php',
        actual: __DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/Models/Article.generated.php'
    );
});

it('generates a model class with a special plural table name', function () {
    Entity::factory()
        ->create([
            'name' => 'Person',
            'table' => 'people',
        ]);

    $this->assertFileEquals(
        expected: __DIR__.'/../generatedModels/Person.php',
        actual: __DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/Models/Person.generated.php'
    );
});

it('generates a model class with a custom table name', function () {
    Entity::factory()
        ->create([
            'name' => 'Page',
            'table' => 'custom_table_name_pages',
        ]);

    $this->assertFileEquals(
        expected: __DIR__.'/../generatedModels/Page.php',
        actual: __DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/Models/Page.generated.php'
    );
});
