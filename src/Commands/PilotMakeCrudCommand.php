<?php

namespace Fida\Crud\Commands;

use Fida\Crud\Generators\AddRouteGenerator;
use Fida\Crud\Generators\ControllerGenerator;
use Fida\Crud\Generators\CreateFormGenerator;
use Fida\Crud\Generators\JsScriptGenerator;
use Fida\Crud\Generators\MigrationGenerator;
use Fida\Crud\Generators\ModelGenerator;
use Fida\Crud\Generators\RequestGenerator;
use Fida\Crud\Generators\RouteGenerator;
use Fida\Crud\Generators\ViewGenerator;
use Illuminate\Console\Command;

class PilotMakeCrudCommand extends Command
{
    //protected $signature = 'pilot:crud {name}';


    protected $signature = 'pilot:crud 
                            {name : The name of the model} 
                            {--columns= : Comma-separated column definitions like name:string,price:decimal}';

    protected $description = 'Generate CRUD for a model including migration, controller, views, etc.';


    public function handle()
    {

        $name = $this->argument('name');
        $columnsOption = $this->option('columns');

        $pairs = explode(',', $columnsOption);

        $columns = [];

        foreach ($pairs as $pair) {
            $parts = explode(':', $pair);

            $colName = $parts[0] ?? null;
            $colType = $parts[1] ?? 'string';

            $colRules = count($parts) > 2  ? implode(':', array_slice($parts, 2)) : '';

            $columns[] = [
                'name' => $colName,
                'type' => $colType,
                'rules' => $colRules,
            ];
        }



        $this->outputResult((new ModelGenerator())->generate($name, $columns));
        $this->outputResult((new ControllerGenerator())->generate($name));
        $this->outputResult((new ViewGenerator())->generate($name, $columns));
        $this->outputResult((new CreateFormGenerator())->generate($name, $columns));
        $this->outputResult((new MigrationGenerator())->generate($name, $columns));
        $this->outputResult((new RequestGenerator())->generate($name, $columns));
        $this->outputResult((new JsScriptGenerator())->generate($name, $columns));
        $this->outputResult((new AddRouteGenerator())->generate($name));
        $this->outputResult((new RouteGenerator())->generate($name));

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
