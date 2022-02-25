<?php

namespace BonsaiCms\MetamodelEloquent\Console;

use Illuminate\Console\Command;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquent\Contracts\ModelManagerContract;

class RegeneratePolicies extends Command
{
    protected $signature = 'metamodel:regenerate-policies';

    protected $description = 'Regenerate policy for all entities';

    public function handle(ModelManagerContract $manager)
    {
        $this->info('Regenerating policies...');

        $this->withProgressBar(Entity::all(), function ($entity) use ($manager) {
            $manager->regeneratePolicy($entity);
        });
        $this->info('');
    }
}
