<?php

return [
    'generate' => [
        'models' => [
            'folder' => app_path('Models'),
            'fileSuffix' => '.php',
            'namespace' => app()->getNamespace().'Models',
            'parentClass' => \Illuminate\Database\Eloquent\Model::class,
        ],
        'policies' => [
            'folder' => app_path('Policies'),
            'fileSuffix' => 'Policy.php',
            'namespace' => app()->getNamespace().'Policies',
            'parentClass' => null,
            'dependencies' => [
                \App\Models\User::class,
                \Illuminate\Auth\Access\HandlesAuthorization::class,
            ],
        ],
    ],
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
];
