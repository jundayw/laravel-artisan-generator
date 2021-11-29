<?php

namespace Jundayw\LaravelArtisanGenerator\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class GeneratorControllerCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'generator:controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new controller class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $stub = view('artisan-generator::controller')->getPath();

        if ($this->option('parent')) {
            $stub = view('artisan-generator::controller-nested')->getPath();
        }

        if ($this->option('repository')) {
            $stub = view('artisan-generator::controller-repository')->getPath();
        }

        if ($this->option('repository') && $this->option('method')) {
            $stub = view('artisan-generator::controller-repository-method')->getPath();
        }

        if ($this->option('repository') && $this->option('method') && $this->option('request')) {
            $stub = view('artisan-generator::controller-repository-method-request')->getPath();
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
        return $rootNamespace . $this->defaultNamespace;
    }

    /**
     * Execute the console command.
     *
     * @return bool|void|null
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        if (!$this->option('label')) {
            $label = function () use (&$label) {
                if ($labelName = $this->ask('The label for the given controller', 'Label')) {
                    return $labelName;
                }
                return $label();
            };
            $this->input->setOption('label', $label());
        }

        $this->defaultNamespace = $this->laravel['config']->get('generator.controller');

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

        if ($this->option('repository')) {
            $replace = array_merge($replace, $this->buildRepositoryReplacements());
        }

        if ($this->option('model')) {
            $replace = array_merge($replace, $this->buildModelReplacements());
        }

        foreach ($this->option('request') as $key => $request) {
            $replace = array_merge($replace, $this->buildRequestReplacements($key, $request));
        }

        if ($this->option('method')) {
            foreach ($this->option('view') as $key => $view) {
                $replace = array_merge($replace, $this->buildViewReplacements($key, $view));
            }
        }

        $replace = array_merge($replace, $this->buildControllerReplacements());

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    /**
     * Build the repository replacement values.
     *
     * @return array
     */
    protected function buildRepositoryReplacements()
    {
        $this->defaultNamespace = $this->laravel['config']->get('generator.repository');

        $modelClass = $this->qualifyClass($this->option('repository'));

        $arguments = [
            'name' => $this->option('repository'),
        ];

        if ($this->option('model')) {
            $arguments['--model'] = $this->option('model');
        } else {
            $this->input->setOption('method', false);
            $this->input->setOption('request', []);
            $this->input->setOption('view', []);
        }

        if ($this->option('method')) {
            $arguments['--method'] = $this->option('method');
        }

        $this->call('generator:repository', $arguments);

        return [
            'DummyRepositoryRootNamespace' => $this->getDefaultNamespace($this->getNamespace($this->rootNamespace())),
            'DummyRepositoryClassNamespace' => $modelClass,
            'DummyRepositoryClass' => class_basename($modelClass),
            'DummyRepositoryVariable' => lcfirst(class_basename($modelClass)),
        ];
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
     * Build the request replacement values.
     *
     * @return array
     */
    protected function buildRequestReplacements($key, $name)
    {
        $this->defaultNamespace = $this->laravel['config']->get('generator.request');

        $modelClass = $this->qualifyClass($name);

        $this->call('generator:request', [
            'name' => $name,
            '--parent' => true,
        ]);

        $key++;

        return [
            'DummyRequestRootNamespace' => $this->getDefaultNamespace($this->getNamespace($this->rootNamespace())),
            "DummyRequestClass{$key}Namespace" => $modelClass,
            "DummyRequestClass{$key}" => class_basename($modelClass),
            "DummyRequestVariable{$key}" => lcfirst(class_basename($modelClass)),
        ];
    }

    /**
     * Build the view resources replacement values.
     *
     * @return array
     */
    protected function buildViewReplacements($key, $name)
    {
        $view = Str::replaceLast('Controller', '', $this->getNameInput());

        $this->call('generator:view', [
            'name' => $view,
            '--view' => $name,
        ]);

        $tpl      = str_replace('/', '.', sprintf('%s/%s', strtolower($view), $name));
        $variable = ucfirst($name);

        return [
            "DummyViewVariable{$variable}" => $tpl,
        ];
    }

    /**
     * Build the controller replacement values.
     *
     * @return array
     */
    protected function buildControllerReplacements()
    {
        $this->defaultNamespace = $this->laravel['config']->get('generator.controller');

        $modelClass = $this->qualifyClass($this->getNameInput());

        return [
            'DummyLabel' => $this->option('label'),
            'DummyControllerRootNamespace' => $this->getDefaultNamespace($this->getNamespace($this->rootNamespace())),
            'DummyControllerClassNamespace' => $modelClass,
            'DummyControllerClass' => class_basename($modelClass),
            'DummyControllerVariable' => lcfirst(class_basename($modelClass)),
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
            ['parent', 'p', InputOption::VALUE_NONE, 'Generate a nested resource controller class.'],
            ['label', null, InputOption::VALUE_OPTIONAL, 'Label for the controller.'],
            ['method', null, InputOption::VALUE_NONE, 'Generate methods for the controller.'],
            ['repository', null, InputOption::VALUE_OPTIONAL, 'Generate a resource controller for the given repository.'],
            ['request', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Generate a resource controller for the given request.'],
            ['view', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Generate a resource controller for the given view resources.', ['create', 'edit', 'list']],
            ['model', null, InputOption::VALUE_OPTIONAL, 'Generate a resource controller for the given model.'],
            ['force', 'f', InputOption::VALUE_NONE, 'Force the operation to run when in production'],
        ];
    }
}
