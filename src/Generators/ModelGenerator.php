<?php

namespace Fida\Crud\Generators;
use Illuminate\Support\Facades\File;

class ModelGenerator
{
    public function generate($name)
    {

        $modelPath = app_path("Models/{$name}.php");
        if (File::exists($modelPath)) {
            return "{$name} model already exists!";
        }
       

        $content = "<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class {$name} extends Model
{
    protected \$fillable = [
        // Add fillable fields here
    ];
}
";

        file_put_contents($modelPath, $content);

        return "{$name} model created successfully!";
    }
}