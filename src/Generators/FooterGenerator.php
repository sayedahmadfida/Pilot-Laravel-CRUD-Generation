<?php

namespace Fida\Crud\Generators;

use Illuminate\Support\Facades\File;

class FooterGenerator
{
    public function generate()
    {
        

        $layoutPath = resource_path("views/layouts/partials/footer.blade.php");

        // Check if file already exists
        if (File::exists($layoutPath)) {
            return "Footer layout already exists!";
        }

        // Ensure layouts directory exists
        $layoutsDir = resource_path("views/layouts/partials");
        if (!File::exists($layoutsDir)) {
            File::makeDirectory($layoutsDir, 0755, true);
        }

        $content = "
<footer class=\"app-footer\">
      <!--begin::To the end-->
      <div class=\"float-end d-none d-sm-inline\">Anything you want</div>
      <!--end::To the end-->
      <!--begin::Copyright-->
      <strong>
        Copyright &copy; 2014-2025&nbsp;
        <a href=\"https://adminlte.io\" class=\"text-decoration-none\">AdminLTE.io</a>.
      </strong>
      All rights reserved.
      <!--end::Copyright-->
    </footer>
";

        File::put($layoutPath, $content);

        return "Footer layout created successfully!";
    }
}