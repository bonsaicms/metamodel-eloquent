<?php

use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\Metamodel\Models\Attribute;

it('should not cast string attributes', function () {
    $entity = Entity::factory()
        ->create([
            'name' => 'Article',
            'table' => 'articles',
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_string_attribute',
            'data_type' => 'string',
        ]);

    $this->assertFileEquals(
        expected: __DIR__.'/../generatedModels/Article.php',
        actual: __DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/Models/Article.generated.php'
    );
});

it('should cast boolean attribute', function () {
    $entity = Entity::factory()
        ->create([
            'name' => 'Article',
            'table' => 'articles',
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_boolean_attribute',
            'data_type' => 'boolean',
        ]);

    $this->assertFileEquals(
        expected: __DIR__.'/../generatedModels/ArticleWithBooleanAttribute.php',
        actual: __DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/Models/Article.generated.php'
    );
});

it('should cast date attribute', function () {
    $entity = Entity::factory()
        ->create([
            'name' => 'Article',
            'table' => 'articles',
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_date_attribute',
            'data_type' => 'date',
        ]);

    $this->assertFileEquals(
        expected: __DIR__.'/../generatedModels/ArticleWithDateAttribute.php',
        actual: __DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/Models/Article.generated.php'
    );
});

it('should cast json attribute', function () {
    $entity = Entity::factory()
        ->create([
            'name' => 'Article',
            'table' => 'articles',
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_json_attribute',
            'data_type' => 'json',
        ]);

    $this->assertFileEquals(
        expected: __DIR__.'/../generatedModels/ArticleWithJsonAttribute.php',
        actual: __DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/Models/Article.generated.php'
    );
});

it('should cast attributes', function () {
    $entity = Entity::factory()
        ->create([
            'name' => 'Article',
            'table' => 'articles',
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_boolean_attribute',
            'data_type' => 'boolean',
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_date_attribute',
            'data_type' => 'date',
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_time_attribute',
            'data_type' => 'time',
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_datetime_attribute',
            'data_type' => 'datetime',
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_json_attribute',
            'data_type' => 'json',
        ]);

    $this->assertFileEquals(
        expected: __DIR__.'/../generatedModels/ArticleWithAttributes.php',
        actual: __DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/Models/Article.generated.php'
    );
});
