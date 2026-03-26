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

        $this->line((new ConfigGenerator())->generate());
        $this->line((new LayoutCssGenerator())->generate());
        $this->line((new AppGenerator())->generate());
        $this->line((new SidebarGenerator())->generate());
        $this->line((new HeadGenerator())->generate());
        $this->line((new HeaderGenerator())->generate());
        $this->line((new ScriptGenerator())->generate());
        $this->line((new GeneralJsGenerator())->generate());
        $this->line((new FooterGenerator())->generate());

        $this->info('Pilot setup completed.');
    }
}