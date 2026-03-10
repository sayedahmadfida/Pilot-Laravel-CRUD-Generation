<?php

namespace Fida\Crud\Commands;

use Illuminate\Console\Command;
use Fida\Crud\Generators\ConfigGenerator;
use Fida\Crud\Generators\LayoutAppGenerator;
use Fida\Crud\Generators\LayoutCssGenerator;

class PilotConfigCommand extends Command
{
    protected $signature = 'pilot:config';

    protected $description = 'Setup Pilot configuration';

    public function handle()
    {
        $this->info('Pilot configuration started...');

        (new ConfigGenerator())->generate();
        (new LayoutAppGenerator())->generate();
        (new LayoutCssGenerator())->generate();

        $this->info('Pilot setup completed.');
    }
}