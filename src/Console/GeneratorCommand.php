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

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle()
    {
        $arguments = [];

        $controller = function () use (&$controller) {
            if ($controllerName = $this->ask('The name of the controller')) {
                return $controllerName;
            }
            return $controller();
        };

        $arguments['name'] = $controller();

        $label = function () use (&$label) {
            if ($labelName = $this->ask('The label for the given controller')) {
                return $labelName;
            }
            return $label();
        };

        $arguments['--label'] = $label();

        if ($this->confirm("Do you want to generate methods for given controller", true)) {
            $arguments['--method'] = true;
        }

        if ($this->confirm("Do you want to generate a repository for given controller", true)) {

            $repository = function () use (&$repository) {
                if ($repositoryName = $this->ask('The name of the repository for the given controller')) {
                    return $repositoryName;
                }
                return $repository();
            };

            $arguments['--repository'] = $repository();
        }

        if ($this->confirm("Do you want to generate a model for given controller", true)) {

            $model = function () use (&$model) {
                if ($modelName = $this->ask('The name of the model for the given controller')) {
                    return $modelName;
                }
                return $model();
            };

            $arguments['--model'] = $model();
        }

        $this->call('generator:controller', $arguments);
    }
}
