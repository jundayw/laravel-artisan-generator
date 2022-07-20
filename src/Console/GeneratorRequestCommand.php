<?php

namespace Jundayw\LaravelArtisanGenerator\Console;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class GeneratorRequestCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'generator:request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new request class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Request';

    private function loadViewsFromStubs($name): string
    {
        $view = sprintf('%s::%s', $this->laravel['config']->get('generator.publishes.stubs'), $name);
        return view($view)->getPath();
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $stub = $this->loadViewsFromStubs('request');

        if ($this->option('parent')) {
            $stub = $this->loadViewsFromStubs('request-nested');
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
        return $rootNamespace . '\\' . implode('\\', array_slice(explode('\\', $this->laravel['config']->get('generator.request')), 1));
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

        $replace = array_merge($replace, $this->buildRequestReplacements());

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    /**
     * Build the request replacement values.
     *
     * @return array
     */
    protected function buildRequestReplacements()
    {
        $modelClass = $this->qualifyClass($this->getNameInput());

        return [
            'DummyRepositoryRootNamespace' => $this->getDefaultNamespace($this->getNamespace($this->rootNamespace())),
            'DummyRequestClassNamespace' => $modelClass,
            'DummyRequestClass' => class_basename($modelClass),
            'DummyRequestVariable' => lcfirst(class_basename($modelClass)),
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
            ['parent', 'p', InputOption::VALUE_NONE, 'Generate a nested resource request class.'],
            ['force', 'f', InputOption::VALUE_NONE, 'Force the operation to run when in production'],
        ];
    }
}
