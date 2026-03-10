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

        // Store all generators in an array
        $generators = [
            new ModelGenerator(),
            new ControllerGenerator(),
            new ViewGenerator(),
            new JsGenerator(),
            new RouteGenerator(),
        ];

        // Check each generator first
        foreach ($generators as $generator) {
            $result = $generator->generate($name);

            if (strpos($result, 'already exists') !== false) {
                $this->error($result); 
                return;
            }
        }

        // If none existed, show success
        $this->info('CRUD generated successfully.');
    }
}