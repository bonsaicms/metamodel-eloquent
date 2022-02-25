<?php

namespace BonsaiCms\MetamodelEloquent\Console;

use Illuminate\Console\Command;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquent\Contracts\ModelManagerContract;

class DeletePolicies extends Command
{
    protected $signature = 'metamodel:delete-policies';

    protected $description = 'Delete policy for all entities';

    public function handle(ModelManagerContract $manager)
    {
        $this->info('Deleting policies...');

        $this->withProgressBar(Entity::all(), function ($entity) use ($manager) {
            $manager->deletePolicy($entity);
        });
        $this->info('');
    }
}
