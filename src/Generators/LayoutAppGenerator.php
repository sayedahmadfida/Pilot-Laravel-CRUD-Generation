<?php

namespace Fida\Crud\Generators;

class LayoutAppGenerator
{
    public function generate()
    {
        $layoutPath = resource_path('views/layouts');

        if (!is_dir($layoutPath)) {
            mkdir($layoutPath, 0755, true);
        }

        $file = $layoutPath . '/app.blade.php';

        if (!file_exists($file)) {

            $content = "<!DOCTYPE html>
<html>
<head>
    <title>Pilot App</title>
    @include('layouts.css')
</head>
<body>

<div class=\"container\">
    @yield('content')
</div>

</body>
</html>";

            file_put_contents($file, $content);

            echo "app.blade.php created.\n";

        } else {

            echo "app.blade.php already exists.\n";

        }
    }
}