<?php

namespace Fida\Crud\Generators;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ViewGenerator
{
    public function generate($name)
    {
        $folder = resource_path('views/' . Str::lower($name));

        // Ensure directory exists
        File::ensureDirectoryExists($folder);

        $nameLower = Str::lower($name);

        // Index view
        $indexContent = <<<HTML
@extends('layouts.app')

@section('content')

<h1>Create {$name}</h1>
<!-- Include JS -->
@endsection
@section('scripts')
<script src="{{ asset('assets/js/{$nameLower}.js') }}"></script>
@endsection
HTML;

        File::put($folder.'/index.blade.php', $indexContent);

        // Create view

        File::put($folder.'/create.blade.php', 
        
'
<form action="#" method="POST" id="create-'.strtolower($name).'-form">
    @csrf
    <h1>Create '.$name.'</h1>
</form>
'   

        );
    }
}