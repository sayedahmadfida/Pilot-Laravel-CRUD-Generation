<?php

namespace Fida\Crud\Generators;

use Illuminate\Support\Facades\File;

class SidebarGenerator
{
    public function generate()
    {
        

        $layoutPath = resource_path("views/layouts/partials/sidebar.blade.php");

        // Check if file already exists
        if (File::exists($layoutPath)) {
            return "Sidebar layout already exists!";
        }

        // Ensure layouts directory exists
        $layoutsDir = resource_path("views/layouts/partials");
        if (!File::exists($layoutsDir)) {
            File::makeDirectory($layoutsDir, 0755, true);
        }

        $content = "
 <aside class=\"app-sidebar bg-body-secondary shadow\" data-bs-theme=\"dark\">
     <!--begin::Sidebar Brand-->
     <div class=\"sidebar-brand\">
         <!--begin::Brand Link-->
         <a href=\"#\" class=\"brand-link\">
             <!--begin::Brand Image-->
             <img src=\"https://adminlte.io/themes/v4/assets/img/AdminLTELogo.png\" alt=\"AdminLTE Logo\"
                 class=\"brand-image opacity-75 shadow\" />
             <!--end::Brand Image-->
             <!--begin::Brand Text-->
             <span class=\"brand-text fw-light\">AdminLTE 4</span>
             <!--end::Brand Text-->
         </a>
         <!--end::Brand Link-->
     </div>
     <!--end::Sidebar Brand-->
     <!--begin::Sidebar Wrapper-->
     <div class=\"sidebar-wrapper\">
         <nav class=\"mt-2\">
             <!--begin::Sidebar Menu-->
             <ul class=\"nav sidebar-menu flex-column\" data-lte-toggle=\"treeview\" role=\"navigation\"
                 aria-label=\"Main navigation\" data-accordion=\"false\" id=\"navigation\">

                 
             </ul>
             <!--end::Sidebar Menu-->
         </nav>
     </div>
     <!--end::Sidebar Wrapper-->
 </aside>
";

        File::put($layoutPath, $content);

        return "Sidebar layout created successfully!";
    }
}