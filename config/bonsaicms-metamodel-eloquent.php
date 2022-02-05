<?php

return [
    'bind' => [
        'modelManager' => true,
    ],
    'observeModels' => [
        'entity' => true,
        'attribute' => true,
        'relationship' => true,

        // TODO: use this syntax
//        'entity' => [
//            'created' => true,
//            'updated' => true,
//            'deleted' => true,
//        ],
//        'attribute' => [
//            'created' => true,
//            'updated' => true,
//            'deleted' => true,
//        ],
//        'relationship' => [
//            'created' => true,
//            'updated' => true,
//            'deleted' => true,
//        ],
    ],
    'generate' => [
        'folder' => app_path('Models/Generated'),
        'modelFileSuffix' => '.php',
        'namespace' => app()->getNamespace().'Models\\Generated',
        'parentModel' => App\Models\Generated\GeneratedModel::class,
    ],
];
