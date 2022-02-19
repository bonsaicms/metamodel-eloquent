<?php

namespace BonsaiCms\MetamodelEloquent\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function getPackageProviders($app)
    {
        return [
            \BonsaiCms\Metamodel\MetamodelServiceProvider::class,
            \BonsaiCms\MetamodelEloquent\MetamodelEloquentServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'pgsql',
            'url' => null,
            'host' => '127.0.0.1',
            'port' => '5432',
            'database' => 'testing',
            'username' => 'postgres',
            'password' => 'postgres',
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ]);
        config()->set('bonsaicms-metamodel', [
            'entityTableName' => 'pre_met_entities_suf_met',
            'attributeTableName' => 'pre_met_attributes_suf_met',
            'relationshipTableName' => 'pre_met_relationships_suf_met',

            'generatedTablePrefix' => 'pre_gen_',
            'generatedTableSuffix' => '_suf_gen',
        ]);
        config()->set('bonsaicms-metamodel-eloquent', [
            'generate' => [
                'models' => [
                    'folder' => app_path('Models'),
                    'fileSuffix' => '.generated.php',
                    'namespace' => 'TestApp\\Models',
                    'parentClass' => 'Some\\Namespace\\ParentModel',
                ],
                'policies' => [
                    'folder' => app_path('Policies'),
                    'fileSuffix' => 'Policy.generated.php',
                    'namespace' => 'TestApp\\Policies',
                    'parentClass' => null,
                    'dependencies' => [
                        \Some\Another\Class::class,
                        \Test\App\Models\User::class,
                        \Test\Illuminate\Auth\Access\HandlesAuthorization::class,
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
        ]);
    }

    /**
     * This method is called before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->deleteGeneratedFiles();
    }

    /**
     * This method is called after each test.
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->deleteGeneratedFiles();
    }

    protected function deleteGeneratedFiles()
    {
        $paths = [
            app_path('Models/*.generated.php'),
            app_path('Policies/*.generated.php'),
        ];

        foreach($paths as $path) {
            foreach (glob($path) as $file) {
                if(is_file($file)) {
                    unlink($file);
                }
            }
        }
    }
}
