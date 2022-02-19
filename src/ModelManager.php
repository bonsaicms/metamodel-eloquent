<?php

namespace BonsaiCms\MetamodelEloquent;

use BonsaiCms\MetamodelEloquent\Traits\WorksWithPolicies;
use BonsaiCms\MetamodelEloquent\Contracts\ModelManagerContract;
use BonsaiCms\MetamodelEloquent\Traits\WorksWithEloquentModels;

class ModelManager implements ModelManagerContract
{
    use WorksWithPolicies;
    use WorksWithEloquentModels;
}
