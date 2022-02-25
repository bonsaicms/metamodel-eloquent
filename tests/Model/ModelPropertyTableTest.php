<?php

use BonsaiCms\Metamodel\Models\Entity;

it('generates a model class', function () {
    Entity::factory()
        ->create([
            'name' => 'Article',
            'table' => 'articles',
        ]);

    $this->assertFileEquals(
        expected: generated_path('models/ArticleWithoutAttributes.php'),
        actual: app_path('Models/Article.generated.php')
    );
});

it('generates a model class with a special plural table name', function () {
    Entity::factory()
        ->create([
            'name' => 'Person',
            'table' => 'people',
        ]);

    $this->assertFileEquals(
        expected: generated_path('models/Person.php'),
        actual: app_path('Models/Person.generated.php')
    );
});

it('generates a model class with a custom table name', function () {
    Entity::factory()
        ->create([
            'name' => 'Page',
            'table' => 'custom_table_name_pages',
        ]);

    $this->assertFileEquals(
        expected: generated_path('models/Page.php'),
        actual: app_path('Models/Page.generated.php')
    );
});
