<?php

namespace Fida\Crud\Generators;

class JsGenerator
{
    public function generate($name)
    {
        $nameLower = strtolower($name);

        $jsPath = public_path("assets/js/{$nameLower}.js");

        if (file_exists($jsPath)) {
            return;
        }

        // Ensure directory exists
        if (!file_exists(dirname($jsPath))) {
            mkdir(dirname($jsPath), 0755, true);
        }

        $content = "console.log('{$name} JS file loaded');

$(document).ready(function() {

    $('#create-{$nameLower}-form').validate({
        rules: {
            // Define validation rules here
        },
        submitHandler: function(form) {
            alert('Create {$name} form submitted');
        }
    });

});
";

        file_put_contents($jsPath, $content);
    }
}