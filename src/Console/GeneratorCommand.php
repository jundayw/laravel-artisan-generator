<?php

namespace Jundayw\LaravelArtisanGenerator\Console;

use Illuminate\Console\Command;

class GeneratorCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'generator';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'laravel artisan generator';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Generator';

    protected $parameters = [];

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle()
    {
        $this->buildController()
            ->buildControllerMethodAndView()
            ->buildControllerRequest()
            ->buildRepository()
            ->buildModel();

        $this->call('generator:controller', $this->parameters);
    }

    protected function buildController()
    {
        $label = function($default = null) use (&$label) {
            if ($labelName = $this->ask('The label for the given controller', $default)) {
                return $labelName;
            }
            return $label($default);
        };

        $this->parameters['--label'] = $label($this->laravel['config']->get('generator.default.label'));

        $controller = function($default = null) use (&$controller) {
            if ($controllerName = $this->ask('The name of the controller', $default)) {
                return $controllerName;
            }
            return $controller($default);
        };

        $this->parameters['name'] = $controller($this->laravel['config']->get('generator.default.controller'));

        return $this;
    }

    protected function buildControllerMethodAndView()
    {
        if ($this->confirm("Do you want to generate methods for given controller", true)) {

            $this->parameters['--method'] = true;

            $view = function($default = null) use (&$view) {
                if ($viewName = $this->ask('The name of the views for the given controller', $default)) {
                    return explode(',', $viewName);
                }
                return $view($default);
            };

            $this->parameters['--view'] = $view($this->laravel['config']->get('generator.default.view'));
        }

        return $this;
    }

    protected function buildControllerRequest()
    {
        if ($this->confirm("Do you want to generate requests for given controller", true)) {

            $request = function($default = null) use (&$request) {
                if ($requestName = $this->ask('The name of the request for the given controller', $default)) {
                    return explode(',', $requestName);
                }
                return $request($default);
            };

            $this->parameters['--request'] = $request($this->laravel['config']->get('generator.default.request'));
        }

        return $this;
    }

    protected function buildRepository()
    {
        if ($this->confirm("Do you want to generate a repository for given controller", true)) {

            $repository = function($default = null) use (&$repository) {
                if ($repositoryName = $this->ask('The name of the repository for the given controller', $default)) {
                    return $repositoryName;
                }
                return $repository($default);
            };

            $this->parameters['--repository'] = $repository($this->laravel['config']->get('generator.default.repository'));
        }

        return $this;
    }

    protected function buildModel()
    {
        if ($this->confirm("Do you want to generate a model for given controller", true)) {

            $model = function($default = null) use (&$model) {
                if ($modelName = $this->ask('The name of the model for the given controller', $default)) {
                    return $modelName;
                }
                return $model($default);
            };

            $this->parameters['--model'] = $model($this->laravel['config']->get('generator.default.model'));
        }

        return $this;
    }
}
