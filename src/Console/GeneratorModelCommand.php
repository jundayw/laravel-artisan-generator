<?php

namespace Jundayw\LaravelArtisanGenerator\Console;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class GeneratorModelCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'generator:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new model class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $stub = view('artisan-generator-stubs::model')->getPath();

        if ($this->option('parent')) {
            $stub = view('artisan-generator-stubs::model-nested')->getPath();
        }

        return $stub;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . $this->laravel['config']->get('generator.model');
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in base namespace.
     *
     * @param string $name
     * @return string
     */
    protected function buildClass($name)
    {
        $replace = [];

        $replace = array_merge($replace, $this->buildModelReplacements());

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    /**
     * Build the model replacement values.
     *
     * @return array
     */
    protected function buildModelReplacements()
    {
        $modelClass = $this->qualifyClass($this->getNameInput());

        return [
            'DummyModelRootNamespace' => $this->getDefaultNamespace($this->getNamespace($this->rootNamespace())),
            'DummyModelClassNamespace' => $modelClass,
            'DummyModelClass' => class_basename($modelClass),
            'DummyModelVariable' => lcfirst(class_basename($modelClass)),
        ];
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the class'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['parent', 'p', InputOption::VALUE_NONE, 'Generate a nested resource model class.'],
            ['force', 'f', InputOption::VALUE_NONE, 'Force the operation to run when in production'],
        ];
    }
}
