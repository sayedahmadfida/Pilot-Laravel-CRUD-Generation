<?php

namespace Fida\Crud\Generators;

class ModelGenerator
{
    public function generate($name)
    {
        $modelPath = app_path("Models/{$name}.php");

        if (file_exists($modelPath)) {
            return;
        }

        $content = "<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class {$name} extends Model
{
    protected \$guarded = [];
}
";

        file_put_contents($modelPath, $content);
    }
}