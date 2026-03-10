<?php

namespace Fida\Crud\Generators;

use Illuminate\Support\Str;

class ControllerGenerator
{
    public function generate($name)
    {
        $controllerPath = app_path("Http/Controllers/{$name}Controller.php");

        // Ensure directory exists
        if (!file_exists(dirname($controllerPath))) {
            mkdir(dirname($controllerPath), 0755, true);
        }

        // Convert name to lowercase for view folder
        $viewFolder = Str::lower($name);

        $content = "<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class {$name}Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('{$viewFolder}.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('{$viewFolder}.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request \$request)
    {
        // Add store logic here
    }

    /**
     * Display the specified resource.
     */
    public function show(\$id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\$id)
    {
        return view('{$viewFolder}.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request \$request, \$id)
    {
        // Add update logic here
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\$id)
    {
        // Add delete logic here
    }
}";

        file_put_contents($controllerPath, $content);
    }
}