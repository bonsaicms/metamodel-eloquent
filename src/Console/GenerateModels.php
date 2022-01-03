<?php

namespace BonsaiCms\MetamodelEloquent\Console;

use Illuminate\Console\Command;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquent\Contracts\ModelManagerContract;

class GenerateModels extends Command
{
    protected $signature = 'metamodel:generate-models';

    protected $description = 'Generate missing model for all entities';

    public function handle(ModelManagerContract $manager)
    {
        $this->info('Generating missing models...');

        $this->withProgressBar(Entity::all(), function ($entity) use ($manager) {
            if ( ! $manager->modelExists($entity)) {
                $manager->generateModel($entity);
            }
        });
        $this->info('');
    }
}
