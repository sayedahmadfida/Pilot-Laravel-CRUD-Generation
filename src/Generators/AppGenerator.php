<?php

namespace Fida\Crud\Generators;

use Illuminate\Support\Facades\File;

class AppGenerator
{
    public function generate()
    {
        

        $layoutPath = resource_path("views/layouts/app.blade.php");

        // Check if file already exists
        if (File::exists($layoutPath)) {
            return "App layout already exists!";
        }

        // Ensure layouts directory exists
        $layoutsDir = resource_path("views/layouts");
        if (!File::exists($layoutsDir)) {
            File::makeDirectory($layoutsDir, 0755, true);
        }

        $content = "
<!doctype html>
<html lang=\"en\">
<head>
  <meta charset=\"utf-8\" />
  <title>AdminLTE</title>

  @include('layouts.partials.head')
</head>

<body class=\"layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary\">

  <div class=\"app-wrapper\">

    @include('layouts.partials.header')

    @include('layouts.partials.sidebar')

    <main class=\"app-main\">
      <div class=\"container-fluid\">
        @yield('content')
      </div>
    </main>

    @include('layouts.partials.footer')

  </div>

  @include('layouts.partials.script')
  @yield('scripts')
</body>
</html>
";

        File::put($layoutPath, $content);

        return "App layout created successfully!";
    }
}