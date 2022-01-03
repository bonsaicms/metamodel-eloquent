<?php

namespace BonsaiCms\MetamodelEloquent;

use Illuminate\Support\Facades\File;

class Stub
{
    const STUB_SUFFIX = '.stub';
    const STUB_FOLDER = __DIR__.'/../resources/stubs/';

    protected bool $stubContentLoaded = false;
    protected string $stubContent;

    public function __construct(
        protected string $stubFileName,
        protected array $variables = []
    ) {}

    public function getStubFileName(): string
    {
        return $this->stubFileName.static::STUB_SUFFIX;
    }

    public function getStubContent(): string
    {
        if ($this->stubContentLoaded !== true) {
            $this->stubContentLoaded = true;
            $this->stubContent = $this->readStubFileContent();
        }

        return $this->stubContent;
    }

    public function getVariables(): array
    {
        return $this->variables;
    }

    public function setVariables(array $variables): self
    {
        $this->variables = $variables;

        return $this;
    }

    public function setVariable(string $variable, string $value): self
    {
        $this->variables[$variable] = $value;

        return $this;
    }

    public function getVariable(string $variable): string|null
    {
        return $this->variables[$variable] ?? null;
    }

    public function __set(string $name, $value): void
    {
        $this->setVariable($name, $value);
    }

    public function __get(string $name)
    {
        return $this->getVariable($name);
    }

    public function generate(): string
    {
        return $this->replaceVariables(
            $this->getStubContent(),
            $this->variables
        );
    }

    public function __toString(): string
    {
        return $this->generate();
    }

    protected function replaceVariables(string $content, array $variables): string
    {
        $names = [];
        $values = [];

        foreach ($variables as $name => $value) {
            $names[] = '{{'.$name.'}}';
            $values[] = $value;
            $names[] = '{{ '.$name.' }}';
            $values[] = $value;
        }

        return str_replace($names, $values, $content);
    }

    private function readStubFileContent(): string
    {
        $overriddenStubFilePath = $this->resolveOverriddenStubFilePath();

        return File::get(
            File::exists($overriddenStubFilePath)
                ? $overriddenStubFilePath
                : static::STUB_FOLDER.$this->getStubFileName()
        );
    }

    private function resolveOverriddenStubFilePath(): string
    {
        return resource_path('stubs/metamodel-eloquent/'.$this->getStubFileName());
    }
}
