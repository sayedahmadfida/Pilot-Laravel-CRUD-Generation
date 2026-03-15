<?php

namespace Fida\Crud\Generators;

use Illuminate\Support\Facades\File;

class WelcomeGenerator
{
    public function generate()
    {
        $viewPath = resource_path("views/welcome.blade.php");

        // Prevent overwrite
        if (File::exists($viewPath)) {
            return "welcome.blade.php already exists!";
        }

        $content = <<<BLADE
@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <div class="text-center">
        <h1>Welcome</h1>
        <p>This welcome page was generated automatically.</p>

        <div class="mt-4">
            <a href="#" class="btn btn-primary">Get Started</a>
        </div>
    </div>
</div>

@endsection
BLADE;

        File::put($viewPath, $content);

        return "welcome.blade.php created successfully!";
    }
}