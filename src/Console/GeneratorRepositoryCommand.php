<?php

namespace Jundayw\LaravelArtisanGenerator\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class GeneratorRepositoryCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'generator:repository';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';

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
        $stub = $this->loadViewsFromStubs('repository');

        if ($this->option('parent')) {
            $stub = $this->loadViewsFromStubs('repository-nested');
        }

        if ($this->option('model')) {
            $stub = $this->loadViewsFromStubs('repository-model');
        }

        if ($this->option('model') && $this->option('method')) {
            $stub = $this->loadViewsFromStubs('repository-model-method');
        }

        return $stub;
    }

    private $defaultNamespace;

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\\' . implode('\\', array_slice(explode('\\', $this->defaultNamespace), 1));
    }

    /**
     * Execute the console command.
     *
     * @return bool|void|null
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $this->defaultNamespace = $this->laravel['config']->get('generator.repository');
        parent::handle();
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

        if ($this->option('model')) {
            $replace = array_merge($replace, $this->buildModelReplacements());
        } else {
            $this->input->setOption('method', false);
        }

        $replace = array_merge($replace, $this->buildRepositoryReplacements());

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
        $this->defaultNamespace = $this->laravel['config']->get('generator.model');

        $modelClass = $this->qualifyClass($this->option('model'));

        $this->call('generator:model', [
            'name' => $this->option('model'),
            '--parent' => true,
        ]);

        return [
            'DummyModelRootNamespace' => $this->getDefaultNamespace($this->getNamespace($this->rootNamespace())),
            'DummyModelClassNamespace' => $modelClass,
            'DummyModelClass' => class_basename($modelClass),
            'DummyModelVariable' => lcfirst(class_basename($modelClass)),
        ];
    }

    /**
     * Build the repository replacement values.
     *
     * @return array
     */
    protected function buildRepositoryReplacements()
    {
        $repository = Str::replaceLast('Repository', '', $this->getNameInput());

        $url   = strtolower($repository);
        $route = str_replace('/', '.', $url);

        $this->defaultNamespace = $this->laravel['config']->get('generator.repository');

        $modelClass = $this->qualifyClass($this->getNameInput());

        return [
            'DummyRepositoryRootNamespace' => $this->getDefaultNamespace($this->getNamespace($this->rootNamespace())),
            'DummyRepositoryClassNamespace' => $modelClass,
            'DummyRepositoryClass' => class_basename($modelClass),
            'DummyRepositoryVariable' => lcfirst(class_basename($modelClass)),
            'DummyRepositoryUrl' => $url,
            'DummyRepositoryRoute' => $route,
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
            ['parent', 'p', InputOption::VALUE_NONE, 'Generate a nested resource repository class.'],
            ['model', null, InputOption::VALUE_OPTIONAL, 'Generate a resource repository for the given model.'],
            ['method', null, InputOption::VALUE_NONE, 'Generate methods for the repository.'],
            ['force', 'f', InputOption::VALUE_NONE, 'Force the operation to run when in production'],
        ];
    }
}
