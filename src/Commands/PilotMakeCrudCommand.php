<?php

namespace Fida\Crud\Commands;

use Fida\Crud\Generators\AddRouteGenerator;
use Fida\Crud\Generators\ControllerGenerator;
use Fida\Crud\Generators\CreateFormGenerator;
use Fida\Crud\Generators\JsGenerator;
use Fida\Crud\Generators\JsScriptGenerator;
use Fida\Crud\Generators\MigrationGenerator;
use Fida\Crud\Generators\ModelGenerator;
use Fida\Crud\Generators\RequestGenerator;
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

        
        $this->outputResult((new ModelGenerator())->generate($name));
        $this->outputResult((new ControllerGenerator())->generate($name));
        // $this->outputResult((new ViewGenerator())->generate($name));
        // $this->outputResult((new CreateFormGenerator())->generate($name));
        // $this->outputResult((new MigrationGenerator())->generate($name));
        // $this->outputResult((new RequestGenerator())->generate($name));
        // $this->outputResult((new JsScriptGenerator())->generate($name));
        // $this->outputResult((new AddRouteGenerator())->generate($name));
        // Store all generators in an array
        // $generators = [
        //     new ModelGenerator(),
        //     new ControllerGenerator(),
        //     new ViewGenerator(),
        //     new (),
        //     new (),
        //     new (),
        //     new (),
        //     new (),
        //     new (),
        // ];

        // // Check each generator first
        // foreach ($generators as $generator) {
        //     $result = $generator->generate($name);

        //     if (strpos($result, 'already exists') !== false) {
        //         $this->error($result); 
        //         return;
        //     }
        // }

        // If none existed, show success
        $this->info('CRUD generated successfully.');
    }

    protected function outputResult($result)
    {
        if ($result['status'] === 'exists') {
            $this->info($result['message']); 
        } else {
            $this->line($result['message']); 
        }
    }
}