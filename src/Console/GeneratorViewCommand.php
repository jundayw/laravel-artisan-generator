<?php

namespace Jundayw\LaravelArtisanGenerator\Console;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class GeneratorViewCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'generator:view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new view resources';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'View';

    private $stub;

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $stub = sprintf('%s::%s', $this->laravel['config']->get('generator.publishes.views'), $this->stub);
        return view($stub)->getPath();
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . $this->laravel['config']->get('generator.view');
    }

    /**
     * Execute the console command.
     *
     * @return bool|null
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        if (!$this->option('view')) {
            $view = function() use (&$view) {
                if ($viewName = $this->ask('View name for the given view resources')) {
                    return $viewName;
                }
                return $view();
            };
            $this->input->setOption('view', $view());
        }

        $this->stub = $this->option('view');

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

        $url         = strtolower($this->getNameInput());
        $route       = str_replace('/', '.', $url);
        $method      = $this->stub;
        $urlMethod   = sprintf('%s/%s', $url, $method);
        $routeMethod = str_replace('/', '.', $urlMethod);

        return [
            'DummyViewRootNamespace' => $this->getDefaultNamespace($this->getNamespace($this->rootNamespace())),
            'DummyViewClassNamespace' => $modelClass,
            'DummyViewClass' => class_basename($modelClass),
            'DummyViewVariable' => lcfirst(class_basename($modelClass)),
            'DummyViewUrlMethod' => $urlMethod,
            'DummyViewRouteMethod' => $routeMethod,
            'DummyViewUrlClass' => $url,
            'DummyViewRouteClass' => $route,
            'DummyViewMethod' => $method,
        ];
    }

    /**
     * Get the destination class path.
     *
     * @param string $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = str_replace('\\', '/', $name);
        return sprintf('%s/%s/%s%s', $this->laravel['path.base'], strtolower($name), $this->stub, '.blade.php');
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return 'resources\\';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The namespace of the view resources'],
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
            ['view', null, InputOption::VALUE_REQUIRED, 'Generate a resource controller for the given view resources.'],
            ['force', 'f', InputOption::VALUE_NONE, 'Force the operation to run when in production'],
        ];
    }
}
