<?php

namespace BonsaiCms\MetamodelEloquent;

use BonsaiCms\Support\AbstractStub;

class Stub extends AbstractStub
{
    protected function resolveDefaultStubFilePath(string $stubFileName): string
    {
        return __DIR__.'/../resources/stubs/' . $stubFileName;
    }

    protected function resolveOverriddenStubFilePath(string $stubFileName): string|null
    {
        return resource_path('stubs/bonsaicms/metamodel-eloquent/' . $stubFileName);
    }
}
