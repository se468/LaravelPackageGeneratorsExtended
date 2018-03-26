<?php

namespace se468\LaravelPackageGeneratorsExtended\Commands;

use se468\LaravelPackageGeneratorsExtended\Commands\PackageGeneratorCommand;
use Illuminate\Filesystem\Filesystem;


class MakeModel extends PackageGeneratorCommand
{
    protected $signature = 'package:model {vendor} {package} {namespace} {name : The name of the model}
        {--path= : The location where the model file should be created relative to package src folder.}';

    protected $description = 'Create a new model file for your package. package:model {vendor} {package} {namespace} --path';

    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    public function handle()
    {
        $this->makeCommand();
    }

    protected function makeCommand()
    {
        $path = $this->getPath($this->argument('name'));
        
        $this->beforeGeneration($path);

        $this->makeDirectory($path);

        $this->files->put($path, $this->compileStub());

        $this->afterGeneration($path);
    }

    protected function getPath($name)
    {
        return $this->getPackagePath().'/Http/' . $name . '.php';
    }

    protected function compileStub () {
        $stub = $this->files->get(__DIR__ . '/../stubs/Model.stub');

        $this->replaceNamespace($stub)
            ->replaceClassName($stub);

        return $stub;
    }

    protected function replaceClassName(&$stub) {
        $stub = str_replace('{{class}}', $this->argument('name'), $stub);

        return $this;
    }
}