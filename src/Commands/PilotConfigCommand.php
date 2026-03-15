<?php

namespace Fida\Crud\Commands;

use AppendIterator;
use Fida\Crud\Generators\AppGenerator;
use Fida\Crud\Generators\ConfigGenerator;
use Fida\Crud\Generators\FooterGenerator;
use Fida\Crud\Generators\GeneralJsGenerator;
use Fida\Crud\Generators\HeaderGenerator;
use Fida\Crud\Generators\HeadGenerator;
use Fida\Crud\Generators\LayoutAppGenerator;
use Fida\Crud\Generators\LayoutCssGenerator;
use Fida\Crud\Generators\ScriptGenerator;
use Fida\Crud\Generators\SidebarGenerator;
use Illuminate\Console\Command;

class PilotConfigCommand extends Command
{
    protected $signature = 'pilot:config';

    protected $description = 'Setup Pilot configuration';

    public function handle()
    {
        $this->info('Pilot configuration started...');

        (new ConfigGenerator())->generate();
        (new LayoutCssGenerator())->generate();
        (new AppGenerator())->generate();
        (new SidebarGenerator())->generate();
        (new HeadGenerator())->generate();
        (new HeaderGenerator())->generate();
        (new ScriptGenerator())->generate();
        (new GeneralJsGenerator())->generate();
        (new FooterGenerator())->generate();

        $this->info('Pilot setup completed.');
    }
}