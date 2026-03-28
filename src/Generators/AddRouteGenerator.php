<?php

namespace Fida\Crud\Generators;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AddRouteGenerator
{
    public function generate($name)
    {
        
        $nameLower = Str::lower($name);
        $plural = Str::plural($nameLower);
        $title = Str::title($plural);

        $layoutPath = resource_path("views/layouts/partials/sidebar.blade.php");

        /*
        |--------------------------------------------------------------------------
        | Create Sidebar If Not Exists
        |--------------------------------------------------------------------------
        */

        if (!File::exists($layoutPath)) {

            $layoutsDir = resource_path("views/layouts/partials");

            if (!File::exists($layoutsDir)) {
                File::makeDirectory($layoutsDir, 0755, true);
            }

            $content = <<<BLADE
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">

    <div class="sidebar-brand">
        <a href="#" class="brand-link">
            <img src="https://adminlte.io/themes/v4/assets/img/AdminLTELogo.png"
                 class="brand-image opacity-75 shadow" />
            <span class="brand-text fw-light">AdminLTE 4</span>
        </a>
    </div>

    <div class="sidebar-wrapper">

        <nav class="mt-2">

            <ul class="nav sidebar-menu flex-column"
                data-lte-toggle="treeview"
                role="navigation"
                data-accordion="false"
                id="navigation">

            </ul>

        </nav>

    </div>

</aside>
BLADE;

            File::put($layoutPath, $content);
        }

        /*
        |--------------------------------------------------------------------------
        | Add New Menu Item
        |--------------------------------------------------------------------------
        */

        $sidebar = File::get($layoutPath);

        $menuItem = <<<HTML

<li class="nav-item">
    <a href="{{ route('{$plural}.index') }}" class="align-items-center d-flex nav-link {{ request()->is('{$plural}*') ? 'active' : '' }}">
        <i class="fa-solid fa-caret-right"></i>
        <p>{$title}</p>
    </a>
</li>

HTML;

        // Prevent duplicate menus
        if (Str::contains($sidebar, "route('{$plural}.index')")) {
            return [
                'status' => 'exists',
                'message' => "Menu already exists in sidebar.",
            ];
        }

        // Insert before closing ul
        $sidebar = str_replace(
            '</ul>',
            $menuItem . "\n</ul>",
            $sidebar
        );

        File::put($layoutPath, $sidebar);

        return [
            'status' => 'created',
            'message' => "{$title} menu added to sidebar.",
        ];
    }
}