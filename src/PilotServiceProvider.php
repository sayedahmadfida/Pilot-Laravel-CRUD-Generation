<?php

namespace Fida\Crud;

use Illuminate\Support\ServiceProvider;
use Fida\Crud\Commands\PilotMakeCrudCommand;
use Fida\Crud\Commands\PilotConfigCommand;

class PilotServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            PilotMakeCrudCommand::class,
            PilotConfigCommand::class,
        ]);
    }
}