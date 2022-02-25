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
        expected: generated_path('models/ArticleWithStringAttribute.php'),
        actual: app_path('Models/Article.generated.php')
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
        expected: generated_path('models/ArticleWithBooleanAttribute.php'),
        actual: app_path('Models/Article.generated.php')
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
        expected: generated_path('models/ArticleWithDateAttribute.php'),
        actual: app_path('Models/Article.generated.php')
    );
});

it('should cast json attributes', function () {
    $entity = Entity::factory()
        ->create([
            'name' => 'Article',
            'table' => 'articles',
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_arraylist_attribute',
            'data_type' => 'arraylist',
        ]);

    Attribute::factory()
        ->for($entity)
        ->create([
            'column' => 'some_arrayhash_attribute',
            'data_type' => 'arrayhash',
        ]);

    $this->assertFileEquals(
        expected: generated_path('models/ArticleWithJsonAttributes.php'),
        actual: app_path('Models/Article.generated.php')
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

    $this->assertFileEquals(
        expected: generated_path('models/ArticleWithAttributes.php'),
        actual: app_path('Models/Article.generated.php')
    );
});
