<?php

namespace Fida\Crud\Commands;

use Fida\Crud\Generators\ControllerGenerator;
use Fida\Crud\Generators\JsGenerator;
use Fida\Crud\Generators\ModelGenerator;
use Fida\Crud\Generators\RouteGenerator;
use Fida\Crud\Generators\ViewGenerator;
use Illuminate\Console\Command;

class PilotMakeCrudCommand extends Command
{
    protected $signature = 'pilot:crud {name}';

    protected $description = 'Create full CRUD module for a given model name';

    public function handle()
    {
        $name = $this->argument('name');

        (new ModelGenerator)->generate($name);
        (new ControllerGenerator)->generate($name);
        (new ViewGenerator)->generate($name);
        (new JsGenerator)->generate($name);
        (new RouteGenerator)->generate($name);

        $this->info('CRUD generated successfully.');
    }
}