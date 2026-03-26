<?php

namespace Fida\Crud\Generators;
use Illuminate\Support\Facades\File;

class ModelGenerator
{
    public function generate($name)
    {

        $modelPath = app_path("Models/{$name}.php");
        if (File::exists($modelPath)) {
            return [
                'status' => 'exists',
                'message' => "{$name} model already exists at:\n".$modelPath,
            ];
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

        return [
            'status' => 'created',
            'message' => "{$name} model created at:\n".$modelPath,
        ];
    }
}