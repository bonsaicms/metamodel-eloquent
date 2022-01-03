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
            'type' => 'string',
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
            'type' => 'boolean',
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
            'type' => 'date',
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
            'type' => 'json',
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
            'type' => 'boolean',
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_date_attribute',
            'type' => 'date',
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_time_attribute',
            'type' => 'time',
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_datetime_attribute',
            'type' => 'datetime',
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_json_attribute',
            'type' => 'json',
        ]);

    $this->assertFileEquals(
        expected: __DIR__.'/../generatedModels/ArticleWithAttributes.php',
        actual: __DIR__.'/../../vendor/orchestra/testbench-core/laravel/app/Models/Article.generated.php'
    );
});
