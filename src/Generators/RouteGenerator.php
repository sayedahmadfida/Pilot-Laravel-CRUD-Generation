<?php

namespace Fida\Crud\Generators;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class RouteGenerator
{
    /**
     * Add a resource route to web.php if it doesn't exist
     *
     * @param string $name Singular name, e.g. "emp"
     * @return array Status and message
     */
    public function generate(string $name): array
    {
        
        $kebab = Str::kebab($name);
        $plural = Str::plural($kebab);
        
        $controller = Str::studly($plural) . 'Controller';
        $routeController = Str::studly($name) . 'Controller';

        $routePath = base_path('routes/web.php');

        // Read the file
        if (!File::exists($routePath)) {
            return [
                'status' => 'error',
                'message' => "web.php file not found at {$routePath}",
            ];
        }

        $routeContent = File::get($routePath);

        // Route definition to add
        $route = "Route::resource('{$plural}', \\App\\Http\\Controllers\\{$routeController}::class);";

        // Check if the route already exists
        if (Str::contains($routeContent, "Route::resource('{$plural}'")) {
            return [
                'status' => 'exists',
                'message' => "Route for '{$plural}' already exists in web.php",
            ];
        }

        // Append the route at the end
        File::append($routePath, "\n" . $route . "\n");

        return [
            'status' => 'created',
            'message' => "Route for '{$plural}' added to web.php",
        ];
    }
}