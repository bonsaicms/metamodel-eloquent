<?php

use Illuminate\Support\Facades\Schema;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\Metamodel\Models\Attribute;
use BonsaiCms\MetamodelEloquent\Exceptions\ModelAlreadyExistsException;
use BonsaiCms\MetamodelEloquent\Contracts\ModelManagerContract;
use Illuminate\Support\Facades\Config;

it('generates a pure model class when the table name reflects model name', function () {
    Entity::factory()
        ->create([
            'name' => 'Article',
            'table' => 'articles',
        ]);

    $this->assertFileEquals(
        expected: __DIR__.'/../generatedModels/Article.php',
        actual: __DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/Models/Article.php'
    );
});

it('generates a pure model class when the table name reflects model name with special plural', function () {
    Entity::factory()
        ->create([
            'name' => 'Person',
            'table' => 'people',
        ]);

    $this->assertFileEquals(
        expected: __DIR__.'/../generatedModels/Person.php',
        actual: __DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/Models/Person.php'
    );
});

it('generates a model class with custom table name', function () {
    Entity::factory()
        ->create([
            'name' => 'Page',
            'table' => 'custom_table_name_pages',
        ]);

    $this->assertFileEquals(
        expected: __DIR__.'/../generatedModels/Page.php',
        actual: __DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/Models/Page.php'
    );
});
