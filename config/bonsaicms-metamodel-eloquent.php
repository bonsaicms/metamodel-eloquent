<?php

return [
    'bind' => [
        'modelManager' => true,
    ],
    'observeModels' => [
        'entity' => true,
        'attribute' => true,
        'relationship' => true,
    ],
    'generate' => [
        'folder' => app_path('Models'),
        'modelFileSuffix' => '.php',
        'namespace' => app()->getNamespace().'Models',
        'parentModel' => Illuminate\Database\Eloquent\Model::class,
    ],
];
