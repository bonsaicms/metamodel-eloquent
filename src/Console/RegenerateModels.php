<?php

namespace BonsaiCms\MetamodelEloquent\Console;

use Illuminate\Console\Command;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquent\Contracts\ModelManagerContract;

class RegenerateModels extends Command
{
    protected $signature = 'metamodel:regenerate-models';

    protected $description = 'Regenerate model for all entities';

    public function handle(ModelManagerContract $manager)
    {
        $this->info('Regenerating models...');

        $this->withProgressBar(Entity::all(), function ($entity) use ($manager) {
            $manager->regenerateModel($entity);
        });
        $this->info('');
    }
}
