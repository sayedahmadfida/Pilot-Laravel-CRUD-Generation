<?php

namespace Fida\Crud\Generators;

use Illuminate\Support\Facades\File;

class HeadGenerator
{
  public function generate()
  {


    $layoutPath = resource_path("views/layouts/partials/head.blade.php");

    // Check if file already exists
    if (File::exists($layoutPath)) {
      return [
        'status' => 'exists',
        'message' => "Head layout already exists at: \n" . $layoutPath,
      ];
    }

    // Ensure layouts directory exists
    $layoutsDir = resource_path("views/layouts/partials");
    if (!File::exists($layoutsDir)) {
      File::makeDirectory($layoutsDir, 0755, true);
    }

    $content = "

  <!--begin::Accessibility Features-->
  <!-- Skip links will be dynamically added by accessibility.js -->
  <meta name=\"supported-color-schemes\" content=\"light dark\" />
  <link rel=\"preload\" href=\"https://adminlte.io/themes/v4/css/adminlte.css\" as=\"style\" />
  <!--end::Accessibility Features-->

  <!--begin::Fonts-->
  <link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css\"
    integrity=\"sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=\" crossorigin=\"anonymous\" media=\"print\"
    onload=\"this.media='all'\" />
  <!--end::Fonts-->

  <!--begin::Third Party Plugin(OverlayScrollbars)-->
  <link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css\"
    crossorigin=\"anonymous\" />
  <!--end::Third Party Plugin(OverlayScrollbars)-->

  <!--begin::Third Party Plugin(Bootstrap Icons)-->
  <link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css\"
    crossorigin=\"anonymous\" />
  <!--end::Third Party Plugin(Bootstrap Icons)-->

  <!--begin::Required Plugin(AdminLTE)-->
  <link rel=\"stylesheet\" href=\"https://adminlte.io/themes/v4/css/adminlte.css\" />
  <!--end::Required Plugin(AdminLTE)-->
  
    <style>
      .error{
        color: #a30000
      }
    </style>

  <!--end::Head-->
  
  <!--begin::Body-->

";

    File::put($layoutPath, $content);

    return [
      'status' => 'created',
      'message' => "Head layout created at:\n" . $layoutPath,
    ];
  }
}
