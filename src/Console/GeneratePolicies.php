<?php

namespace BonsaiCms\MetamodelEloquent\Console;

use Illuminate\Console\Command;
use BonsaiCms\Metamodel\Models\Entity;
use BonsaiCms\MetamodelEloquent\Contracts\ModelManagerContract;

class GeneratePolicies extends Command
{
    protected $signature = 'metamodel:generate-policies';

    protected $description = 'Generate missing policy for all entities';

    public function handle(ModelManagerContract $manager)
    {
        $this->info('Generating missing policies...');

        $this->withProgressBar(Entity::all(), function ($entity) use ($manager) {
            if ( ! $manager->policyExists($entity)) {
                $manager->generatePolicy($entity);
            }
        });
        $this->info('');
    }
}
