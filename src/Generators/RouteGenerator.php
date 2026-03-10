<?php

namespace Fida\Crud\Generators;

use Illuminate\Support\Str;

class RouteGenerator
{
    public function generate($name)
    {
        $nameLower = Str::lower($name).'s';

        $webRoutes = base_path('routes/web.php');

        // Define the new CRUD route block
        $routeBlock = "\n// Routes for {$name} CRUD\n";
        $routeBlock .= "Route::resource('{$nameLower}', App\Http\Controllers\\{$name}Controller::class);\n";
       
        // Read existing content
        $existingContent = file_get_contents($webRoutes);

        // Avoid duplicate routes
        if (strpos($existingContent, "{$name}Controller") === false) {
            file_put_contents($webRoutes, $existingContent . $routeBlock);
        }
    }
}