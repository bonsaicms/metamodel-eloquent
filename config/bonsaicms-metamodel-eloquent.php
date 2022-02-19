<?php

return [
    'bind' => [
        'modelManager' => true,
    ],
    'observeModels' => [
        'entity' => [
            'enabled' => true,
            'model' => [
                'created' => true,
                'updated' => true,
                'deleted' => true,
            ],
            'policy' => [
                'created' => true,
                'updated' => true,
                'deleted' => true,
            ],
        ],
        'attribute' => [
            'enabled' => true,
            'model' => [
                'created' => true,
                'updated' => true,
                'deleted' => true,
            ],
            'policy' => [
                'created' => true,
                'updated' => true,
                'deleted' => true,
            ],
        ],
        'relationship' => [
            'enabled' => true,
            'model' => [
                'created' => true,
                'updated' => true,
                'deleted' => true,
            ],
            'policy' => [
                'created' => true,
                'updated' => true,
                'deleted' => true,
            ],
        ],
    ],
    'generate' => [
        'folder' => app_path('Models'),
        'modelFileSuffix' => '.php',
        'namespace' => app()->getNamespace().'Models',
        'parentModel' => \Illuminate\Database\Eloquent\Model::class,
    ],
];
