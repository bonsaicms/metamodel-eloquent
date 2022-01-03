<?php

namespace BonsaiCms\MetamodelEloquent\Console;

use Illuminate\Console\Command;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquent\Contracts\ModelManagerContract;

class DeleteModels extends Command
{
    protected $signature = 'metamodel:delete-models';

    protected $description = 'Delete model for all entities';

    public function handle(ModelManagerContract $manager)
    {
        $this->info('Deleting models...');

        $this->withProgressBar(Entity::all(), function ($entity) use ($manager) {
            $manager->deleteModel($entity);
        });
        $this->info('');
    }
}
