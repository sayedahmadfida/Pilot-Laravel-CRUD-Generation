<?php

namespace Fida\Crud\Generators;

use Illuminate\Support\Facades\File;

class ScriptGenerator
{
    public function generate()
    {


        $layoutPath = resource_path("views/layouts/partials/script.blade.php");

        // Check if file already exists
        if (File::exists($layoutPath)) {
            return "Script layout already exists!";
        }

        // Ensure layouts directory exists
        $layoutsDir = resource_path("views/layouts/partials");
        if (!File::exists($layoutsDir)) {
            File::makeDirectory($layoutsDir, 0755, true);
        }

        $content = "

<script src=\"https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js\"
    crossorigin=\"anonymous\"></script>
  <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
  <script src=\"https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js\"
    crossorigin=\"anonymous\"></script>
  <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
  <script src=\"https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js\" crossorigin=\"anonymous\"></script>
  <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
  <script src=\"https://adminlte.io/themes/v4/js/adminlte.js\"></script>
  <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
  <script>
    const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
    const Default = {
      scrollbarTheme: 'os-theme-light',
      scrollbarAutoHide: 'leave',
      scrollbarClickScroll: true,
    };
    document.addEventListener('DOMContentLoaded', function () {
      const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);

      // Disable OverlayScrollbars on mobile devices to prevent touch interference
      const isMobile = window.innerWidth <= 992;

      if (
        sidebarWrapper &&
        OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined &&
        !isMobile
      ) {
        OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
          scrollbars: {
            theme: Default.scrollbarTheme,
            autoHide: Default.scrollbarAutoHide,
            clickScroll: Default.scrollbarClickScroll,
          },
        });
      }
    });
  </script>
  <script src=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/js/all.min.js\"
    integrity=\"sha512-6BTOlkauINO65nLhXhthZMtepgJSghyimIalb+crKRPhvhmsCdnIuGcVbR5/aQY2A+260iC1OPy1oCdB6pSSwQ==\"
    crossorigin=\"anonymous\" referrerpolicy=\"no-referrer\"></script>
  <!--end::OverlayScrollbars Configure-->

  <script src=\"https://code.jquery.com/jquery-4.0.0.min.js\" integrity=\"sha256-OaVG6prZf4v69dPg6PhVattBXkcOWQB62pdZ3ORyrao=\" crossorigin=\"anonymous\"></script>

  <script src=\"https://cdn.jsdelivr.net/npm/jquery-validation@1.22.1/dist/jquery.validate.min.js\"></script>

  
  <script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script>
  
  <script src=\"{{asset('assets/js/general.js')}}\"></script>

  <!--end::Script-->

    ";

        File::put($layoutPath, $content);

        return "Script layout created successfully!";
    }
}
